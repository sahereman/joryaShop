<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Support\Facades\App;
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
                'bail',
                'required',
                'required_with:number',
                'integer',
                'exists:product_skus,id',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($value);
                    if ($sku->product->on_sale == 0) {
                        if (App::isLocale('en')) {
                            $fail('This product sku is off sale already.');
                        } else {
                            $fail('该商品已下架');
                        }
                    }
                    if ($sku->stock == 0) {
                        if (App::isLocale('en')) {
                            $fail('This product sku is out of stock already.');
                        } else {
                            $fail('该商品已售罄');
                        }
                    }
                    /*if ($sku->stock < $this->input('number')) {
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }*/
                },
            ],
            'number' => [
                'bail',
                'required',
                'required_with:sku_id',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($this->input('sku_id'));
                    if ($sku->stock < $value) {
                        if (App::isLocale('en')) {
                            $fail("The stock of this product sku is not sufficient. Plz re-enter another appropriate number.");
                        } else {
                            $fail('该商品库存不足，请重新调整商品购买数量');
                        }
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
        if (App::isLocale('en')) {
            return [];
        }
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
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'sku_id.exists' => '该商品不存在',
        ];
    }
}
