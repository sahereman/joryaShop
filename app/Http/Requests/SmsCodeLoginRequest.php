<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\LoginSmsCodeSendableRule;
use Illuminate\Validation\Rule;

class SmsLoginRequest extends Request
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
                        $fail('对不起，该手机号码尚未注册用户');
                    }
                },
                new LoginSmsCodeSendableRule($this->input('country_code')),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        return [
            'country_code' => '国家|地区码',
            'phone' => '手机号码',
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        return [
            'country_code.regex' => '国家|地区码 格式不正确（仅支持数字组合）',
            'phone.regex' => '手机号码 格式不正确（仅支持数字组合）',
        ];
    }
}
