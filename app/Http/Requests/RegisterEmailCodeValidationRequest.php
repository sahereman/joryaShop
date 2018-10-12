<?php

namespace App\Http\Requests;

use App\Rules\RegisterEmailCodeValidRule;
use Illuminate\Validation\Rule;

class RegisterEmailCodeValidationRequest extends Request
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
            'name' => 'bail|required|string|max:255|unique:users',
            'password' => 'bail|required|string|min:6',
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'unique:users',
                new RegisterEmailCodeValidRule($this->has('code') ? $this->input('code') : ''),
            ],
            'code' => 'required|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '用户名',
            'password' => '密码',
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
        return [
            'name.unique' => '该用户名已注册用户',
            'email.unique' => '该邮箱已注册用户',
        ];
    }
}
