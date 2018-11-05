<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
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
            'currency' => 'required|string|exists:exchange_rates',
            'sku_id' => [
                'required_without:cart_ids',
                'required_with:number',
                'integer',
                'exists:product_skus,id',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($value);
                    if ($sku->product->on_sale == 0) {
                        $fail('该商品已下架');
                    }
                    if ($sku->stock == 0) {
                        $fail('该商品已售罄');
                    }
                    /*if ($sku->stock < $this->input('number')) {
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }*/
                },
            ],
            'number' => [
                'required_without:cart_ids',
                'required_with:sku_id',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($this->input('sku_id'));
                    if ($sku->stock < $value) {
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }
                },
            ],
            'cart_ids' => [
                'required_without_all:sku_id,number',
                'string',
                'regex:/^\d(\,\d)*$/'
            ],
            'name' => 'required|string',
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'remark' => 'sometimes|nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'currency' => '币种',
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
            'cart_ids' => '购物车IDs',
            'name' => '收货人',
            'phone' => '手机号码',
            'address' => '详细地址',
            'remark' => '订单备注',
        ];
    }

    public function messages()
    {
        return [
            'currency.exists' => '该币种支付暂不支持',
            'sku_id.exists' => '该商品不存在',
            'cart_ids.regex' => '购物车IDs格式不正确',
        ];
    }
}
