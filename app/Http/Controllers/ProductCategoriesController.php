<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductCategoriesController extends Controller
{
    // 商品分类列表
    public function index (Request $request, ProductCategory $category)
    {
        return view('product_categories.index', []);
    }

    // 商品分类呈现[一|二级分类]
    public function home (ProductRequest $request, ProductCategory $category)
    {
        // TODO ...
        return view('product_categories.home', []);
    }
}
