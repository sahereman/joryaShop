<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeResetEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetEmailCodeRequest;
use App\Http\Requests\ResetEmailCodeValidationRequest;
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

    // POST 发送邮箱验证码
    public function sendEmailCode(ResetEmailCodeRequest $request)
    {
        /*$email = $request->input('email');

        if (Cache::has('reset_email_code-' . $email)) {
            Cache::forget('reset_email_code-' . $email);
        }

        event(new EmailCodeResetEvent($email));*/

        return redirect()->route('reset.input_email_code')->withInput(
            $request->only('email')
        );
    }

    // POST 再次发送邮箱验证码 [for Ajax request]
    public function resendEmailCode(ResetEmailCodeRequest $request)
    {
        $email = $request->input('email');

        if (Cache::has('reset_email_code-' . $email)) {
            Cache::forget('reset_email_code-' . $email);
        }

        event(new EmailCodeResetEvent($email));

        /*return redirect()->route('reset.input_email_code')->withInput(
            $request->only('email')
        );*/
    }

    // GET 输入邮箱验证码页面
    public function inputEmailCode(Request $request)
    {
        return view('auth.passwords.input_email_code');
    }

    // POST 验证邮箱验证码
    public function verifyEmailCode(ResetEmailCodeValidationRequest $request)
    {
        $email = $request->input('email');
        $code = $request->input('code');

        // Cache::forget('reset_email_code-' . $email);
        return redirect()->route('reset.override')->withInput([
            'email' => $email,
            'code' => $code,
        ]);
    }

    // GET 重复输入新密码页面
    public function override(Request $request)
    {
        return view('auth.passwords.reset');
    }

    // POST 重置密码为新密码
    public function overridePassword(ResetEmailCodeValidationRequest $request)
    {
        $email = $request->input('email');
        // $code = $request->input('code');
        $password = $request->input('password');

        Cache::forget('reset_email_code-' . $email);

        $user = User::where(['email' => $email])->first();
        $user->password = bcrypt($password);
        $result = $user->save();
        if ($result) {
            return redirect()->route('reset.success');
        } else {
            return redirect()->back();
        }
    }

    // GET 通过邮箱验证码重置密码成功页面
    public function success(Request $request)
    {
        return view('auth.passwords.reset_success');
    }
}
