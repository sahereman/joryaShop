<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductSkuAttrValue;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductsController extends Controller
{
    public function search(ProductRequest $request)
    {
        return view('mobile.products.search', [
            'query' => $request->query('query'),
        ]);
    }

    public function show(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('该商品尚未上架');
        }

        // user browsing history - appending (maybe firing an event)
        $productsController = new \App\Http\Controllers\ProductsController();
        $productsController->appendUserBrowsingHistoryCacheByProduct($product);

        $comment_count = $product->comments->count();
        /* SQL: SELECT COUNT(*) FROM `product_comments` WHERE `photos` IS NOT NULL AND `photos` <> ''; */
        $photo_comment_count = $product->comments()->whereNotNull('photos')->where('photos', '<>', '')->count();
        $user = $request->user();
        $is_favourite = false;
        if ($user) {
            $is_favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $product->id)->exists();
        }

        $product_skus = $product->skus;
        $product_sku_ids = $product_skus->pluck('id');
        $attributes = ProductSkuAttrValue::with('sku')->whereIn('product_sku_id', $product_sku_ids)->get()->groupBy('product_sku_id')->toArray();

        return view('mobile.products.show', [
            'product' => $product->makeVisible(['content_en', 'content_zh']),
            'product_skus' => $product_skus,
            'attributes' => $attributes,
            'comment_count' => $comment_count,
            'photo_comment_count' => $photo_comment_count,
            'is_favourite' => $is_favourite,
        ]);
    }
}
