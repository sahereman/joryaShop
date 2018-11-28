<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;

class EmailVerificationCodeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('email.send')) {
            return [
                'email' => 'bail|required|string|email',
                // When resending email verification code, key should be present.
                'key' => 'bail|sometimes|required|string',
            ];
        } elseif ($this->routeIs('email.verify')) {
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
            'email' => '邮箱',
            'key' => '密钥',
            'code' => '验证码',
        ];
    }
}
