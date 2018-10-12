<?php

namespace App\Http\Requests;

use App\Rules\RegisterEmailCodeSentableRule;
use Illuminate\Validation\Rule;

class RegisterEmailCodeRequest extends Request
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
                new RegisterEmailCodeSentableRule(),
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
        return [
            'name' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
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
