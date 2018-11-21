<?php

namespace App\Http\Controllers\Mobile;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProductCategoriesController extends Controller
{
    public function index(Request $request)
    {
        return view('mobile.product_categories.index', [

        ]);
    }

    public function categoryMore(ProductCategory $category)
    {

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'categories' => $category->children,
            ],
        ]);
    }

    public function product(ProductCategory $category)
    {

        return view('mobile.product_categories.product', [

        ]);
    }

}
