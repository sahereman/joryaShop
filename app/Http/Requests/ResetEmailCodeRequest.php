<?php

namespace App\Http\Requests;

use App\Rules\ResetEmailCodeSendableRule;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class ResetEmailCodeRequest extends Request
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
                new ResetEmailCodeSendableRule(),
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
            'email.exists' => trans('basic.users.Email_has_not_been_registered_as_user'),
        ];
    }
}
