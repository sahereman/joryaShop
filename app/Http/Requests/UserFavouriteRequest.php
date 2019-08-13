<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use App\Models\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserFavouriteRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('user_favourites.store')) {
            $user = $this->user();
            return [
                'product_id' => [
                    'required',
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
            ];
        } elseif ($this->routeIs('user_favourites.multi_delete')) {
            return [
                'favourite_ids' => [
                    'required',
                    'string',
                    'regex:/^\d+(\,\d+)*$/',
                ],
            ];
        } elseif ($this->routeIs('user_favourites.destroy')) {
            return [
                'favourite_id' => 'required|integer|exists:user_favourites,id',
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
            'product_id' => '商品ID',
            'favourite_ids' => '收藏IDs',
        ];
    }

    public function messages()
    {
        return [
            'product_id.exits' => trans('basic.favourites.Plz_select_a_product'),
            'product_id.unique' => trans('basic.favourites.Product_added_to_favourites'),
        ];
    }
}
