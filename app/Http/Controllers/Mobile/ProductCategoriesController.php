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
        $current_page = $request->has('page') ? $request->input('page') : 1;
        if (preg_match('/^\d+$/', $current_page) != 1) {
            if (App::isLocale('en')) {
                throw new InvalidRequestException('The parameter page must be an integer.');
            } else {
                throw new InvalidRequestException('页码参数必须为数字！');
            }
        }
        $children = $category->children()->with('products')->get();
        $page_size = 1;
        $page_count = $children->count() / $page_size;
        $next_page = ($current_page >= $page_count) ? false : ($current_page + 1);
        $request_url = $next_page ? route('mobile.product_categories.more', ['category' => $category->id]) . '?page=' . $next_page : false;
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'children' => $children->forPage($current_page, $page_size),
                'request_url' => $request_url,
            ],
        ]);
    }
}
