<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    // 商品分类列表
    public function index (ProductCategory $productCategory)
    {
        return view('product_categories.index', []);
    }
}
