<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function root(Request $request)
    {
        $products = [];
        // TODO ... [投放广告页]
        $posters = Poster::where(['slug' => 'advertisement'])->latest()->limit(3)->get();

        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => true])->get();
        foreach ($categories as $category) {
            $child_category_ids = [];
            if ($category->parent_id == 0) {
                $category->children->each(function ($child_category) use (&$child_category_ids) {
                    $child_category_ids[] = $child_category->id;
                });
            }
            if ($child_category_ids !== []) {
                $products[$category->id]['category'] = $category;
                $products[$category->id]['products'] = Product::where('is_index', true)->whereIn('product_category_id', $child_category_ids)->orderByDesc('index')->limit(8)->get();
            }
        }
        $guesses = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
        return view('index.root', [
            'posters' => $posters,
            'products' => $products,
            'guesses' => $guesses,
        ]);
    }
}
