<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $skus = $product->skus;
        $comment_count = $product->comments->count();
        /* SQL: SELECT COUNT(*) FROM `product_comments` WHERE `photos` IS NOT NULL AND `photos` <> ''; */
        $photo_comment_count = $product->comments()->whereNotNull('photos')->Where('photos', '<>', '')->count();
        return view('mobile.products.show', [
            'product' => $product,
            'skus' => $skus,
            'comment_count' => $comment_count,
            'photo_comment_count' => $photo_comment_count,
        ]);
    }
}
