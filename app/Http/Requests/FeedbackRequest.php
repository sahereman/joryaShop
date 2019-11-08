<?php

namespace App\Http\Requests;

use App\Models\Feedback;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

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
            // 'captcha' => ['bail', 'required', 'captcha'],
            'name' => 'string|nullable|max:255',
            'phone' => 'string|nullable|max:255',
            'email' => 'bail|required|string|email|max:255',
            /*'email' => [
                'bail', 'required', 'string', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->input('type') === 'subscription') {
                        if (Feedback::where([
                            'email' => $value,
                            'type' => 'subscription',
                        ])->exists()
                        ) {
                            if (!App::isLocale('zh-CN')) {
                                $fail('You have subscribed to our news with this email.');
                            } else {
                                $fail('您已订阅我们的消息');
                            }
                        }
                    }
                }
            ],*/
            'content' => 'string|nullable|max:255',
            'type' => ['bail', 'required', 'string', Rule::in(['subscription', 'consultancy'])],
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
            // 'captcha' => '验证码',
            'email' => '邮箱地址',
            'type' => '留言类型',
        ];
    }
}
