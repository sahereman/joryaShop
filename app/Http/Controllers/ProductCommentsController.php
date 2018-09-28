<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductCommentsController extends Controller
{
    public function index (Product $product)
    {
        return view('product_comments.index', []);
    }

    public function store (Request $request)
    {
        // TODO ...
    }
}
