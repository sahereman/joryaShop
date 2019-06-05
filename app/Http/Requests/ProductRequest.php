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
        if ($this->routeIs('product_categories.more')) {
            return [
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
        } elseif ($this->routeIs('products.search_more') || $this->routeIs('products.search') || $this->routeIs('mobile.products.search')) {
            return [
                'query' => 'bail|string|nullable',
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
            'query' => '搜索内容',
            'sort' => '排序方式',
            'min_price' => '最低价格',
            'max_price' => '最高价格',
            'page' => '页码',
        ];
    }
}
