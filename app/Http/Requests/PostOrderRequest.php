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
            'sku_id' => [
                'required_without:cart_ids',
                'required_with:number',
                'integer',
                'exists:product_skus,id',
                function($attribute, $value, $fail){
                    $sku = ProductSku::find($value);
                    if($sku->product->on_sale == false){
                        $fail('该商品已下架');
                    }
                    if($sku->stock == 0){
                        $fail('该商品已售罄');
                    }
                    if($sku->stock < $this->input('number')){
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }
                },
            ],
            'number' => [
                'required_without:cart_ids',
                'required_with:sku_id',
                'integer',
                'min:1',
            ],
            'cart_ids' => [
                'required_without_all:sku_id,number',
                'string',
                'regex:/^\d(\,\d)*$/'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
            'cart_ids' => '购物车IDs',
        ];
    }

    public function messages()
    {
        return [
            'sku_id.exists' => '该商品不存在',
            'cart_ids.regex' => '购物车IDs格式不正确',
        ];
    }
}
