<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class OrderListRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    'all', // 全部订单
                    'paying', // 待付款订单
                    'receiving', // 待收货订单
                    'uncommented', // 待评价订单
                    'refunding', // 售后订单
                    'completed', // 已完成订单
                ]),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'status' => '订单状态',
        ];
    }
}
