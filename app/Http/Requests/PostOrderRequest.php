<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PostOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'skus' => 'sometimes|json|required_without:carts',
            'carts' => 'sometimes|json|required_without:skus',
        ];
    }

    public function attributes()
    {
        return [
            'skus' => '来自SKU的订单信息',
            'carts' => '来自购物车的订单信息',
        ];
    }
}
