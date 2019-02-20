<?php

namespace App\Http\Requests;

use App\Rules\RegisterEmailCodeSendableRule;
use Illuminate\Support\Facades\App;
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
                new RegisterEmailCodeSendableRule(),
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
        if (!App::isLocale('zh-CN')) {
            return [];
        }
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
            'name.unique' => trans('basic.users.Username_has_been_registered_as_user'),
            'email.unique' => trans('basic.users.Email_has_been_registered_as_user'),
        ];
    }
}
