<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductCategoriesController extends Controller
{
    // GET 商品分类列表:
    // 一级分类及其商品列表 [完整展示]
    // 二级分类及其商品列表 [仅展示页面]
    public function index(Request $request, ProductCategory $category)
    {
        $products = [];
        if ($category->parent_id == 0) {
            $children = $category->children;
            $children->each(function (ProductCategory $child) use (&$products) {
                // on_sale: 是否在售 + index: 综合指数
                $products[$child->id] = $child->products()->where('on_sale', 1)->orderByDesc('index')->limit(10)->get();
            });
            return view('product_categories.index', [
                'category' => $category,
                'children' => $children,
                'products' => $products,
            ]);
        } else {
            // 第一次请求 route('product_categories.index') 打开待填充数据页面
            return view('products.index', [
                'category' => $category,
            ]);

        }
    }

    // GET 二级分类及其商品列表 下拉加载更多 [for Ajax request]
    public function more(ProductRequest $request, ProductCategory $category)
    {
        $parent = $category->parent;
        // Ajax request for the 1st time: route('product_categories.index').'?page=1'
        $current_page = $request->has('page') ? $request->input('page') : 1;
        // on_sale: 是否在售 + index: 综合指数
        $products = $category->products()
            ->where('on_sale', 1);
        $product_count = $products->count();
        $page_count = ceil($product_count / 10);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;

        $query_data = [];
        if ($request->has('min_price') && $request->input('min_price')) {
            $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $query_data['max_price'] = $request->input('max_price');
            $products = $products->where('price', '<', $max_price);
        }
        if ($request->has('sort')) {
            $query_data['sort'] = $request->input('sort');
            switch ($request->input('sort')) {
                case 'index':
                    $products = $products->orderByDesc('index');
                    break;
                case 'heat':
                    $products = $products->orderByDesc('heat');
                    break;
                case 'latest':
                    $products = $products->orderByDesc('created_at');
                    break;
                case 'sales':
                    $products = $products->orderByDesc('sales');
                    break;
                case 'price_asc':
                    $products = $products->orderBy('price');
                    break;
                case 'price_desc':
                    $products = $products->orderByDesc('price');
                    break;
                default:
                    $products = $products->orderByDesc('index');
                    break;
            }
        }
        $products = $products->simplePaginate(10);

        if ($next_page == false) {
            $request_url = false;
        } else {
            $query_data['page'] = $next_page;
            $request_url = route('product_categories.index', [
                    'category' => $category->id,
                ]) . '?' . http_build_query($query_data);
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'parent' => $parent,
                'category' => $category,
                'products' => $products,
                'request_url' => $request_url,
            ],
        ]);
    }
}
