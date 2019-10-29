<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\CustomAttr;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductSkuAttrValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CartsController extends Controller
{
    // GET 购物车清单
    public function index(Request $request)
    {
        $user = $request->user();
        $total_amount = 0;
        $attr_values = [];
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
                $carts = $carts->map(function (Cart $cart, $key) use (&$total_amount, &$attr_values) {
                    if ($cart->sku && $cart->sku->product && $cart->sku->product->on_sale) {
                        $total_amount += bcmul($cart->sku->price, $cart->number, 2);
                        /*custom product sku attr value sorting*/
                        $sorted_custom_attr_values = collect();
                        if ($cart->sku->product->type == Product::PRODUCT_TYPE_CUSTOM) {
                            $grouped_custom_attr_values = $cart->sku->custom_attr_values->groupBy('type');
                            foreach (CustomAttr::$customAttrTypeMap as $type) {
                                if (isset($grouped_custom_attr_values[$type])) {
                                    $sorted_custom_attr_values[$type] = $grouped_custom_attr_values[$type];
                                }
                            }
                            $sorted_custom_attr_values = $sorted_custom_attr_values->flatten(1);
                        }
                        $attr_values[$cart->sku_id] = $cart->sku->product->type == Product::PRODUCT_TYPE_CUSTOM ? $sorted_custom_attr_values : $cart->sku->attr_values;
                        /*custom product sku attr value sorting*/
                        return $cart;
                    }
                    $cart->delete();
                });
            }
        } else {
            $carts = [];
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            // 自动清除失效商品[已删除或已下架商品]
            $flag = false;
            foreach ($cart as $product_sku_id => $number) {
                $product_sku = ProductSku::with('product')->find($product_sku_id);
                if (!$product_sku || !$product_sku->product || !$product_sku->product->on_sale) {
                    unset($cart[$product_sku_id]);
                    $flag = true;
                    continue;
                }
                /*custom product sku attr value sorting*/
                $sorted_custom_attr_values = collect();
                if ($product_sku->product->type == Product::PRODUCT_TYPE_CUSTOM) {
                    $grouped_custom_attr_values = $product_sku->custom_attr_values->groupBy('type');
                    foreach (CustomAttr::$customAttrTypeMap as $type) {
                        if (isset($grouped_custom_attr_values[$type])) {
                            $sorted_custom_attr_values[$type] = $grouped_custom_attr_values[$type];
                        }
                    }
                    $sorted_custom_attr_values = $sorted_custom_attr_values->flatten(1);
                }
                $attr_values[$product_sku_id] = $product_sku->product->type == Product::PRODUCT_TYPE_CUSTOM ? $sorted_custom_attr_values : $product_sku->attr_values;
                /*custom product sku attr value sorting*/
                $carts[$product_sku_id]['product_sku_id'] = $product_sku_id;
                $carts[$product_sku_id]['product_sku'] = $product_sku;
                $carts[$product_sku_id]['number'] = $number;
                $total_amount += bcmul($product_sku->price, $number, 2);
            }
            if ($flag) {
                session(['cart' => $cart]);
                // Session::put('cart', $cart);
                // Session::put(['cart' => $cart]);
            }
        }

        return view('carts.index', [
            'carts' => $carts,
            'total_amount' => $total_amount,
            'attr_values' => $attr_values
        ]);
    }

    // POST 加入购物车
    public function store(CartRequest $request)
    {
        $user = $request->user();
        $sku_id = false;
        if ($request->has('sku_id')) {
            $sku_id = $request->input('sku_id');
        } else {
            $product_id = $request->input('product_id');
            $product_sku_attr_values = $request->input('product_sku_attr_values');
        }
        $number = $request->input('number');

        if ($sku_id === false && isset($product_id) && isset($product_sku_attr_values)) {
            $product = Product::find($product_id);
            $product_attrs = $product->attrs;
            $product_attr_names = $product_attrs->pluck('name', 'id')->toArray();
            $product_attr_ids = $product_attrs->pluck('id', 'name')->toArray();
            $product_skus = $product->skus()->with('attr_values')->get();
            $product_sku_ids = $product_skus->pluck('id')->toArray();
            $product_sku_attr_value_collection = ProductSkuAttrValue::whereIn('product_sku_id', $product_sku_ids)->get();
            foreach ($product_sku_attr_values as $product_attr_name => $product_sku_attr_value) {
                if ($product_sku_attr_value && !in_array($product_attr_name, $product_attr_names)) {
                    break;
                }
                if ($product_sku_attr_value) {
                    $product_attr_id = $product_attr_ids[$product_attr_name];
                    $product_sku_ids = $product_sku_attr_value_collection->whereIn('product_sku_id', $product_sku_ids)->where('product_attr_id', $product_attr_id)->where('value', $product_sku_attr_value)->pluck('product_sku_id')->toArray();
                }
                if (!$product_sku_ids) {
                    break;
                }
            }
            if (count($product_sku_ids) > 0) {
                $sku_id = $product_sku_ids[0];
            } else {
                $product_sku = ProductSku::create([
                    'product_id' => $product->id,
                    'name_en' => 'custom product sku of lyrical hair',
                    'name_zh' => 'custom product sku of lyrical hair',
                    'photo' => '',
                    'delta_price' => 0,
                    'stock' => 100, // 暂定数值，占位用，便于客户在购物车内追加购买数量
                    'sales' => 1,
                ]);
                $sku_id = $product_sku->id;
                $count = count($product_sku_attr_values) + 1;
                foreach ($product_sku_attr_values as $product_attr_name => $product_sku_attr_value) {
                    if ($product_sku_attr_value) {
                        $product_attr_id = $product_attr_ids[$product_attr_name];
                        ProductSkuAttrValue::create([
                            'product_sku_id' => $sku_id,
                            'product_attr_id' => $product_attr_id,
                            'value' => $product_sku_attr_value,
                            'sort' => $count - 1,
                        ]);
                    }
                }
            }
        }

        if (!$sku_id) {
            throw new InvalidRequestException('Your request is denied. Please try it again.');
        }

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
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            if (isset($cart[$sku_id])) {
                $cart[$sku_id] += $number;
            } else {
                $cart[$sku_id] = $number;
            }

            session(['cart' => $cart]);
            // Session::put('cart', $cart);
            // Session::put(['cart' => $cart]);
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

        $amount = 0;
        $total_amount = 0;

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
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'product_sku_id' => $sku_id,
                    'number' => $number
                ]);
            }

            $amount = get_current_price(bcmul($cart->sku->price, $number, 2));
            $carts = $user->carts()->with('sku.product')->get();
            if ($carts->isNotEmpty()) {
                $carts->each(function (Cart $cart, $key) use (&$total_amount) {
                    if ($cart->sku->product && $cart->sku->product->on_sale) {
                        $total_amount += bcmul($cart->sku->price, $cart->number, 2);
                        return $cart;
                    }
                    $cart->delete();
                });
            }
        } else {
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            $cart[$sku_id] = $number;

            session(['cart' => $cart]);
            // Session::put('cart', $cart);
            // Session::put(['cart' => $cart]);

            $product_sku = ProductSku::with('product')->find($sku_id);
            $amount = get_current_price(bcmul($product_sku->price, $number, 2));
            // 自动清除失效商品[已删除或已下架商品]
            $flag = false;
            foreach ($cart as $product_sku_id => $number) {
                $product_sku = ProductSku::with('product')->find($product_sku_id);
                if (!$product_sku->product || !$product_sku->product->on_sale) {
                    unset($cart[$product_sku_id]);
                    $flag = true;
                    continue;
                }
                $total_amount += bcmul($product_sku->price, $number, 2);
            }
            if ($flag) {
                session(['cart' => $cart]);
                // Session::put('cart', $cart);
                // Session::put(['cart' => $cart]);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'amount' => $amount,
                'total_amount' => $total_amount
            ]
        ]);
    }

    // DELETE 删除
    public function destroy(CartRequest $request)
    {
        // $this->authorize('delete', $cart);

        $user = $request->user();
        $sku_id = $request->input('sku_id');

        // $total_amount = 0;

        if ($user) {
            $cart = Cart::where([
                'user_id' => $user->id,
                'product_sku_id' => $sku_id
            ])->first();
            if ($cart) {
                $cart->user()->dissociate();
                $cart->delete();
            }

            /*$carts = $user->carts()->with('sku.product')->get();
            if ($carts->isNotEmpty()) {
                $carts->each(function (Cart $cart, $key) use (&$total_amount) {
                    if ($cart->sku->product && $cart->sku->product->on_sale) {
                        $total_amount += bcmul($cart->sku->price, $cart->number, 2);
                        return $cart;
                    }
                    $cart->delete();
                });
            }*/
        } else {
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            if (isset($cart[$sku_id])) {
                unset($cart[$sku_id]);

                session(['cart' => $cart]);
                // Session::put('cart', $cart);
                // Session::put(['cart' => $cart]);
            }

            // 自动清除失效商品[已删除或已下架商品]
            /*$flag = false;
            foreach ($cart as $product_sku_id => $number) {
                $product_sku = ProductSku::with('product')->find($product_sku_id);
                if (!$product_sku->product || !$product_sku->product->on_sale) {
                    unset($cart[$product_sku_id]);
                    $flag = true;
                    continue;
                }
                $total_amount += bcmul($product_sku->price, $number, 2);
            }
            if ($flag) {
                session(['cart' => $cart]);
                // Session::put('cart', $cart);
                // Session::put(['cart' => $cart]);
            }*/
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            /*'data' => [
                'total_amount' => $total_amount
            ]*/
        ]);
    }

    // DELETE 清空购物车
    public function flush(Request $request)
    {
        $user = $request->user();

        if ($user) {
            Cart::where(['user_id' => $user->id])->delete();
        } else {
            if (session()->has('cart')) { // (Session::has('cart'))
                session(['cart' => []]);
                // Session::put('cart', []);
                // Session::put(['cart' => []]);
                // session()->forget('cart');
                // Session::forget('cart');
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
