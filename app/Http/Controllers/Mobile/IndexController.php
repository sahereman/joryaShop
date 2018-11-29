<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    // GET 首页
    public function root(Request $request)
    {
        $banners = Banner::where('type', 'index')->latest()->get();

        $products = [];
        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => 1])->get();
        foreach ($categories as $category) {
            $children = $category->children;
            if ($children->isEmpty()) {
                continue;
            }
            $children_ids = $children->pluck('id')->all();
            $products[$category->id]['category'] = $category;
            $products[$category->id]['children'] = $children;
            $products[$category->id]['products'] = Product::where('is_index', 1)->whereIn('product_category_id', $children_ids)->orderByDesc('index')->limit(8)->get();
        }
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();

        return view('mobile.index.root', [
            'banners' => $banners,
            'products' => $products,
            'guesses' => $guesses,
        ]);
    }

    // GET 搜索页面 [仅展示页面]
    public function search(Request $request)
    {
        return view('mobile.index.search');
    }

    // GET 修改网站语言 页面
    public function localeShow(Request $request)
    {
        return view('mobile.index.locale');
    }
}
