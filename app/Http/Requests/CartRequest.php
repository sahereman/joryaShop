<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Validation\Rule;

class CartRequest extends Request
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
            'sku_id' => [
                'required',
                'required_with:number',
                'integer',
                'exists:product_skus,id',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($value);
                    if ($sku->product->on_sale == false) {
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
                'required',
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
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
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
            'sku_id.exists' => '该商品不存在',
        ];
    }
}
