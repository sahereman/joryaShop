<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class IndexController extends Controller
{
    // GET 首页
    public function root(Request $request)
    {
        $banners = Banner::where('type', 'mobile')->orderBy('sort')->get();
        $latest = Product::where('is_index', 1)->latest('updated_at')->limit(8)->get();

        $products = [];
        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => 1])->orderBy('sort')->get()->reject(function ($item, $key) {
            return $item->children->isEmpty();
        });
        $categories = $categories->values(); // reset the indices.
        foreach ($categories as $key => $category) {
            $children = $category->children;
            $children_ids = $children->pluck('id')->all();
            $products[$key]['category'] = $category;
            $products[$key]['children'] = $children;
            $products[$key]['products'] = Product::where('is_index', 1)->whereIn('product_category_id', $children_ids)->orderByDesc('index')->limit(8)->get();
        }
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();

        return view('mobile.index.root', [
            'banners' => $banners,
            'latest' => $latest,
            'products' => $products,
            'guesses' => $guesses,
        ]);
    }

    // GET search more ... [for Ajax request]
    public function guessMore(Request $request)
    {
        $current_page = $request->has('page') ? $request->input('page') : 1;
        if (preg_match('/^\d+$/', $current_page) != 1) {
            if (App::isLocale('en')) {
                throw new InvalidRequestException('The parameter page must be an integer.');
            } else {
                throw new InvalidRequestException('页码参数必须为数字！');
            }
        }
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->simplePaginate(8);
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'guesses' => $guesses,
            ],
        ]);
    }

    // GET 搜索页面 [仅展示页面]
    public function search(Request $request)
    {
        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => 1])->get();
        return view('mobile.index.search', [
            'categories' => $categories,
        ]);
    }

    // GET 修改网站语言 页面
    public function localeShow(Request $request)
    {
        return view('mobile.index.locale');
    }
}
