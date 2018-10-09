<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ProductRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'query' => 'sometimes|string',
            'category' => 'sometimes|integer',
            'sort' => [
                'sometimes',
                Rule::in(['index', 'heats', 'latest', 'sales', 'price']),
            ],
            'order' => [
                'sometimes',
                Rule::in(['asc', 'desc']),
            ],
            'min_price' => 'sometimes|numeric',
            'max_price' => 'sometimes|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'query' => '搜索商品',
            'category' => '商品分类',
            'sort' => '排序方式',
            'order' => '顺序倒序',
            'min_price' => '最低价位',
            'max_price' => '最高价位',
        ];
    }
}
