<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CartsController extends Controller
{
    // GET 购物车清单
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $carts = $user->carts()->with('sku.product')->get();

            // 自动清除失效商品[已删除或已下架商品]
            /*foreach ($carts as $key => $cart) {
                if (!$cart->sku->product || !$cart->sku->product->on_sale) {
                    $cart->user()->dissociate();
                    $cart->delete();
                    $carts->forget($key);
                }
            }*/
            /*if ($carts->isNotEmpty()) {
                $carts = $carts->reject(function (Cart $cart, $key) {
                    return (!$cart->sku->product || !$cart->sku->product->on_sale);
                });
            }*/
            if ($carts->isNotEmpty()) {
                $carts = $carts->filter(function (Cart $cart, $key) {
                    return ($cart->sku->product && $cart->sku->product->on_sale);
                });
            }
        } else {
            $carts = session('carts', []);
            // $carts = Session::get('carts', []);

            // 自动清除失效商品[已删除或已下架商品]
            $flag = false;
            foreach ($carts as $key => $cart) {
                $product_sku = ProductSku::with('product')->find($cart['product_sku_id']);
                if (!$product_sku->product || !$product_sku->product->on_sale) {
                    unset($carts[$key]);
                    $flag = true;
                }
            }
            if ($flag) {
                session(['carts' => $carts]);
                // Session::put('carts', $carts);
                // Session::put(['carts' => $carts]);
            }
        }

        return view('carts.index', [
            'carts' => $carts,
        ]);
    }

    // POST 加入购物车
    public function store(CartRequest $request)
    {
        $user = $request->user();
        $sku_id = $request->input('sku_id');
        $number = $request->input('number');

        if ($user) {
            $cart = Cart::where([
                'user_id' => $user->id,
                'product_sku_id' => $sku_id
            ])->first();
            if ($cart) {
                $cart->increment('number', $number);
                $cart->save();
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_sku_id' => $sku_id,
                    'number' => $number
                ]);
            }
        } else {
            $carts = session('carts', []);
            // $carts = Session::get('carts', []);
            $flag = false;
            foreach ($carts as $key => $cart) {
                if ($cart['product_sku_id'] == $sku_id) {
                    $carts[$key]['number'] += $number;
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $carts[] = [
                    'product_sku_id' => $sku_id,
                    'number' => $number
                ];
            }
            session(['carts' => $carts]);
            // Session::put('carts', $carts);
            // Session::put(['carts' => $carts]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // PATCH 更新 (增减数量)
    public function update(CartRequest $request)
    {
        // $this->authorize('update', $cart);

        $user = $request->user();
        $sku_id = $request->input('sku_id');
        $number = $request->input('number');

        if ($user) {
            $cart = Cart::where([
                'user_id' => $user->id,
                'product_sku_id' => $sku_id
            ])->first();
            if ($cart) {
                $cart->update([
                    'number' => $number
                ]);
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_sku_id' => $sku_id,
                    'number' => $number
                ]);
            }
        } else {
            $carts = session('carts', []);
            // $carts = Session::get('carts', []);
            $flag = false;
            foreach ($carts as $key => $cart) {
                if ($cart['product_sku_id'] == $sku_id) {
                    $carts[$key]['number'] = $number;
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $carts[] = [
                    'product_sku_id' => $sku_id,
                    'number' => $number
                ];
            }
            session(['carts' => $carts]);
            // Session::put('carts', $carts);
            // Session::put(['carts' => $carts]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // DELETE 删除
    public function destroy(CartRequest $request)
    {
        // $this->authorize('delete', $cart);

        $user = $request->user();
        $sku_id = $request->input('sku_id');

        if ($user) {
            $cart = Cart::where([
                'user_id' => $user->id,
                'product_sku_id' => $sku_id
            ])->first();
            if ($cart) {
                $cart->user()->dissociate();
                $cart->delete();
            }
        } else {
            $carts = session('carts', []);
            // $carts = Session::get('carts', []);
            $flag = false;
            foreach ($carts as $key => $cart) {
                if ($cart['product_sku_id'] == $sku_id) {
                    unset($carts[$key]);
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                session(['carts' => $carts]);
                // Session::put('carts', $carts);
                // Session::put(['carts' => $carts]);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // DELETE 清空购物车
    public function flush(Request $request)
    {
        $user = $request->user();

        if ($user) {
            Cart::where(['user_id' => $user->id])->delete();
        } else {
            if (session()->has('carts')) { // (Session::has('carts'))
                session()->forget('carts');
                // Session::forget('carts');
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
