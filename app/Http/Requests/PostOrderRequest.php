<?php

namespace App\Http\Requests;

use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Models\UserCoupon;
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
        if ($this->routeIs('orders.store') || $this->routeIs('orders.get_total_shipping_fee')) {
            return [
                'currency' => [
                    'bail',
                    'sometimes',
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value != ExchangeRate::USD && ExchangeRate::where('currency', $value)->doesntExist()) {
                            $fail(trans('basic.orders.Currency_not_supported'));
                        }
                    },
                ],
                'sku_id' => [
                    'bail',
                    'required_without:sku_ids',
                    'required_with:number',
                    'integer',
                    'exists:product_skus,id',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($value);
                        if ($sku->product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        /*if ($sku->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }*/
                        /*if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                    },
                ],
                'number' => [
                    'bail',
                    'required_without:sku_ids',
                    'required_with:sku_id',
                    'integer',
                    'min:1',
                    /*function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($this->input('sku_id'));
                        if ($sku->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },*/
                ],
                'sku_ids' => [
                    'bail',
                    'required_without_all:sku_id,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/'
                ],
                'address_id' => [
                    'bail',
                    // 'required',
                    'sometimes',
                    'nullable',
                    'integer',
                    // 'exists:user_addresses,id',
                    function ($attribute, $value, $fail) {
                        if (!UserAddress::where(['id' => $value, 'user_id' => $this->user()->id])->exists()) {
                            $fail(trans('basic.orders.User_address_does_not_exist'));
                        }
                    },
                ],
                'coupon_id' => [
                    'bail',
                    'sometimes',
                    'nullable',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if (!UserCoupon::where(['id' => $value, 'user_id' => $this->user()->id, 'order_id' => null])->exists()) {
                            $fail(trans('basic.orders.User_coupon_does_not_exist'));
                        }
                    },
                ],
                'name' => 'bail|sometimes|nullable|string',
                'phone' => 'bail|sometimes|nullable|string',
                'address' => 'bail|sometimes|nullable|string',
                'province' => 'bail|required|string',
                'remark' => 'bail|sometimes|nullable|string',
            ];
        } elseif ($this->routeIs('orders.pre_payment')) {
            return [
                'sku_id' => [
                    'bail',
                    'required_without:product_id,product_sku_attr_values,sku_ids',
                    'required_with:number',
                    'integer',
                    'exists:product_skus,id',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($value);
                        if ($sku->product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        /*if ($sku->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }*/
                        /*if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                    },
                ],
                'product_id' => [
                    'required_without:sku_id,sku_ids',
                    'required_with:product_sku_attr_values,number',
                    'integer',
                    //'exists:products,id',
                    Rule::exists('products', 'id')->where(function ($query) {
                        return $query->where('on_sale', 1);
                    }),
                    // Rule::unique('user_favourites')->where('user_id', $user->id),
                    /*Rule::unique('user_favourites')->where(function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    }),*/
                ],
                'product_sku_attr_values' => 'required_without:sku_id,sku_ids|required_with:product_id,number|array',
                'product_sku_attr_values.*' => 'required_without:sku_id,sku_ids|required_with:product_id,number|string',
                'number' => [
                    'bail',
                    'required_without:sku_ids',
                    'required_with:sku_id,product_id,product_sku_attr_values',
                    'integer',
                    'min:1',
                    /*function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($this->input('sku_id'));
                        if ($sku->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },*/
                ],
                'sku_ids' => [
                    'bail',
                    'required_without_all:sku_id,product_id,product_sku_attr_values,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/',
                ],
            ];
        } elseif ($this->routeIs('orders.get_available_coupons')) {
            return [
                'sku_id' => [
                    'bail',
                    'required_without:sku_ids',
                    'required_with:number',
                    'integer',
                    'exists:product_skus,id',
                    function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($value);
                        if ($sku->product->on_sale == 0) {
                            $fail(trans('basic.orders.Product_sku_off_sale'));
                        }
                        /*if ($sku->stock == 0) {
                            $fail(trans('basic.orders.Product_sku_out_of_stock'));
                        }*/
                        /*if ($sku->stock < $this->input('number')) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }*/
                    },
                ],
                'number' => [
                    'bail',
                    'required_without:sku_ids',
                    'required_with:sku_id',
                    'integer',
                    'min:1',
                    /*function ($attribute, $value, $fail) {
                        $sku = ProductSku::find($this->input('sku_id'));
                        if ($sku->stock < $value) {
                            $fail(trans('basic.orders.Insufficient_sku_stock'));
                        }
                    },*/
                ],
                'sku_ids' => [
                    'bail',
                    'required_without_all:sku_id,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/',
                ],
            ];
        } elseif ($this->routeIs('orders.integrate')) {
            return [
                'order_ids' => [
                    'bail',
                    'required',
                    'string',
                    'regex:/^\d+(\,\d+)*$/'
                ]
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
            'currency' => '币种',
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
            'sku_ids' => '商品SKU-IDs',
            'address_id' => '用户地址ID',
            'name' => '收件人姓名',
            'phone' => '收件人联系方式',
            'address' => '收货地址',
            'remark' => '订单备注',
            'order_ids' => '订单IDs'
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
            'sku_ids.regex' => trans('basic.orders.Cart_ids_with_bad_format'),
        ];
    }
}
