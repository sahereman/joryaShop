<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;

class FeedbackRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'captcha' => ['bail','required','captcha'],
            'email' => ['bail', 'required','string','email','max:255'],
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
            'captcha' => '验证码',
            'email' => '邮箱地址',
        ];
    }
}
