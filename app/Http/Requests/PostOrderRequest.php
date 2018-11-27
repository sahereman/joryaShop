<?php

namespace App\Http\Requests;

use App\Models\ExchangeRate;
use App\Models\ProductSku;
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
                            if (App::isLocale('en')) {
                                $fail('This currency is not supported yet.');
                            } else {
                                $fail('该币种支付暂不支持');
                            }
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
                    'required_without:cart_ids',
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
                'cart_ids' => [
                    'bail',
                    'required_without_all:sku_id,number',
                    'string',
                    'regex:/^\d+(\,\d+)*$/'
                ],
                'name' => 'bail|required|string',
                'phone' => 'bail|required|string',
                'address' => 'bail|required|string',
                'remark' => 'bail|sometimes|nullable|string',
            ];
        } elseif ($this->routeIs('orders.pre_payment')) {
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
                    'required_without:cart_ids',
                    'required_with:sku_id',
                    'integer',
                    'min:1',
                    function ($attribute, $value, $fail) use ($this) {
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
            'name' => '收货人',
            'phone' => '手机号码',
            'address' => '详细地址',
            'remark' => '订单备注',
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            'sku_id.exists' => '该商品不存在',
            'cart_ids.regex' => '购物车IDs格式不正确',
        ];
    }
}
