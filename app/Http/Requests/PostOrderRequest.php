<?php

namespace App\Http\Requests;

use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('orders.store')) {
            return [
                'currency' => [
                    'bail',
                    'sometimes',
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value != 'CNY' && ExchangeRate::where('currency', $value)->doesntExist()) {
                            $fail(trans('basic.orders.Currency_not_supported'));
                        }
                    },
                ],
                'sku_id' => [
                    'bail',
                    'required_without:cart_ids',
                    'required_with:number',
                    'integer',
                    'exists:product_skus,id',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($value);
                        if ($sku->product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        if ($sku->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }
                        /*if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                    },
                ],
                'number' => [
                    'bail',
                    'required_without:cart_ids',
                    'required_with:sku_id',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($this->input('sku_id'));
                        if ($sku->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
                'cart_ids' => [
                    'bail',
                    'required_without_all:sku_id,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/'
                ],
                'address_id' => [
                    'bail',
                    'required',
                    'integer',
                    // 'exists:user_addresses,id',
                    function ($attribute, $value, $fail) {
                        if (!UserAddress::where(['id' => $value, 'user_id' => $this->user()->id])->exists()) {
                            $fail('该用户地址不存在');
                        }
                    },
                ],
                'remark' => 'bail|sometimes|nullable|string',
            ];
        } elseif ($this->routeIs('orders.store_by_sku_parameters')) {
            return [
                'currency' => [
                    'bail',
                    'sometimes',
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value != 'CNY' && ExchangeRate::where('currency', $value)->doesntExist()) {
                            $fail(trans('basic.orders.Currency_not_supported'));
                        }
                    },
                ],
                'base_size' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'hair_colour' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'hair_density' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'product_id' => [
                    'bail',
                    'required',
                    'integer',
                    'exists:products,id',
                    function ($attribute, $value, $fail) {
                        $product = Product::find($value);
                        if ($product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        if ($product->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }
                        /*if ($product->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                        $base_size = $this->input('base_size');
                        $hair_colour = $this->input('hair_colour');
                        $hair_density = $this->input('hair_density');
                        if ($product->is_base_size_optional && !$base_size) {
                            $fail(trans('basic.orders.Plz_select_a_base_size'));
                        }
                        if ($product->is_hair_colour_optional && !$hair_colour) {
                            $fail(trans('basic.orders.Plz_select_a_hair_colour'));
                        }
                        if ($product->is_hair_density_optional && !$hair_density) {
                            $fail(trans('basic.orders.Plz_select_a_hair_density'));
                        }
                        $skus = $product->skus;
                        if (App::isLocale('en')) {
                            $skus = $product->is_base_size_optional ? $skus->where('base_size_en', $base_size) : $skus;
                            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_en', $hair_colour) : $skus;
                            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_en', $hair_density) : $skus;
                        } else {
                            $skus = $product->is_base_size_optional ? $skus->where('base_size_zh', $base_size) : $skus;
                            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_zh', $hair_colour) : $skus;
                            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_zh', $hair_density) : $skus;
                        }
                        if ($skus->isEmpty()) {
                            $fail(trans('basic.orders.Sku_does_not_exist'));
                        }
                        $sku = $skus->first();
                        if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
                'number' => [
                    'bail',
                    'required',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $product = Product::find($this->input('product_id'));
                        if ($product->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
                'address_id' => [
                    'bail',
                    'required',
                    'integer',
                    // 'exists:user_addresses,id',
                    function ($attribute, $value, $fail) {
                        if (!UserAddress::where(['id' => $value, 'user_id' => $this->user()->id])->exists()) {
                            $fail('该用户地址不存在');
                        }
                    },
                ],
                'remark' => 'bail|sometimes|nullable|string',
            ];
        } elseif ($this->routeIs('orders.pre_payment') || $this->routeIs('mobile.orders.pre_payment')) {
            return [
                'sku_id' => [
                    'bail',
                    'required_without:cart_ids',
                    'required_with:number',
                    'integer',
                    'exists:product_skus,id',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($value);
                        if ($sku->product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        if ($sku->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }
                        /*if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                    },
                ],
                'number' => [
                    'bail',
                    'required_without:cart_ids',
                    'required_with:sku_id',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($this->input('sku_id'));
                        if ($sku->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
                'cart_ids' => [
                    'bail',
                    'required_without_all:sku_id,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/',
                ],
            ];
        } elseif ($this->routeIs('orders.pre_payment_by_sku_parameters') || $this->routeIs('mobile.orders.pre_payment_by_sku_parameters')) {
            return [
                'base_size' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'hair_colour' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'hair_density' => [
                    'bail',
                    'sometimes',
                    'string',
                ],
                'product_id' => [
                    'bail',
                    'required',
                    'integer',
                    'exists:products,id',
                    function ($attribute, $value, $fail) {
                        $product = Product::find($value);
                        if ($product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        if ($product->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }
                        /*if ($product->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                        $base_size = $this->input('base_size');
                        $hair_colour = $this->input('hair_colour');
                        $hair_density = $this->input('hair_density');
                        if ($product->is_base_size_optional && !$base_size) {
                            $fail(trans('basic.orders.Plz_select_a_base_size'));
                        }
                        if ($product->is_hair_colour_optional && !$hair_colour) {
                            $fail(trans('basic.orders.Plz_select_a_hair_colour'));
                        }
                        if ($product->is_hair_density_optional && !$hair_density) {
                            $fail(trans('basic.orders.Plz_select_a_hair_density'));
                        }
                        $skus = $product->skus;
                        if (App::isLocale('en')) {
                            $skus = $product->is_base_size_optional ? $skus->where('base_size_en', $base_size) : $skus;
                            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_en', $hair_colour) : $skus;
                            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_en', $hair_density) : $skus;
                        } else {
                            $skus = $product->is_base_size_optional ? $skus->where('base_size_zh', $base_size) : $skus;
                            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_zh', $hair_colour) : $skus;
                            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_zh', $hair_density) : $skus;
                        }
                        if ($skus->isEmpty()) {
                            $fail(trans('basic.orders.Sku_does_not_exist'));
                        }
                        $sku = $skus->first();
                        if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
                'number' => [
                    'bail',
                    'required',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) {
                        $product = Product::find($this->input('product_id'));
                        if ($product->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },
                ],
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
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'currency' => '币种',
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
            'cart_ids' => '购物车IDs',
            'address_id' => '用户地址ID',
            'remark' => '订单备注',
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        return [
            'sku_id.exists' => trans('basic.orders.Product_sku_does_not_exist'),
            'cart_ids.regex' => trans('basic.orders.Cart_ids_with_bad_format'),
        ];
    }
}
