<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class EasySmsSendRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.code' => 'required|string',
            'phone_number' => 'required|integer',
            'country_code' => 'required|integer',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'data' => '短信内容',
            'data.code' => '短信验证码',
            'phone_number' => '电话号码',
            'country_code' => '国家|地区码',
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
