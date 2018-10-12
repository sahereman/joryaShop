<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeLoginEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        return redirect()->back();
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        if (request()->has('username')) {
            if (Validator::make(request()->all(), [
                'username' => 'required|string|email',
            ])->passes()
            ) {
                return 'email';
            } else {
                return 'name';
            }
        }
        return 'email';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if($request->has('username')){
            return [
                $this->username() => $request->input('username'),
                'password' => $request->input('password'),
            ];
        }
        return $request->only('email', 'code');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        if ($request->has('username')) {
            $this->validate($request, [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
        } else {
            $this->validate($request, [
                'email' => 'required|string|email|exists:users',
                'code' => 'required|string',
            ]);
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request)
    {
        return redirect()->back(302);
    }

    public function sendEmailCode(Request $request)
    {
        if (Validator::make($request->only('email'), [
            'email' => 'required|string|email|exists:users'
        ], [
            'email.exists' => '该邮箱尚未注册用户',
        ])->fails()
        ) {
            return response()->json([
                'code' => 204,
                'message' => '该邮箱尚未注册用户',
            ]);
        }
        $email = $request->input('email');

        if (Cache::has('login_email_code_sent-' . $email)) {
            return response()->json([
                'code' => 201,
                'message' => '邮箱验证码已发送',
            ]);
        }

        if (Cache::has('login_email_code-' . $email)) {
            Cache::forget('login_email_code-' . $email);
        }

        event(new EmailCodeLoginEvent($email));

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $email = $request->input('email');
        $code = $request->input('code');
        $this->validateLogin($request);
        if (Cache::has('login_email_code-' . $email)) {
            if (Cache::get('login_email_code-' . $email) === $code) {
                Cache::forget('login_email_code-' . $email);
                $user = User::where(['email' => $email])->first();
                if ($request->filled('remember')) {
                    Auth::login($user, true);
                } else {
                    Auth::login($user);
                }
                return $this->sendLoginResponse($request);
            }
            return response()->json([
                'code' => 203,
                'message' => '邮箱验证码错误',
            ]);
        }
        return response()->json([
            'code' => 202,
            'message' => '邮箱验证码已过期',
        ]);
    }
}
