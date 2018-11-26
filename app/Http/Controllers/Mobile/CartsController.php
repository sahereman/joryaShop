<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function index(Request $request)
    {
        $carts = $request->user()->carts()->with('sku.product')->get();

        // 自动清除失效商品[已删除或已下架商品]
        foreach ($carts as $cart) {
            if (!$cart->sku->product || !$cart->sku->product->on_sale) {
                $cart->user()->dissociate();
                $cart->delete();
            }
        }

        return view('mobile.carts.index', [
            'carts' => $carts,
        ]);
    }
}
