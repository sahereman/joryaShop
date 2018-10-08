<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductComment;
use App\Models\ProductCategory;

class ProductsController extends Controller
{
    // 商品列表 | 搜索结果
    public function index (ProductRequest $request, ProductCategory $productCategory)
    {
        return view('products.index', []);
    }

    // 商品详情
    public function show (Request $request, Product $product)
    {
        return view('products.show', []);
    }

    public function comments (Request $request, Product $product)
    {
        // TODO ...
        // api for ajax request.
        return response()->json([
            //
        ]);
    }
}
