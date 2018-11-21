<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartsController extends Controller
{
    public function index()
    {
        return view('mobile.carts.index', [

        ]);
    }
}
