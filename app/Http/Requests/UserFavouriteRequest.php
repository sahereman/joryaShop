<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\Product;

class UserFavouriteRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $user = $this->user();
        return [
            'product_id' => [
                'required',
                'numeric',
                //'exists:products,id',
                Rule::exists('products', 'id')->where(function ($query) {
                    return $query->where([['on_sale', '=', 1], ['stock', '>', '0']]);
                }),
                /*function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if ($product == null) {
                        $fail('该商品不存在');
                    } else if (!$product->on_sale) {
                        $fail('该商品未上架');
                    } else if ($product->stock === 0) {
                        $fail('该商品已售完');
                    }
                },*/
                Rule::unique('user_favourites')->where('user_id', $user->id),
                /*Rule::unique('user_favourites')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),*/
            ],
        ];
    }

    public function attributes()
    {
        return [
            'product_id' => '商品ID',
        ];
    }

    public function messages()
    {
        return [
            'product_id.exits' => '请选择商品添加收藏',
            'product_id.unique' => '该商品已添加收藏',
        ];
    }
}
