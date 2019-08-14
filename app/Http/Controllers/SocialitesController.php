<?php

namespace App\Http\Controllers;

use App\Clients\FacebookGuzzle6HttpClient;
use App\Exceptions\InvalidRequestException;
use App\Models\User;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphUser;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SocialitesController extends Controller
{
    protected $supportedSocialites = [
        'facebook'
    ];

    protected $fb;
    protected $accessToken;

    protected $avatarUrl;
    protected $avatar_width = 400;
    protected $avatar_height = 400;

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
        // if (session_id() == '') {
        if (session_status() != PHP_SESSION_ACTIVE) { // 2
            session_start();
        }

        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);

        /*$fb_csrf_state = Str::random(FacebookRedirectLoginHelper::CSRF_LENGTH);
        $_GET['state'] = $fb_csrf_state;
        $_SESSION['FBRLH_state'] = $fb_csrf_state;
        $_SESSION['fb_csrf_state'] = $fb_csrf_state;
        session()->put('fb_csrf_state', $fb_csrf_state);*/
        $helper = $fb->getRedirectLoginHelper();
        // $helper->getPersistentDataHandler()->set('state', $fb_csrf_state);

        // $permissions = ['email']; // Optional permissions
        // $permissions = ['default', 'email']; // Optional permissions
        $permissions = ['email', 'public_profile']; // Optional permissions
        // $loginUrl = $helper->getLoginUrl($config['redirect'] . "?state={$fb_csrf_state}", $permissions);
        $loginUrl = $helper->getLoginUrl($config['redirect'], $permissions);

        // preg_match('/.+state\=(.+)\&.+/U', $loginUrl, $matches); // preg match in un-greedy mode
        preg_match('/.+state\=(.+?)\&.+/', $loginUrl, $matches); // preg match in un-greedy mode
        $fb_csrf_state = $matches[1];
        $helper->getPersistentDataHandler()->set('state', $fb_csrf_state);
        $_SESSION['FBRLH_state'] = $fb_csrf_state;

        // echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
        die;
        // return redirect()->to(htmlspecialchars($loginUrl));
    }

    // POST: Socialite Callback Url
    public function callback(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);

        // Facebook Login Callback
        // if (session_id() == '') {
        if (session_status() != PHP_SESSION_ACTIVE) { // 2
            session_start();
        }

        $config = $this->getSocialiteConfig($socialite);
        $fb = new Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => $config['graph_version'],
            'http_client_handler' => new FacebookGuzzle6HttpClient()
        ]);
        $this->fb = $fb;

        /*if ($_SESSION['FBRLH_state']) {
            $fb_csrf_state = $_SESSION['FBRLH_state'];
        } else if ($_SESSION['fb_csrf_state']) {
            $fb_csrf_state = $_SESSION['fb_csrf_state'];
        } else if ($request->session()->pull('fb_csrf_state')) {
            $fb_csrf_state = $request->session()->pull('fb_csrf_state');
        } else {
            $fb_csrf_state = $request->input('state');
        }*/

        if ($_SESSION['FBRLH_state']) {
            $fb_csrf_state = $_SESSION['FBRLH_state'];
        } else {
            $fb_csrf_state = $request->input('state');
        }
        $_GET['state'] = $fb_csrf_state;

        $helper = $fb->getRedirectLoginHelper();
        $helper->getPersistentDataHandler()->set('state', $fb_csrf_state);

        try {
            $accessToken = $helper->getAccessToken();
            $this->accessToken = $accessToken;
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
            // $response = $fb->get('/me?fields=id,name,first_name,middle_name,last_name,email,gender,picture', $accessToken);
            // $response = $fb->get('/me', $accessToken);
            // $response = $fb->get('/me?fields=id,name,email,picture&redirect=false', $accessToken);
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
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

        // if (session_id() == '') {
        if (session_status() != PHP_SESSION_ACTIVE) { // 2
            session_start();
        }

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

        // if (session_id() == '') {
        if (session_status() != PHP_SESSION_ACTIVE) { // 2
            session_start();
        }

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

        if ($_SESSION['fb_access_token']) {
            $accessToken = $_SESSION['fb_access_token'];
        } else if ($request->session()->get("{$socialite}_access_token")) {
            $accessToken = $request->session()->get("{$socialite}_access_token");
        } else {
            $accessToken = $this->getAccessToken($request, $socialite);
        }

        try {
            // Returns a `Facebook\FacebookResponse` object
            // $response = $fb->get('/me?fields=id,name', '{access-token}');
            // $response = $fb->get('/me?fields=id,name,first_name,middle_name,last_name,email,gender,picture', $accessToken);
            // $response = $fb->get('/me', $accessToken);
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
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

        if (in_array($socialite, $this->supportedSocialites) && $socialite == 'facebook') {
            $user = User::where([
                'facebook' => $user_profile->getId()
            ])->first();

            if (!$user) {
                $avatar_url = $this->getAvatarUrl($user_profile);
                $user = User::create([
                    'name' => $user_profile->getName(),
                    'password' => bcrypt(Str::random(6)),
                    // 'avatar' => $user_profile->getPicture()->getUrl(), // https://graph.facebook.com/userid_here/picture
                    'avatar' => $avatar_url,
                    'email' => $user_profile->getEmail(),
                    // 'real_name' => $user_profile->getFirstName() . $user_profile->getMiddleName() . $user_profile->getLastName(),
                    // 'gender' => $user_profile->getGender(),
                    'facebook' => $user_profile->getId()
                ]);
            } else if (!$user->avatar) {
                $user->avatar = $this->getAvatarUrl($user_profile);
                $user->save();
            }
            return $user;
        } else {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
    }

    protected function getAvatarUrl(GraphUser $user_profile)
    {
        // $date = Carbon::now();
        $prefix_path = Storage::disk('public')->getAdapter()->getPathPrefix();
        $child_path = 'avatar'; /*存储文件格式为 avatar 文件夹内*/
        // $path = $prefix_path . $child_path;

        $user_id = $user_profile->getId();
        $user_name = $user_profile->getName();

        $i = 0;
        $file_name = str_replace(' ', '-', strtolower($user_name)) . '.jpg';
        $name = pathinfo($file_name, PATHINFO_FILENAME);
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        while (\Storage::disk('public')->exists($child_path . '/' . $file_name)) {
            $file_name = $name . '-' . $i . '.' . $extension;
            $i++;
        }
        $file_path = $prefix_path . $child_path . '/' . $file_name;

        try {
            // Returns a `FacebookFacebookResponse` object
            // $response = $fb->get(
            // '/{user-id}/picture',
            // '{access-token}'
            // );
            // curl -i -X GET \
            // "https://graph.facebook.com/v4.0/{user-id}/picture"
            // $response = $fb->get("/{$user_id}/picture?height=80&redirect=0&type=normal&width=80", $accessToken);
            $response = $this->fb->get("/{$user_id}/picture?redirect=0&type=large", $this->accessToken);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();
        $url = $graphNode->getField('url');
        $avatar_path = CurlHelper::downloadFile($url, array(
            'followLocation' => true,
            'maxRedirs' => 5,
        ), $file_path);
        // dd($avatar_path);

        // Image::make($prefix_path . $path)->orientate()->resize($this->avatar_width, $this->avatar_height)->save();
        // $image = Image::make($prefix_path . $path)->orientate();
        $image = Image::make($avatar_path)->orientate();
        $width = $image->width();
        $height = $image->height();
        $image->fit(min($width, $height))->resize($this->avatar_width, $this->avatar_height, function ($constraint) {
            // $constraint->aspectRatio();
            $constraint->upsize();
        })->save();

        $this->avatarUrl = Storage::disk('public')->url($child_path . '/' . $file_name);
        // dd($this->avatarUrl);

        return $this->avatarUrl;
    }
}

class CurlHelper
{
    /**
     * Downloads a file from a url and returns the temporary file path.
     * @param string $url
     * @return string The file path
     */
    public static function downloadFile($url, $options = array(), $file_path)
    {
        if (!is_array($options)) {
            $options = array();
        }
        $options = array_merge(array(
            'connectionTimeout' => 5, // seconds
            'timeout' => 10, // seconds
            'sslVerifyPeer' => false,
            'followLocation' => false, // if true, limit recursive redirection by
            'maxRedirs' => 1, // setting value for "maxRedirs"
        ), $options);

        // create a temporary file (we are assuming that we can write to the system's temporary directory)
        // $tempFileName = tempnam(sys_get_temp_dir(), '') . '.jpg';
        // $fh = fopen($tempFileName, 'w');
        $fh = fopen($file_path, 'w');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FILE, $fh);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $options['connectionTimeout']);
        curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $options['sslVerifyPeer']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $options['followLocation']);
        curl_setopt($curl, CURLOPT_MAXREDIRS, $options['maxRedirs']);
        curl_exec($curl);

        curl_close($curl);
        fclose($fh);

        return $file_path;
    }
}
