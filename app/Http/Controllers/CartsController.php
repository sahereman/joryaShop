<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CartsController extends Controller
{
    // GET 购物车清单
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

        return view('carts.index', [
            'carts' => $carts,
        ]);
    }

    // POST 加入购物车
    public function store(CartRequest $request)
    {
        $cart = Cart::where([
            'user_id' => $request->user()->id,
            'product_sku_id' => $request->input('sku_id'),
        ])->first();

        if ($cart) {
            $cart->increment('number', $request->input('number'));
            $result = $cart->save();
        } else {
            $result = Cart::create([
                'user_id' => $request->user()->id,
                'product_sku_id' => $request->input('sku_id'),
                'number' => $request->input('number'),
            ]);
        }

        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // POST 加入购物车
    public function storeBySkuParameters(CartRequest $request)
    {
        $base_size = $request->input('base_size');
        $hair_colour = $request->input('hair_colour');
        $hair_density = $request->input('hair_density');
        $product = Product::find($request->input('product_id'));
        $skus = $product->skus();
        if (App::isLocale('zh-CN')) {
            $skus = $product->is_base_size_optional ? $skus->where('base_size_zh', $base_size) : $skus;
            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_zh', $hair_colour) : $skus;
            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_zh', $hair_density) : $skus;
        } else {
            $skus = $product->is_base_size_optional ? $skus->where('base_size_en', $base_size) : $skus;
            $skus = $product->is_hair_colour_optional ? $skus->where('hair_colour_en', $hair_colour) : $skus;
            $skus = $product->is_hair_density_optional ? $skus->where('hair_density_en', $hair_density) : $skus;
        }
        $sku = $skus->first();
        $cart = Cart::where([
            'user_id' => $request->user()->id,
            'product_sku_id' => $sku->id,
        ])->first();

        if ($cart) {
            $cart->increment('number', $request->input('number'));
            $result = $cart->save();
        } else {
            $result = Cart::create([
                'user_id' => $request->user()->id,
                'product_sku_id' => $request->input('sku_id'),
                'number' => $request->input('number'),
            ]);
        }

        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // PATCH 更新 (增减数量)
    public function update(CartRequest $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $cart->update([
            'number' => $request->input('number'),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // DELETE 删除
    public function destroy(Request $request, Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->user()->dissociate();
        $cart->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // DELETE 清空购物车
    public function flush(Request $request)
    {
        Cart::where(['user_id' => $request->user()->id])->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
