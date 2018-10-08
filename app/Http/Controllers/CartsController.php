<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartsController extends Controller
{
    // GET 购物车清单
    public function index (Request $request)
    {
        return view('carts.index');
    }

    // POST 加入购物车
    public function store (Request $request)
    {
        // TODO ...
    }

    // PATCH 更新 (增减数量)
    public function update (Cart $cart)
    {
        // TODO ...
    }

    // DELETE 删除
    public function destroy(Cart $cart)
    {
        // TODO ...
    }

    // DELETE 清空购物车
    public function flush (Request $request)
    {
        // TODO ...
    }
}
