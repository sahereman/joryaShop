<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;

class SmsVerificationCodeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('sms.send')) {
            return [
                'country_code' => 'bail|required|string',
                'phone' => 'bail|required|string',
                // When resending sms verification code, key should be present.
                // 'key' => 'bail|sometimes|required|string',
            ];
        } elseif ($this->routeIs('sms.verify')) {
            return [
                'key' => 'bail|required|string',
                'code' => 'bail|required|string',
            ];
        } else {
            return [
                //
            ];
        }
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'country_code' => '国家|地区码',
            'phone' => '手机号码',
            'key' => '密钥',
            'code' => '验证码',
        ];
    }
}
