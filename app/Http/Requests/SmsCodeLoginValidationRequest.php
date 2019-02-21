<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\LoginSmsCodeValidRule;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class SmsCodeLoginValidationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'country_code' => 'bail|required|string|regex:/^\d+$/',
            'phone' => [
                'bail',
                'required',
                'string',
                'regex:/^\d+$/',
                function ($attribute, $value, $fail) {
                    if (!User::where([
                        'country_code' => $this->input('country_code'),
                        'phone' => $value,
                    ])->exists()
                    ) {
                        $fail(trans('basic.users.Phone_has_not_been_registered_as_user'));
                    }
                },
            ],
            'code' => [
                'bail',
                'required',
                'string',
                'regex:/^\d+$/',
                new LoginSmsCodeValidRule($this->input('country_code'), $this->input('phone')),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (!App::isLocale('zh-CN')) {
            return [];
        }
        return [
            'country_code' => '国家|地区码',
            'phone' => '手机号码',
            'code' => '短信验证码',
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        return [
            'country_code.regex' => trans('basic.users.Country_code_with_bad_format'),
            'phone.regex' => trans('basic.users.Phone_with_bad_format'),
            'code.regex' => trans('basic.users.Code_with_bad_format'),
        ];
    }
}
