<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\RegisterSmsCodeValidRule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\URL;
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
     * @var string
     */
    protected $redirectTo = '/mobile/users';

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /*public function redirectTo()
    {
        return URL::previous();
    }*/

    public function showRegistrationForm()
    {
        return view('mobile.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'bail|required|string|max:255|unique:users',
            'password' => 'bail|required|string|min:6',
            'email' => 'bail|required|string|email|unique:users',
            'country_code' => 'bail|required|string|regex:/^\d+$/',
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\d+$/',
                function ($attribute, $value, $fail) use ($data) {
                    if (isset($data['country_code'])) {
                        if (User::where([
                            'country_code' => $data['country_code'],
                            'phone' => $value,
                        ])->exists()
                        ) {
                            $fail(trans('basic.users.Phone_has_been_registered_as_user'));
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
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'country_code' => $data['country_code'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
