<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'avatar' => ['sometimes', 'image'],
            'password' => 'sometimes|required|string|min:6|confirmed',
            'email' => [
                'sometimes', 'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->route()->user->id),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'avatar' => '头像',
            'email' => '邮箱',
            'password' => '密码',
        ];
    }
}
