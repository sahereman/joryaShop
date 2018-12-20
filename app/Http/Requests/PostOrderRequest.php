<?php

namespace App\Http\Requests;

use App\Models\ExchangeRate;
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
