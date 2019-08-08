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
    // 一级分类及其商品列表
    // 二级分类及其商品列表
    public function index(ProductRequest $request, ProductCategory $category)
    {
        $user = $request->user();
        $category = ProductCategory::with('children.children')->find($category->id);

        // crumbs
        $crumbs = '&nbsp;<span>&nbsp;&gt;&nbsp;</span>&nbsp;<a href="javascript:void(0);">' . $category->name_en . '</a>';
        $child = $category;
        while ($parent = $child->parent) {
            $crumbs = '&nbsp;<span>&nbsp;&gt;&nbsp;</span>&nbsp;<a href="'
                . route('product_categories.index', ['category' => $parent->id])
                . '">' . $parent->name_en . '</a>' . $crumbs;
            $child = $parent;
        }

        if ($category->children->isNotEmpty()) {
            $children_ids = $category->children->pluck('id')->toArray();
            $products = Product::where('on_sale', 1)->whereIn('product_category_id', $children_ids);
        } else {
            $products = $category->products()->where('on_sale', 1);
        }

        $query_data = [];
        if ($request->has('min_price') && $request->input('min_price')) {
            // $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $min_price = exchange_price($request->input('min_price'), 'USD', get_global_currency());
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            // $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $max_price = exchange_price($request->input('max_price'), 'USD', get_global_currency());
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
        // $products = $products->paginate(12);
        $products = $products->simplePaginate(12);

        return view('product_categories.index', [
            'user' => $user,
            'category' => $category,
            'crumbs' => $crumbs,
            'products' => $products,
            'query_data' => $query_data
        ]);
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
            // $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $min_price = exchange_price($request->input('min_price'), 'USD', get_global_currency());
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            // $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $max_price = exchange_price($request->input('max_price'), 'USD', get_global_currency());
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
