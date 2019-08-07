<?php

namespace App\Http\Controllers;

use App\Clients\FacebookGuzzle6HttpClient;
use App\Exceptions\InvalidRequestException;
use App\Models\User;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialitesController extends Controller
{
    protected $supportedSocialites = [
        'facebook'
    ];

    protected function isAuthorized(string $socialite)
    {
        if (!in_array($socialite, $this->supportedSocialites)) {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
    }

    protected function getSocialiteConfig(string $socialite)
    {
        // $this->isAuthorized($socialite);

        return config("socialites.{$socialite}");
    }

    // GET: Socialite Login Url
    public function login(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);

        // Facebook Login
        session_start();
        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);

        $helper = $fb->getRedirectLoginHelper();

        // $permissions = ['email']; // Optional permissions
        // $permissions = ['default', 'email']; // Optional permissions
        $permissions = ['email', 'public_profile']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($config['redirect'], $permissions);

        echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
        die;
        // return redirect()->to(htmlspecialchars($loginUrl));
    }

    // POST: Socialite Callback Url
    public function callback(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);

        // Facebook Login Callback
        session_start();
        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            // echo 'Graph returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Graph returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        // echo '<h3>Access Token</h3>';
        // var_dump($accessToken->getValue());
        // $_SESSION['fb_access_token'] = (string)$accessToken;
        $_SESSION['fb_access_token'] = $accessToken->getValue();

        // session(['fb_access_token' => (string)$accessToken]);
        // session()->pull('fb_access_token', (string)$accessToken);
        // $request->session()->put('fb_access_token', (string)$accessToken);
        // $request->session()->put("{$socialite}_access_token", (string)$accessToken);
        $request->session()->put("{$socialite}_access_token", $accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        // echo '<h3>Metadata</h3>';
        // var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        // $tokenMetadata->validateAppId('{app-id}'); // Replace {app-id} with your app id
        $tokenMetadata->validateAppId($config['app_id']); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        // $tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }

            // echo '<h3>Long-lived</h3>';
            // var_dump($accessToken->getValue());
        }

        // $_SESSION['fb_access_token'] = (string)$accessToken;
        $_SESSION['fb_access_token'] = $accessToken->getValue();

        // session(['fb_access_token' => (string)$accessToken]);
        // session()->pull('fb_access_token', (string)$accessToken);
        // $request->session()->put('fb_access_token', (string)$accessToken);
        // $request->session()->put("{$socialite}_access_token", (string)$accessToken);
        $request->session()->put("{$socialite}_access_token", $accessToken->getValue());

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        // header('Location: https://example.com/members.php');

        // Retrieve User Profile via the Graph API
        try {
            // Returns a `Facebook\FacebookResponse` object
            // $response = $fb->get('/me?fields=id,name', '{access-token}');
            $response = $fb->get('/me', $accessToken);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        // $user = $response->getGraphUser();
        $user_profile = $response->getGraphUser();

        // echo 'Name: ' . $user['name'];
        // OR
        // echo 'Name: ' . $user->getName();

        // dd($user_profile->getId());
        $user = $this->findOrCreateUser($user_profile, $socialite);
        Auth::login($user);
        return redirect()->route('root');
    }

    // POST: Socialite Deauthorize Url
    public function deauthorize(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);

        $user_profile = $this->getUserProfile($request, $socialite);
        if (!in_array($socialite, $this->supportedSocialites) && $socialite == 'facebook') {
            $user = User::where([
                'facebook' => $user_profile->getId()
            ])->first();

            if ($user) {
                $user->update([
                    'facebook' => null
                ]);
            }
        } else {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
        return redirect()->route('root');
    }

    // POST: Socialite Delete Url
    public function delete(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);

        $user_profile = $this->getUserProfile($request, $socialite);
        if (!in_array($socialite, $this->supportedSocialites) && $socialite == 'facebook') {
            $user = User::where([
                'facebook' => $user_profile->getId()
            ])->first();

            if ($user) {
                $user->delete();
            }
        } else {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
        return redirect()->route('root');
    }

    protected function getAccessToken(Request $request, string $socialite)
    {
        // $this->isAuthorized($socialite);

        session_start();
        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            // echo 'Graph returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Graph returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // $_SESSION['fb_access_token'] = (string)$accessToken;
        $_SESSION['fb_access_token'] = $accessToken->getValue();

        // session(['fb_access_token' => (string)$accessToken]);
        // session()->pull('fb_access_token', (string)$accessToken);
        // $request->session()->put('fb_access_token', (string)$accessToken);
        // $request->session()->put("{$socialite}_access_token", (string)$accessToken);
        $request->session()->put("{$socialite}_access_token", $accessToken->getValue());

        return $accessToken;
    }

    protected function getUserProfile(Request $request, string $socialite)
    {
        // $this->isAuthorized($socialite);

        session_start();
        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);

        /*$helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            // echo 'Graph returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Graph returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            // exit;
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            throw new InvalidRequestException('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        $_SESSION['fb_access_token'] = (string)$accessToken;// Retrieve User Profile via the Graph API*/

        $accessToken = $this->getAccessToken($request, $socialite);

        try {
            // Returns a `Facebook\FacebookResponse` object
            // $response = $fb->get('/me?fields=id,name', '{access-token}');
            // $response = $fb->get('/me?fields=id,name,first_name,middle_name,last_name,email,gender,picture,url', $accessToken);
            $response = $fb->get('/me', $accessToken);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        // $user = $response->getGraphUser();
        $user_profile = $response->getGraphUser();

        // echo 'Name: ' . $user['name'];
        // OR
        // echo 'Name: ' . $user->getName();

        return $user_profile;
    }

    protected function findOrCreateUser(GraphUser $user_profile, string $socialite)
    {
        // $this->isAuthorized($socialite);

        if (!in_array($socialite, $this->supportedSocialites) && $socialite == 'facebook') {
            $user = User::where([
                'facebook' => $user_profile->getId()
            ])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $user_profile->getName(),
                    'avatar' => $user_profile->getPicture()->getUrl(),
                    'email' => $user_profile->getEmail(),
                    'real_name' => $user_profile->getFirstName() . $user_profile->getMiddleName() . $user_profile->getLastName(),
                    'gender' => $user_profile->getGender(),
                    'facebook' => $user_profile->getId()
                ]);
            }
            return $user;
        } else {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
    }
}
