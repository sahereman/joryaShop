<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAddressRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('user_addresses.store') || $this->routeIs('user_addresses.store_for_ajax')) {
            return [
                'name' => 'required|string',
                'phone' => 'required|string',
                'country' => 'string|nullable',
                'province' => 'string|nullable',
                'city' => 'string|nullable',
                'address' => [
                    'required',
                    'string',
                    // Rule::unique('user_addresses')
                    //->where('user_id', $this->user()->id)
                    //->where('name', $this->input('name')),
                    //->where('phone', $this->input('phone')),
                ],
                'zip' => 'string|nullable',
            ];
        } elseif ($this->routeIs('user_addresses.update')) {
            return [
                'name' => 'required|string',
                'phone' => 'required|string',
                'country' => 'string|nullable',
                'province' => 'string|nullable',
                'city' => 'string|nullable',
                'address' => [
                    'required',
                    'string',
                    // Rule::unique('user_addresses')
                    //->ignore($this->route('address')->id)
                    //->where('user_id', $this->user()->id)
                    //->where('name', $this->input('name')),
                    //->where('phone', $this->input('phone')),
                ],
                'zip' => 'string|nullable',
            ];
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (!App::isLocale('zh-CN')) {
            return [];
        }
        return [
            'name' => '收货人',
            'phone' => '手机号码',
            'country' => '国家',
            'province' => '省',
            'city' => '市',
            'address' => '详细地址',
            'zip' => '邮政编码',
        ];
    }
}
