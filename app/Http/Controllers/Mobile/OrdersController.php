<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostOrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductSku;
use App\Models\ShipmentCompany;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    // GET 订单列表页面
    public function index(Request $request)
    {
        return view('mobile.orders.index');
    }

    // GET 获取订单数据 请求 [for Ajax request]
    public function more(Request $request)
    {
        $user = Auth::user();
        $status = $request->has('status') ? $request->input('status') : 'all';
        $builder = $user->orders();

        switch ($status) {
            // 待付款订单
            case Order::ORDER_STATUS_PAYING:
                $builder->where('status', Order::ORDER_STATUS_PAYING)
                    ->orderByDesc('created_at');
                break;
            // 待发货订单
            case Order::ORDER_STATUS_SHIPPING:
                $builder = $builder->where('status', Order::ORDER_STATUS_SHIPPING)
                    ->orderByDesc('paid_at')
                    ->simplePaginate(5);
                break;
            // 待收货订单
            case Order::ORDER_STATUS_RECEIVING:
                $builder->where('status', Order::ORDER_STATUS_RECEIVING)
                    ->orderByDesc('shipped_at');
                break;
            // 待评价订单
            case Order::ORDER_STATUS_UNCOMMENTED:
                $builder->where(['status' => Order::ORDER_STATUS_COMPLETED, 'commented_at' => null])
                    ->orderByDesc('completed_at');
                break;
            // 售后订单
            case Order::ORDER_STATUS_REFUNDING:
                $builder->where('status', Order::ORDER_STATUS_REFUNDING)
                    ->with('refund')
                    ->orderByDesc('updated_at');
                break;
            // 已完成订单
            case Order::ORDER_STATUS_COMPLETED:
                $builder->where('status', Order::ORDER_STATUS_COMPLETED)
                    ->orderByDesc('completed_at');
                break;
            // 默认：all 全部订单
            default:
                $builder->with('refund')
                    ->orderByDesc('updated_at');
                break;
        }

        $orders = $builder->simplePaginate(5);

        // return $orders;
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'orders' => $orders,
            ],
        ]);
    }

    // GET 订单详情页面
    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        // 订单物流状态
        $shipment_company_name = $order->shipment_company;
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_company != 'etc' && $order->shipment_sn != null) {
            $shipment_companies = ShipmentCompany::shipmentCompanies()->pluck('name', 'code');
            if (isset($shipment_companies[$order->shipment_company])) {
                $shipment_company_name = $shipment_companies[$order->shipment_company];
                // 快递鸟(kdniao.com) 即时查询API
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
            }
        }

        $seconds_to_close_order = 0;
        $seconds_to_complete_order = 0;
        if ($order->status == Order::ORDER_STATUS_PAYING) {
            $seconds_to_close_order = strtotime($order->created_at) + Order::getSecondsToCloseOrder() - time();
            if ($seconds_to_close_order < 0) {
                $seconds_to_close_order = 0;
            }
        }
        if ($order->status == Order::ORDER_STATUS_RECEIVING) {
            $seconds_to_complete_order = strtotime($order->shipped_at) + Order::getSecondsToCompleteOrder() - time();
            if ($seconds_to_complete_order < 0) {
                $seconds_to_complete_order = 0;
            }
        }

        return view('mobile.orders.show', [
            'order' => $order,
            'shipment_sn' => $order->shipment_sn,
            'shipment_company' => $shipment_company_name,
            'order_shipment_traces' => $order_shipment_traces,
            'seconds_to_close_order' => $seconds_to_close_order,
            'seconds_to_complete_order' => $seconds_to_complete_order,
        ]);
    }

    // GET 根据订单序列号查看订单详情
    public function searchBySn(Request $request, string $sn)
    {
        $user = $request->user();
        $order = Order::where('order_sn', $sn)->first();
        if (!$order || ($user && $order->user_id)) {
            throw new InvalidRequestException('You access to this resource is denied.');
        }

        // 订单物流状态
        $shipment_company_name = $order->shipment_company;
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_company != 'etc' && $order->shipment_sn != null) {
            $shipment_companies = ShipmentCompany::shipmentCompanies()->pluck('name', 'code');
            if (isset($shipment_companies[$order->shipment_company])) {
                $shipment_company_name = $shipment_companies[$order->shipment_company];
                // 快递鸟(kdniao.com) 即时查询API
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
            }
        }

        $seconds_to_close_order = 0;
        $seconds_to_complete_order = 0;
        if ($order->status == Order::ORDER_STATUS_PAYING) {
            $seconds_to_close_order = strtotime($order->created_at) + Order::getSecondsToCloseOrder() - time();
            if ($seconds_to_close_order < 0) {
                $seconds_to_close_order = 0;
            }
        }
        if ($order->status == Order::ORDER_STATUS_RECEIVING) {
            $seconds_to_complete_order = strtotime($order->shipped_at) + Order::getSecondsToCompleteOrder() - time();
            if ($seconds_to_complete_order < 0) {
                $seconds_to_complete_order = 0;
            }
        }

        return view('mobile.orders.show', [
            'order' => $order,
            'shipment_sn' => $order->shipment_sn,
            'shipment_company' => $shipment_company_name,
            'order_shipment_traces' => $order_shipment_traces,
            'seconds_to_close_order' => $seconds_to_close_order,
            'seconds_to_complete_order' => $seconds_to_complete_order,
        ]);
    }

    // GET 选择地址+币种页面
    public function prePayment(PostOrderRequest $request)
    {
        $user = $request->user();
        $total_amount = 0;
        $total_shipping_fee = 0;
        $items = [];
        $is_nil = true;
        $product_types = [];

        if ($request->has('sku_id') && $request->has('number')) {
            $sku = ProductSku::find($request->query('sku_id'));
            $product = $sku->product;
            $product_types[] = $product->type;
            $number = $request->query('number');
            $items[0]['sku'] = $sku;
            $items[0]['product'] = $product;
            $items[0]['number'] = $number;
            $items[0]['amount'] = bcmul($sku->price, $number, 2);
            $items[0]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
            $total_amount = bcmul($sku->price, $number, 2);
            $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
            $is_nil = false;
        } elseif ($user && $request->has('cart_ids')) {
            $cart_ids = explode(',', $request->query('cart_ids'));
            foreach ($cart_ids as $key => $cart_id) {
                $cart = Cart::find($cart_id);
                if ($cart->user_id != $user->id) {
                    // array_forget($cart_ids, $key);
                    continue;
                }
                $number = $cart->number;
                $sku = $cart->sku;
                if ($number > $sku->stock) {
                    throw new InvalidRequestException(trans('basic.orders.Insufficient_sku_stock'));
                }
                $product = $sku->product;
                if (!in_array($product->type, $product_types)) {
                    $product_types[] = $product->type;
                }
                $items[$key]['sku'] = $sku;
                $items[$key]['product'] = $product;
                $items[$key]['number'] = $number;
                $items[$key]['amount'] = bcmul($sku->price, $number, 2);
                $items[$key]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
                $total_amount += bcmul($sku->price, $number, 2);
                $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                $is_nil = false;
            }
        }
        $total_fee = bcadd($total_amount, $total_shipping_fee, 2);

        if ($is_nil) {
            return redirect()->back();
        }

        $address = false;
        $available_coupons = [];
        if ($user) {
            $addresses = $user->addresses()->latest('last_used_at')->latest('updated_at')->latest()->get();
            if ($addresses->isNotEmpty()) {
                if ($addresses->where('is_default', 1)->isNotEmpty()) {
                    // 默认地址
                    $address = $addresses->where('is_default', 1)->first();
                } else {
                    // 上次使用地址
                    $address = $addresses->first();
                }
            }

            /* usage of coupon */
            $user->available_coupons->each(function (UserCoupon $userCoupon) use ($total_fee, $product_types, &$available_coupons) {
                if ($userCoupon->proto_coupon->status == Coupon::COUPON_STATUS_USING && $userCoupon->proto_coupon->threshold <= $total_fee) {
                    foreach ($product_types as $product_type) {
                        if (in_array($product_type, $userCoupon->proto_coupon->supported_product_types)) {
                            $available_coupons[] = $userCoupon;
                            break;
                        }
                    }
                }
            });
            /* usage of coupon */
        }

        /* usage of coupon */
        $saved_fee = [];
        foreach ($available_coupons as $user_coupon) {
            $user_coupon_id = $user_coupon->id;
            $saved_fee[$user_coupon_id] = 0;
            $proto_coupon = $user_coupon->proto_coupon;
            if ($proto_coupon->type == Coupon::COUPON_TYPE_REDUCTION) {
                $saved_fee[$user_coupon_id] = $proto_coupon->reduction;
            } else if ($proto_coupon->type == Coupon::COUPON_TYPE_DISCOUNT) {
                foreach ($items as $item) {
                    if (in_array($item['product']->type, $proto_coupon->supported_product_types)) {
                        $saved_fee[$user_coupon_id] += bcmul(bcadd($item['amount'], $item['shipping_fee'], 2), $proto_coupon->discount, 2);
                    }
                }
            }
        }
        asort($saved_fee, SORT_NUMERIC);
        /* usage of coupon */

        return view('mobile.orders.pre_payment', [
            'items' => $items,
            'address' => $address,
            'total_amount' => $total_amount,
            // 'total_amount_en' => $total_amount_en,
            'total_shipping_fee' => $total_shipping_fee,
            // 'total_shipping_fee_en' => $total_shipping_fee_en,
            'total_fee' => $total_fee,
            // 'total_fee_en' => $total_fee_en,
            'available_coupons' => $available_coupons,
            'saved_fee' => $saved_fee
        ]);
    }

    // POST 多个订单聚合支付
    public function integrate(PostOrderRequest $request)
    {
        $user = $request->user();
        $order_ids = $request->input('order_ids');
        if ($order_ids && preg_match($order_ids, '/\d+(,\d+)*/')) {
            $order_ids = explode(',', $order_ids);
            if ($user) {
                $orders = Order::where('user_id', $user->id)->whereIn('id', $order_ids)->get()->filter(function (Order $order) {
                    return $order->status == Order::ORDER_STATUS_PAYING;
                });
            } else {
                $orders = Order::whereNull('user_id')->whereIn('id', $order_ids)->get()->filter(function (Order $order) {
                    return $order->status == Order::ORDER_STATUS_PAYING;
                });
            }
            if ($orders->isNotEmpty()) {
                $amount = 0;
                $currency = $orders->first()->currency;
                $is_currency_consistent = true;
                $orders->each(function (Order $order) use (&$amount, $currency, &$is_currency_consistent) {
                    $amount += bcsub(bcadd($order->total_amount, $order->total_shipping_fee, 2), $order->saved_fee, 2);
                    if ($order->currency != $currency) {
                        $is_currency_consistent = false;
                    }
                });
                if ($is_currency_consistent == false) {
                    return redirect()->back()->withErrors([
                        'orders' => ['Please make sure that the orders are paid at the same currency']
                    ]);
                }
                $rate = $currency == ExchangeRate::USD ? 1 : ExchangeRate::where('currency', $currency)->first()->rate;
                $payment = Payment::create([
                    'user_id' => $user ? $user->id : null,
                    'currency' => $currency,
                    'amount' => $amount,
                    'rate' => $rate
                ]);
                $payment_id = $payment->id;
                $orders->each(function (Order $order) use ($payment_id) {
                    $order->update([
                        'payment_id' => $payment_id
                    ]);
                });
                return redirect()->route('payments.method', [
                    'payment' => $payment_id
                ]);
            }
        }
        return redirect()->back()->withErrors([
            'order_ids' => ['Please select at least one of your orders']
        ]);
    }

    // GET 选择支付方式页面
    public function paymentMethod(Request $request, Order $order)
    {
        $user = $request->user();
        if ($user && $order->user_id) {
            $this->authorize('pay', $order);
        }

        if ($order->paid_at != null && $order->payment_method != null && $order->payment_sn != null) {
            return redirect()->route('mobile.payments.success', [
                'order' => $order->id,
            ]);
        }

        return view('mobile.orders.payment_method', [
            'order' => $order,
        ]);
    }

    // GET 物流详情 页面
    public function showShipment(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        // 订单物流状态
        $shipment_company_name = $order->shipment_company;
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_company != 'etc' && $order->shipment_sn != null) {
            $shipment_companies = ShipmentCompany::shipmentCompanies()->pluck('name', 'code');
            if (isset($shipment_companies[$order->shipment_company])) {
                $shipment_company_name = $shipment_companies[$order->shipment_company];
                // 快递鸟(kdniao.com) 即时查询API
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
            }
        }

        return view('mobile.orders.show_shipment', [
            'order' => $order,
            'shipment_sn' => $order->shipment_sn,
            'shipment_company' => $shipment_company_name,
            'order_shipment_traces' => $order_shipment_traces,
        ]);
    }

    // GET 创建订单评价
    public function createComment(Request $request, Order $order)
    {
        $this->authorize('store_comment', $order);

        if ($order->comments->isNotEmpty()) {
            return redirect()->route('mobile.orders.show_comment', [
                'order' => $order->id,
            ]);
        }

        return view('mobile.orders.create_comment', [
            'order' => $order,
            // 'order_items' => $order->items()->with('sku.product')->get(),
        ]);
    }

    // GET 查看订单评价
    public function showComment(Request $request, Order $order)
    {
        $this->authorize('show_comment', $order);

        if ($order->comments->isEmpty()) {
            return redirect()->route('mobile.orders.create_comment', [
                'order' => $order->id,
            ]);
        }

        return view('mobile.orders.show_comment', [
            'user' => $request->user(),
            'order' => $order,
            // 'order_items' => $order->items()->with('sku.product')->get(),
            'comments' => $order->comments->groupBy('order_item_id'),
        ]);
    }

    /*--售后订单--*/
    // GET 退单申请页面 [仅退款]
    public function refund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        return view('mobile.orders.refund', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
        ]);
    }

    // GET 退单申请页面 [退货并退款]
    public function refundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        /*$shipment_company_name = '';
        if (isset($refund)) {
            $shipment_company_name = ShipmentCompany::codeTransformName($refund->shipment_company);
        }*/

        return view('mobile.orders.refund_with_shipment', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
            // 'shipment_company' => $shipment_company_name,
        ]);
    }
}
