<?php

namespace App\Http\Requests;

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
            'address' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('user_addresses')->where('user_id', $this->user()->id),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'address' => '地址',
        ];
    }
}
