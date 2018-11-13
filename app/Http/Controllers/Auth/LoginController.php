<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeLoginEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginEmailCodeRequest;
use App\Http\Requests\LoginEmailCodeValidationRequest;
use App\Http\Requests\SmsCodeLoginRequest;
use App\Http\Requests\SmsCodeLoginValidationRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    // The maximum number of attempts to allow.
    protected $maxAttempts = 3;

    // The number of minutes to throttle for.
    protected $decayMinutes = 1;

    /**
     * Where to redirect users after login.
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {

        return URL::previous();
        // return url()->previous();
        // return Redirect::back()->getTargetUrl();
        // return redirect()->back()->getTargetUrl();
        // return $request->headers->get('referer');
    }


    /**
     * Get the login username to be used by the controller.
     * @return string
     */
    public function username()
    {
        if (request()->has('username'))
        {
            if (Validator::make(request()->all(), [
                'username' => 'required|string|regex:/^\d+$/',
            ])->passes()
            )
            {
                return 'phone';
            } elseif (Validator::make(request()->all(), [
                'username' => 'required|string|email',
            ])->passes()
            )
            {
                return 'email';
            }
        }
        return 'name';
    }

    /**
     * Get the needed authorization credentials from the request.
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            $this->username() => $request->input('username'),
            'password' => $request->input('password'),
        ];
    }

    /**
     * Validate the user login request.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'bail|required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * The user has been authenticated.
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, User $user)
    {
        return redirect()->back(302);
    }

    // POST 发送邮箱验证码 [for Ajax request]
    public function sendEmailCode(LoginEmailCodeRequest $request)
    {
        $email = $request->input('email');

        if (Cache::has('login_email_code-' . $email))
        {
            Cache::forget('login_email_code-' . $email);
        }

        event(new EmailCodeLoginEvent($email));

        return response()->json([]);
    }

    // POST 验证邮箱验证码 [for Ajax request]
    public function verifyEmailCode(LoginEmailCodeValidationRequest $request)
    {
        $email = $request->input('email');
        // $code = $request->input('code');

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (Cache::has('login_email_code-' . $email))
        {
            Cache::forget('login_email_code-' . $email);
        }

        $user = User::where(['email' => $email])->first();
        if ($user)
        {
            if ($request->filled('remember'))
            {
                Auth::login($user, true);
            } else
            {
                Auth::login($user);
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // POST 发送短信验证码 [for Ajax request]

    /**
     * Successful Response Demo:
     * {
     * "code":200,
     * "message":"success",
     * "response":{
     * "aliyun":{
     * "gateway":"aliyun",
     * "status":"success",
     * "result":{
     * "Message":"OK",
     * "RequestId":"56A053E7-5934-4FDF-A1D2-E60C6AC11706",
     * "BizId":"924907740968089747^0",
     * "Code":"OK",
     * },
     * },
     * },
     * }
     */
    public function sendSmsCode(SmsCodeLoginRequest $request)
    {
        $phone_number = $request->input('phone');
        $country_code = $request->input('country_code');

        if (Cache::has('login_sms_code-' . $country_code . '-' . $phone_number))
        {
            Cache::forget('login_sms_code-' . $country_code . '-' . $phone_number);
        }

        $code = random_int(100000, 999999);
        $data['code'] = $code;
        $response = easy_sms_send($data, $phone_number, $country_code);

        if ($response['aliyun']['status'] == 'success')
        {

            $ttl = 10;
            Cache::set('login_sms_code-' . $country_code . '-' . $phone_number, $code, $ttl);
            // 60s内不允许重复发送邮箱验证码
            Cache::set('login_sms_code_sent-' . $country_code . '-' . $phone_number, true, 1);

            return response()->json([
                'code' => 200,
                'message' => 'success',
                'response' => $response,
            ]);
        }

        return response()->json($response, 500);
    }

    // POST 验证短信验证码 [for Ajax request]
    public function verifySmsCode(SmsCodeLoginValidationRequest $request)
    {
        $phone_number = $request->input('phone');
        $country_code = $request->input('country_code');

        // Comment For Test:
        if (Cache::has('login_sms_code-' . $country_code . '-' . $phone_number))
        {
            Cache::forget('login_sms_code-' . $country_code . '-' . $phone_number);
        }

        $user = User::where([
            'country_code' => $country_code,
            'phone' => $phone_number,
        ])->first();
        if ($user)
        {
            if ($request->filled('remember'))
            {
                Auth::login($user, true);
            } else
            {
                Auth::login($user);
            }

            // return $this->sendLoginResponse($request);

            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'return_url' => URL::previous(),
                    // 'return_url' => url()->previous(),
                    // 'return_url' => Redirect::back()->getTargetUrl(),
                    // 'return_url' => redirect()->back()->getTargetUrl(),
                    // 'return_url' => $request->headers->get('referer'),
                ],
            ]);
        }

        return response()->json([
            'code' => 422,
            'message' => '验证码不正确',
        ]);
    }

    /**
     * Handle a login request to the application.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $users = User::where([
            $this->username() => $request->input('username'),
        ])->get();
        foreach ($users as $user)
        {
            $userData = $user->makeVisible('password')->toArray();
            if (Hash::check($request->input('password'), $userData['password']))
            {
                if ($request->filled('remember'))
                {
                    Auth::login($user, true);
                } else
                {
                    Auth::login($user);
                }
                // return $this->sendLoginResponse($request);

                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'return_url' => URL::previous(),
                        // 'return_url' => url()->previous(),
                        // 'return_url' => Redirect::back()->getTargetUrl(),
                        // 'return_url' => redirect()->back()->getTargetUrl(),
                        // 'return_url' => $request->headers->get('referer'),
                    ],
                ]);

                break;
            }
        }

        return response()->json([
            'code' => 422,
            'message' => '用户名或密码不正确',
        ]);

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        // $this->incrementLoginAttempts($request);

        // return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return redirect('/');
    }

}
