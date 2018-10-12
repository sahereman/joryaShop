<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeResetEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    public function sendEmailCode(Request $request)
    {
        $validator = Validator::make($request->only('email'), [
            'email' => 'required|string|email|exists:users',
        ], [
            'email.exists' => '该邮箱尚未注册用户',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $email = $request->input('email');

        if (Cache::has('reset_email_code_sent-' . $email)) {
            return response()->json([
                'code' => 201,
                'message' => '邮箱验证码已发送',
            ]);
        }

        if (Cache::has('reset_email_code-' . $email)) {
            Cache::forget('reset_email_code-' . $email);
        }

        event(new EmailCodeResetEvent($email));

        return redirect()->route('reset.input_email_code')->withInput(
            $request->only('email')
        );
    }

    public function inputEmailCode(Request $request)
    {
        return view('auth.passwords.input_email_code');
    }

    public function verifyEmailCode(Request $request)
    {
        $email = $request->input('email');
        $code = $request->input('code');
        if (Cache::has('reset_email_code-' . $email)) {
            if (Cache::get('reset_email_code-' . $email) === $code) {
                // Cache::forget('reset_email_code-' . $email);
                return redirect()->route('reset.override')->withInput([
                    'email' => $email,
                    'code' => $code,
                ]);
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

    public function override(Request $request)
    {
        return view('auth.passwords.reset');
    }

    public function overridePassword(Request $request)
    {
        $code = $request->input('code');
        $email = $request->input('email');
        $password = $request->input('password');
        if (Cache::has('reset_email_code-' . $email)) {
            if (Cache::get('reset_email_code-' . $email) === $code) {
                Cache::forget('reset_email_code-' . $email);

                $this->validate($request, [
                    'email' => 'required|string|email|exists:users',
                    'password' => 'required|string|confirmed',
                ], [
                    'email.exists' => '该邮箱尚未注册用户',
                ]);

                $user = User::where(['email' => $email])->first();
                $user->password = bcrypt($password);
                $result = $user->save();
                if ($result) {
                    return redirect()->route('reset.success');
                } else {
                    return redirect()->route('root');
                }
            }
            return response()->json([
                'code' => 203,
                'message' => '邮箱验证码错误',
            ]);
        } else {
            return response()->json([
                'code' => 202,
                'message' => '邮箱验证码已过期',
            ]);
        }
    }

    public function success(Request $request)
    {
        return view('auth.passwords.reset_success');
    }
}
