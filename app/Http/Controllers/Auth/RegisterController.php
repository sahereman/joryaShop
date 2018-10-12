<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailCodeRegisterEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmailCodeRequest;
use App\Http\Requests\RegisterEmailCodeValidationRequest;
use App\Models\User;
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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'code' => 'required|string',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'avatar' => asset('/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg'),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function sendEmailCode(RegisterEmailCodeRequest $request)
    {
        $email = $request->input('email');

        if (Cache::has('reset_email_code-' . $email)) {
            Cache::forget('reset_email_code-' . $email);
        }

        event(new EmailCodeRegisterEvent($email));

        return response()->json([]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterEmailCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterEmailCodeRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
