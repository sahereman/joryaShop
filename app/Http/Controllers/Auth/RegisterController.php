<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeRegisterEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmailCodeRequest;
use App\Http\Requests\RegisterEmailCodeValidationRequest;
use App\Http\Requests\SmsCodeRegisterRequest;
use App\Http\Requests\SmsCodeRegisterValidationRequest;
use App\Models\User;
use App\Rules\RegisterSmsCodeValidRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    public function redirectTo()
    {
        return redirect()->back();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /*return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'code' => 'required|string',
        ]);*/
        return Validator::make($data, [
            'name' => 'bail|required|string|max:255|unique:users',
            'password' => 'bail|required|string|min:6',
            'country_code' => 'bail|required|string|regex:/^\d+$/',
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\d+$/',
                function ($attribute, $value, $fail) {
                    if (isset($data['country_code'])) {
                        if (User::where([
                            'country_code' => $data['country_code'],
                            'phone' => $value,
                        ])->exists()
                        ) {
                            $fail('对不起，该手机号码已经注册过用户');
                        }
                    }
                }
            ],
            'code' => [
                'bail',
                'required',
                'string',
                'regex:/^\d+$/',
                new RegisterSmsCodeValidRule($data['country_code'], $data['phone']),
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'avatar' => asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg'),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/
        return User::create([
            'name' => $data['name'],
            'avatar' => asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg'),
            'country_code' => $data['country_code'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }

    // POST 通过邮箱验证码注册
    public function sendEmailCode(RegisterEmailCodeRequest $request)
    {
        $email = $request->input('email');

        if (Cache::has('register_email_code-' . $email)) {
            Cache::forget('register_email_code-' . $email);
        }

        event(new EmailCodeRegisterEvent($email));

        return response()->json([]);
    }

    // POST 通过短信验证码注册
    public function sendSmsCode(SmsCodeRegisterRequest $request)
    {
        $phone_number = $request->input('phone');
        $country_code = $request->input('country_code');

        if (Cache::has('register_sms_code-' . $country_code . '-' . $phone_number)) {
            Cache::forget('register_sms_code-' . $country_code . '-' . $phone_number);
        }

        $code = random_int(100000, 999999);
        $ttl = 10;
        Cache::set('register_sms_code-' . $country_code . '-' . $phone_number, $code, $ttl);
        // 60s内不允许重复发送邮箱验证码
        Cache::set('register_sms_code_sent-' . $country_code . '-' . $phone_number, true, 1);
        // Interruption For Test:
        // dd($code);

        $data['code'] = $code;
        $response = easy_sms_send($data, $phone_number, $country_code);

        if ($response['aliyun']['status'] == 'success') {
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'response' => $response,
            ]);
        }

        return response()->json([
            'code' => 400,
            'message' => 'Bad Request',
            'response' => $response,
        ], 400);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterEmailCodeRequest
     * @param  \App\Http\Requests\SmsCodeRegisterValidationRequest $request
     * @return \Illuminate\Http\Response
     */
    // public function register(RegisterEmailCodeRequest $request)
    public function register(SmsCodeRegisterValidationRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
