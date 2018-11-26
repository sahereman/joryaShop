<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeResetEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetEmailCodeRequest;
use App\Http\Requests\ResetEmailCodeValidationRequest;
use App\Http\Requests\SmsCodeResetRequest;
use App\Http\Requests\SmsCodeResetValidationRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    // GET 输入邮箱验证码页面
    public function inputEmailCode(Request $request)
    {
        return view('auth.passwords.input_email_code');
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

    // POST 验证邮箱验证码
    public function verifyEmailCode(ResetEmailCodeValidationRequest $request)
    {
        /*$email = $request->input('email');
        $code = $request->input('code');

        if (Cache::has('reset_email_code-' . $email)) {
            Cache::forget('reset_email_code-' . $email);
        }*/

        return redirect()->route('reset.override')->withInput(
            $request->only('email', 'code')
        );
    }

    // POST 校验国家|地区码+手机号码，并跳转下一步
    public function sendSmsCode(SmsCodeResetRequest $request)
    {
        return redirect()->route('reset.input_sms_code')->withInput(
            $request->only('country_code', 'phone')
        );
    }

    // GET 输入短信验证码页面
    public function inputSmsCode(Request $request)
    {
        return view('auth.passwords.input_sms_code');
    }

    // POST 发送短信验证码 [for Ajax request]
    public function resendSmsCode(SmsCodeResetRequest $request)
    {
        $country_code = $request->input('country_code');
        $phone_number = $request->input('phone');

        if (Cache::has('reset_sms_code-' . $country_code . '-' . $phone_number)) {
            Cache::forget('reset_sms_code-' . $country_code . '-' . $phone_number);
        }

        $code = random_int(100000, 999999);
        $data['code'] = $code;
        $response = easy_sms_send($data, $phone_number, $country_code);

        if ($response['aliyun']['status'] == 'success') {

            $ttl = 10;
            Cache::set('reset_sms_code-' . $country_code . '-' . $phone_number, $code, $ttl);
            // 60s内不允许重复发送邮箱验证码
            Cache::set('reset_sms_code_sent-' . $country_code . '-' . $phone_number, true, 1);

            $request->session()->put('sms_code_sent', true);

            return response()->json([
                'code' => 200,
                'message' => 'success',
                'response' => $response,
            ]);
        }

        return response()->json($response, 500);
    }

    // POST 验证短信验证码
    public function verifySmsCode(SmsCodeResetValidationRequest $request)
    {
        /*$country_code = $request->input('country_code');
        $phone_number = $request->input('phone');
        $code = $request->input('code');

        if (Cache::has('reset_sms_code-' . $country_code . '-' . $phone_number)) {
            Cache::forget('reset_sms_code-' . $country_code . '-' . $phone_number);
        }*/

        $request->session()->put('sms_code_verified', true);

        return redirect()->route('reset.override')->withInput(
            $request->only('country_code', 'phone', 'code')
        );
    }

    // GET 重复输入新密码页面
    public function override(Request $request)
    {
        if ($request->session()->has('sms_code_sent') && $request->session()->has('sms_code_verified')) {
            return view('auth.passwords.reset');
        }

        return redirect()->route('password.request');
    }

    // POST 重置密码为新密码
    public function overridePassword(SmsCodeResetValidationRequest $request)
    {
        if ($request->session()->has('sms_code_sent') && $request->session()->has('sms_code_verified')) {
            // $email = $request->input('email');
            // $code = $request->input('code');
            $country_code = $request->input('country_code');
            $phone_number = $request->input('phone');
            $password = $request->input('password');

            if (Cache::has('reset_sms_code-' . $country_code . '-' . $phone_number)) {
                Cache::forget('reset_sms_code-' . $country_code . '-' . $phone_number);
            }

            $user = User::where([
                'country_code' => $country_code,
                'phone' => $phone_number,
            ])->first();
            $user->password = bcrypt($password);
            $result = $user->save();

            $request->session()->forget('sms_code_verified');
            $request->session()->forget('sms_code_sent');

            if ($result) {
                return redirect()->route('reset.success');
            } else {
                return redirect()->back()->withInput(
                    $request->only('country_code', 'phone')
                );
            }
        }

        return redirect()->route('password.request');
    }

    // GET 通过邮箱验证码重置密码成功页面
    public function success(Request $request)
    {
        return view('auth.passwords.reset_success');
    }
}
