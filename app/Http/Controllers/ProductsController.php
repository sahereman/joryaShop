<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // 商品分类呈现
    public function getProductByCategory (ProductCategory $productCategory)
    {
        return view('products.index', []);
    }

    // 商品搜索结果
    public function search(Request $request)
    {
        return view('products.search', []);
    }

    // 商品详情
    public function show(Product $product)
    {
        return view('products.show', []);
    }
}
