<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('product_categories.index')) {
            return [
                // 'is_by_param' => 'bail|sometimes|nullable|integer|in:0,1', // boolean: 0, 1
                'is_by_param' => 'bail|sometimes|nullable|boolean', // boolean: 0, 1
                // 'param' => 'bail|sometimes|nullable|string',
                // 'value' => 'bail|sometimes|nullable|string',
                'sort' => [
                    'bail',
                    'sometimes',
                    'nullable',
                    'string',
                    Rule::in(['index', 'heat', 'latest', 'sales', 'price_asc', 'price_desc'])
                ],
                'min_price' => 'bail|sometimes|nullable|numeric',
                'max_price' => 'bail|sometimes|nullable|numeric',
                'page' => 'sometimes|required|integer|min:1',
            ];
        } elseif ($this->routeIs('products.search')) {
            return [
                // 'is_by_param' => 'bail|sometimes|nullable|integer|in:0,1', // boolean: 0, 1
                'is_by_param' => 'bail|sometimes|nullable|boolean', // boolean: 0, 1
                'param' => 'bail|sometimes|nullable|string',
                'value' => 'bail|sometimes|nullable|string',
                'query' => 'bail|sometimes|nullable|string',
                'sort' => [
                    'bail',
                    'sometimes',
                    'nullable',
                    'string',
                    Rule::in(['index', 'heat', 'latest', 'sales', 'price_asc', 'price_desc'])
                ],
                'min_price' => 'bail|sometimes|nullable|numeric',
                'max_price' => 'bail|sometimes|nullable|numeric',
                'page' => 'sometimes|required|integer|min:1',
            ];
        } elseif ($this->routeIs('products.share')) {
            return [
                'to_email' => 'bail|required|email',
                'from_email' => 'bail|sometimes|nullable|email',
                'subject' => 'bail|sometimes|nullable|string',
                'body' => 'bail|sometimes|nullable|string'
            ];
        } elseif ($this->routeIs('products.custom.store')) {
            return [
                'custom_attr_value_ids' => 'required|regex:/^\d+(\,\d+)*$/',
            ];
        } elseif ($this->routeIs('products.search_by_sku_attr')) {
            return [
                'product_sku_attr_values' => 'nullable|array',
                'product_sku_attr_values.*' => 'nullable|string',
            ];
        } else {
            throw new NotFoundHttpException();
        }
    }

    public function attributes()
    {
        if (!App::isLocale('zh-CN')) {
            return [];
        }
        return [
            'is_by_param' => '是否根据商品属性进行搜索', // 0, 1
            'param' => '商品属性名称',
            'value' => '商品属性值',
            'query' => '搜索内容',
            'sort' => '排序方式',
            'min_price' => '最低价格',
            'max_price' => '最高价格',
            'page' => '页码',
        ];
    }
}
