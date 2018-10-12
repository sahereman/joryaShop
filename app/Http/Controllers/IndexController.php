<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function root(Request $request)
    {
        $products = [];
        $products['latest'] = Product::where(['is_index' => true, 'on_sale' => true])->latest()->limit(3)->get();
        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => true])->get();
        foreach ($categories as $category) {
            $subCategoryIds = [];
            $category->sub_categories->each(function($subCategory) use (&$subCategoryIds) {
                $subCategoryIds[] = $subCategory->id;
            });
            if($subCategoryIds !== []){
                $products['category'][$category->id]['category'] = $category;
                $products['category'][$category->id]['products'] = Product::where('is_index', true)->whereIn('product_category_id', $subCategoryIds)->orderByDesc('index')->limit(8)->get();
            }
        }
        $products['guess'] = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
//        var_dump($products['category']);
        return view('index.root', [
            'products' => $products,
        ]);
    }
}
