<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductCategoriesController extends Controller
{
    // 商品分类列表:
    // 一级分类及其商品列表 [完整展示]
    // 二级分类及其商品列表 [下拉加载更多]
    public function index(Request $request, ProductCategory $category)
    {
        $products = [];
        if ($category->parent_id == 0) {
            $children = $category->children;
            $children->each(function (ProductCategory $child) use ($products) {
                // on_sale: 是否在售 + index: 综合指数
                $products[$child->id]['products'] = $child->products()->where('on_sale', 1)->orderByDesc('index')->limit(10)->get();
            });
            return view('product_categories.index', [
                'category' => $category,
                'children' => $children,
                'products' => $products,
            ]);
        } else {
            $this->validate($request, [
                'page' => 'sometimes|required|integer|min:1',
            ], [], [
                'page' => '页码',
            ]);
            $parent = $category->parent;
            $current_page = $request->has('page') ? $request->input('page') : 1;
            // on_sale: 是否在售 + index: 综合指数
            $products = $category->products()
                ->where('on_sale', 1)
                ->orderByDesc('index')
                ->simplePaginate(10);
            $product_count = $products->count();
            $page_count = ceil($product_count / 10);
            $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
            if ($next_page == false) {
                $request_url = false;
            } else {
                $request_url = route('product_categories.index', [
                        'category' => $category->id,
                    ]) . '?page=' . $next_page;
            }
            return view('products.index', [
                'parent' => $parent,
                'category' => $category,
                'products' => $products,
                'request_url' => $request_url,
            ]);
        }
    }
}
