<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class UserAddressRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => [
                'required',
                'string',
                Rule::unique('user_addresses')->where('user_id', $this->user()->id),
            ],
        ];
    }

    public function attributes()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'name' => '收货人',
            'phone' => '手机号码',
            'address' => '详细地址',
        ];
    }
}
