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
            'real_name' => 'sometimes|string',
            'gender' => 'sometimes|string',
            'qq' => 'sometimes|string',
            'wechat' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'facebook' => 'sometimes|string',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'avatar' => '头像',
            'email' => '邮箱',
            'password' => '密码',
            'real_name' => '真实姓名',
            'gender' => '性别:male|female',
            'qq' => 'QQ',
            'wechat' => '微信',
            'phone' => '手机',
            'facebook' => 'Facebook',
        ];
    }
}
