<?php

namespace App\Http\Requests;

use App\Rules\LoginEmailCodeValidRule;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class LoginEmailCodeValidationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'exists:users',
            ],
            'code' => [
                'required',
                'string',
                new LoginEmailCodeValidRule($this->has('email') ? $this->input('email') : ''),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'email' => '邮箱',
            'code' => '邮箱验证码',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'email.exists' => '该邮箱尚未注册用户',
        ];
    }
}
