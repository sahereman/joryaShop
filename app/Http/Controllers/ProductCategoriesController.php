<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\ExchangeRate;
use App\Models\Param;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductParam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductCategoriesController extends Controller
{
    // GET 商品分类列表:
    // 一级分类及其商品列表
    // 二级分类及其商品列表
    public function index(Request $request, ProductCategory $category)
    {
        $query_data = [];
        $query_param_values = [];
        $user = $request->user();
        $queries = $request->query();
        $is_by_param = $request->query('is_by_param');
        foreach ($queries as $key => $value) {
            if (strpos($key, 'param-') === 0) {
                $param = str_replace('_', ' ', substr($key, 6));
                $query_data[$key] = $value;
                $query_param_values[$param] = $value;
            }
        }

        $category = ProductCategory::with('children.children')->find($category->id);

        // crumbs
        $crumbs = '&nbsp;<span>&nbsp;&gt;&nbsp;</span>&nbsp;<a href="javascript:void(0);">' . $category->name_en . '</a>';
        $child = $category;
        while ($parent = $child->parent) {
            $crumbs = '&nbsp;<span>&nbsp;&gt;&nbsp;</span>&nbsp;<a href="'
                . route('seo_url', ['slug' => $parent->slug])
                . '">' . $parent->name_en . '</a>' . $crumbs;
            $child = $parent;
        }

        $category_ids = [$category->id];
        if ($children = $category->children) {
            $children->each(function (ProductCategory $child) use (&$category_ids) {
                $category_ids[] = $child->id;
                if ($children = $child->children) {
                    $children->each(function (ProductCategory $child) use (&$category_ids) {
                        $category_ids[] = $child->id;
                    });
                }
            });
        }

        /*if ($category->children->isNotEmpty()) {
            $products = Product::where('on_sale', 1)->whereIn('product_category_id', $category_ids);
        } else {
            $products = $category->products()->where('on_sale', 1);
        }*/

        $products = Product::where('on_sale', 1)->whereIn('product_category_id', $category_ids);
        $all_products = Product::where('on_sale', 1)->whereIn('product_category_id', $category_ids);

        if ($is_by_param == 1 && count($query_param_values) > 0) {
            $query_data['is_by_param'] = $is_by_param;
            $product_ids = [];
            foreach ($query_param_values as $param => $value) {
                if ($product_ids == []) {
                    $product_ids = ProductParam::where(['name' => $param, 'value' => $value])->get()->pluck('product_id')->toArray();
                } else {
                    $product_ids = ProductParam::where(['name' => $param, 'value' => $value])->whereIn('product_id', $product_ids)->get()->pluck('product_id')->toArray();
                }
            }
            $products = $products->whereIn('id', $product_ids);
            // $all_products = $all_products->whereIn('id', $product_ids);
        }

        /*if ($request->has('min_price') && $request->input('min_price')) {
            // $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $min_price = exchange_price($request->input('min_price'), 'USD', get_global_currency());
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
            $all_products = $all_products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            // $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $max_price = exchange_price($request->input('max_price'), 'USD', get_global_currency());
            $query_data['max_price'] = $request->input('max_price');
            $products = $products->where('price', '<', $max_price);
            $all_products = $all_products->where('price', '<', $max_price);
        }*/

        $query_data['order'] = $request->input('order', 'desc');

        if ($request->has('sort')) {
            $query_data['sort'] = $request->input('sort', 'index');
            switch ($request->input('sort')) {
                case 'index':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('index');
                    } else {
                        $products = $products->orderByDesc('index');
                    }
                    // $all_products = $all_products->orderByDesc('index');
                    break;
                case 'heat':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('heat');
                    } else {
                        $products = $products->orderByDesc('heat');
                    }
                    // $all_products = $all_products->orderByDesc('heat');
                    break;
                case 'latest':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('created_at');
                    } else {
                        $products = $products->orderByDesc('created_at');
                    }
                    // $all_products = $all_products->orderByDesc('created_at');
                    break;
                case 'sales':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('sales');
                    } else {
                        $products = $products->orderByDesc('sales');
                    }
                    // $all_products = $all_products->orderByDesc('sales');
                    break;
                case 'price':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('price');
                    } else {
                        $products = $products->orderByDesc('price');
                    }
                    // $all_products = $all_products->orderBy('price');
                    break;
                default:
                    $products = $products->orderByDesc('index');
                    // $all_products = $all_products->orderByDesc('index');
                    break;
            }
        } else {
            $products = $products->orderByDesc('index');
            // $all_products = $all_products->orderByDesc('index');
        }
        $products = $products->simplePaginate(12);

        $param_values = [];
        Param::orderByDesc('sort')->get()->each(function (Param $param) use (&$param_values) {
            $param_values[$param->name] = [];
        });
        $all_products->get()->each(function (Product $product) use (&$param_values) {
            $product->params->each(function (ProductParam $productParam) use (&$param_values) {
                if (!isset($param_values[$productParam->name])) {
                    $param_values[$productParam->name] = [];
                }
                if (!isset($param_values[$productParam->name][$productParam->value])) {
                    $param_values[$productParam->name][$productParam->value] = 1;
                } else {
                    $param_values[$productParam->name][$productParam->value] += 1;
                }
            });
        });

        return view('product_categories.index', [
            'user' => $user,
            'category' => $category,
            'crumbs' => $crumbs,
            'param_values' => $param_values,
            'query_param_values' => $query_param_values,
            'products' => $products,
            'query_data' => $query_data
        ]);
    }
}
