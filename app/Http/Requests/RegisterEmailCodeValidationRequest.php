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
            'email' => [
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
            'email.unique' => '该邮箱已注册用户',
        ];
    }
}
