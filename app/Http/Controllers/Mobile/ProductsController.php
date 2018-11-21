<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function search()
    {
        return view('mobile.products.search', [

        ]);
    }

    public function show(Product $product)
    {

        return view('mobile.products.show', [
            'product' => $product
        ]);
    }
}
