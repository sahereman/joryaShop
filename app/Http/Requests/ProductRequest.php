<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
