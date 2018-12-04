<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class ProductCategoriesController extends Controller
{
    // GET 商品分类展示首页 [仅展示页面]
    public function index(Request $request)
    {
        $categories = ProductCategory::where('parent_id', 0)->get();
        $category_id = $request->has('category') ? $request->input('category') : ProductCategory::where('parent_id', 0)->first()->id;
        return view('mobile.product_categories.index', [
            'categories' => $categories,
            'category_id' => $category_id,
        ]);
    }

    // GET 商品一级分类展示列表 [for Ajax request]
    public function more(Request $request, ProductCategory $category)
    {
        $children = $category->children()->with('products')->get();
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'children' => $children,
            ],
        ]);
    }
}
