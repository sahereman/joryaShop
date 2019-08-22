<?php

namespace App\Http\Controllers;

use App\Events\OrderClosedEvent;
use App\Events\OrderCompletedEvent;
use App\Events\OrderRefundingEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\PostOrderCommentRequest;
use App\Http\Requests\PostOrderRequest;
use App\Http\Requests\RefundOrderRequest;
use App\Http\Requests\RefundOrderWithShipmentRequest;
use App\Jobs\AutoCloseOrderJob;
use App\Models\Cart;
use App\Models\CountryProvince;
use App\Models\Coupon;
use App\Models\CustomAttr;
use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductSku;
use App\Models\ShipmentCompany;
use App\Models\UserAddress;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    // GET 订单列表页面
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->has('status') ? $request->input('status') : 'all';
        $builder = $user->orders();
        // ->with('items.sku.product');
        switch ($status) {
            // 待付款订单
            case Order::ORDER_STATUS_PAYING:
                $builder->where('status', Order::ORDER_STATUS_PAYING)
                    ->orderByDesc('created_at');
                break;
            // 待发货订单
            case Order::ORDER_STATUS_SHIPPING:
                $builder->where('status', Order::ORDER_STATUS_SHIPPING)
                    ->orderByDesc('paid_at');
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
                    // ->with('refund')
                    ->orderByDesc('updated_at');
                break;
            // 已完成订单
            case Order::ORDER_STATUS_COMPLETED:
                $builder->where('status', Order::ORDER_STATUS_COMPLETED)
                    ->orderByDesc('completed_at');
                break;
            // 默认：all 全部订单
            default:
                $builder->orderByDesc('updated_at');
                break;
        }
        $orders = $builder->simplePaginate(5);
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        return view('orders.index', [
            'status' => $status,
            'orders' => $orders,
            'guesses' => $guesses,
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
                if (in_array($order->status, [Order::ORDER_STATUS_PAYING, Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_SHIPPING])) {
                    $order_shipment_traces = [];
                } else if (($order->status == Order::ORDER_STATUS_COMPLETED) || ($order->status == Order::ORDER_STATUS_REFUNDING && $order->refund->status == OrderRefund::ORDER_REFUND_STATUS_REFUNDED) || Carbon::now()->diffInRealSeconds($order->last_queried_at) < 3600 * 6) {
                    $order_shipment_traces = $order->shipment_info;
                } else {
                    $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
                    $order->update([
                        'shipment_info' => $order_shipment_traces,
                        'last_queried_at' => Carbon::now()->toDateTimeString()
                    ]);
                }
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

        return view('orders.show', [
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
                if (in_array($order->status, [Order::ORDER_STATUS_PAYING, Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_SHIPPING])) {
                    $order_shipment_traces = [];
                } else if (($order->status == Order::ORDER_STATUS_COMPLETED) || ($order->status == Order::ORDER_STATUS_REFUNDING && $order->refund->status == OrderRefund::ORDER_REFUND_STATUS_REFUNDED) || Carbon::now()->diffInRealSeconds($order->last_queried_at) < 3600 * 6) {
                    $order_shipment_traces = $order->shipment_info;
                } else {
                    $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
                    $order->update([
                        'shipment_info' => $order_shipment_traces,
                        'last_queried_at' => Carbon::now()->toDateTimeString()
                    ]);
                }
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

        return view('orders.show', [
            'order' => $order,
            'shipment_sn' => $order->shipment_sn,
            'shipment_company' => $shipment_company_name,
            'order_shipment_traces' => $order_shipment_traces,
            'seconds_to_close_order' => $seconds_to_close_order,
            'seconds_to_complete_order' => $seconds_to_complete_order,
        ]);
    }

    // GET 获得当前用户可用的优惠券列表 [for Ajax request]
    public function getAvailableCoupons(PostOrderRequest $request)
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
        } elseif ($request->has('sku_ids')) {
            $sku_ids = explode(',', $request->query('sku_ids'));
            foreach ($sku_ids as $key => $sku_id) {
                if ($user) {
                    $cart = Cart::where(['user_id' => $user->id, 'product_sku_id' => $sku_id])->first();
                    if (!$cart) {
                        // array_forget($sku_ids, $key);
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
                } else {
                    $cart = session('cart', []);
                    // $cart = Session::get('cart', []);
                    if (!isset($cart[$sku_id])) {
                        continue;
                    }
                    $number = $cart[$sku_id];
                    $sku = ProductSku::with('product')->find($sku_id);
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
        }
        $total_fee = bcadd($total_amount, $total_shipping_fee, 2);

        if ($is_nil) {
            return response()->json([
                'total_amount' => $total_amount,
                'total_shipping_fee' => $total_shipping_fee,
                'total_fee' => $total_fee,
                'available_coupons' => [],
                'saved_fees' => []
            ]);
        }

        $available_coupons = [];
        if ($user) {
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
        }

        $saved_fees = [];
        foreach ($available_coupons as $user_coupon) {
            $user_coupon_id = $user_coupon->id;
            $saved_fees[$user_coupon_id] = 0;
            $proto_coupon = $user_coupon->proto_coupon;
            if ($proto_coupon->type == Coupon::COUPON_TYPE_REDUCTION) {
                $saved_fees[$user_coupon_id] = $proto_coupon->reduction;
            } else if ($proto_coupon->type == Coupon::COUPON_TYPE_DISCOUNT) {
                foreach ($items as $item) {
                    if (in_array($item['product']->type, $proto_coupon->supported_product_types)) {
                        $saved_fees[$user_coupon_id] += bcmul(bcadd($item['amount'], $item['shipping_fee'], 2), $proto_coupon->discount, 2);
                    }
                }
            }
        }
        asort($saved_fees, SORT_NUMERIC);

        return response()->json([
            'total_amount' => $total_amount,
            'total_shipping_fee' => $total_shipping_fee,
            'total_fee' => $total_fee,
            'available_coupons' => $available_coupons,
            'saved_fees' => $saved_fees
        ]);
    }

    // GET 选择地址+币种页面
    public function prePayment(PostOrderRequest $request)
    {
        $user = $request->user();
        $saved_fee = 0;
        $total_amount = 0;
        $total_shipping_fee = 0;
        $items = [];
        $is_nil = true;
        $attr_values = [];
        $product_types = [];

        $countries = [];
        $provinces = [];
        CountryProvince::with('children')->where(['parent_id' => 0, 'type' => 'country'])->get()->each(function (CountryProvince $countryProvince) use (&$countries, &$provinces) {
            if (!in_array($countryProvince->name_en, $countries)) {
                $countries[] = $countryProvince->name_en;
            }
            if (!isset($provinces[$countryProvince->name_en])) {
                $provinces[$countryProvince->name_en] = [];
            }
            $countryProvince->children()->get()->each(function (CountryProvince $province) use ($countryProvince, &$provinces) {
                if (!in_array($province->name_en, $provinces[$countryProvince->name_en])) {
                    $provinces[$countryProvince->name_en][] = $province->name_en;
                }
            });
        });

        if ($request->has('sku_id') && $request->has('number')) {
            $sku_id = $request->query('sku_id');
            $sku = ProductSku::find($sku_id);
            $product = $sku->product;
            $product_types[] = $product->type;
            $number = $request->query('number');
            $price = $sku->price;
            $discounted_fee = 0;
            $discounts = $product->discounts()->orderBy('number', 'desc')->get();
            foreach ($discounts as $discount) {
                if ($number >= $discount->number) {
                    $price = $discount->price;
                    $discounted_fee = bcsub($sku->price, $discount->price, 2);
                    break;
                }
            }
            /*custom product sku attr value sorting*/
            $sorted_custom_attr_values = collect();
            if ($product->type == Product::PRODUCT_TYPE_CUSTOM) {
                $grouped_custom_attr_values = $sku->custom_attr_values->groupBy('type');
                foreach (CustomAttr::$customAttrTypeMap as $type) {
                    if (isset($grouped_custom_attr_values[$type])) {
                        $sorted_custom_attr_values[$type] = $grouped_custom_attr_values[$type];
                    }
                }
                $sorted_custom_attr_values = $sorted_custom_attr_values->flatten(1);
            }
            $attr_values[$sku_id] = $product->type == Product::PRODUCT_TYPE_CUSTOM ? $sorted_custom_attr_values : $sku->attr_values;
            /*custom product sku attr value sorting*/
            $items[0]['sku'] = $sku;
            $items[0]['product'] = $product;
            $items[0]['number'] = $number;
            $items[0]['price'] = $price;
            $items[0]['amount'] = bcmul($price, $number, 2);
            $items[0]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
            $saved_fee = bcmul($discounted_fee, $number, 2);
            $total_amount = bcmul($price, $number, 2);
            $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
            $is_nil = false;
        } elseif ($request->has('sku_ids')) {
            $sku_ids = explode(',', $request->query('sku_ids'));
            foreach ($sku_ids as $key => $sku_id) {
                if ($user) {
                    $cart = Cart::where(['user_id' => $user->id, 'product_sku_id' => $sku_id])->first();
                    if (!$cart) {
                        // array_forget($sku_ids, $key);
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
                    $price = $sku->price;
                    $discounted_fee = 0;
                    $discounts = $product->discounts()->orderBy('number', 'desc')->get();
                    foreach ($discounts as $discount) {
                        if ($number >= $discount->number) {
                            // $price = $discount->price;
                            $discounted_fee = bcsub($sku->price, $discount->price, 2);
                            break;
                        }
                    }
                    /*custom product sku attr value sorting*/
                    $sorted_custom_attr_values = collect();
                    if ($product->type == Product::PRODUCT_TYPE_CUSTOM) {
                        $grouped_custom_attr_values = $cart->sku->custom_attr_values->groupBy('type');
                        foreach (CustomAttr::$customAttrTypeMap as $type) {
                            if (isset($grouped_custom_attr_values[$type])) {
                                $sorted_custom_attr_values[$type] = $grouped_custom_attr_values[$type];
                            }
                        }
                        $sorted_custom_attr_values = $sorted_custom_attr_values->flatten(1);
                    }
                    $attr_values[$sku_id] = $product->type == Product::PRODUCT_TYPE_CUSTOM ? $sorted_custom_attr_values : $sku->attr_values;
                    /*custom product sku attr value sorting*/
                    $items[$key]['sku'] = $sku;
                    $items[$key]['product'] = $product;
                    $items[$key]['number'] = $number;
                    $items[$key]['price'] = $price;
                    $items[$key]['amount'] = bcmul($price, $number, 2);
                    $items[$key]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
                    $saved_fee += bcmul($discounted_fee, $number, 2);
                    $total_amount += bcmul($price, $number, 2);
                    $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                    $is_nil = false;
                } else {
                    $cart = session('cart', []);
                    // $cart = Session::get('cart', []);
                    if (!isset($cart[$sku_id])) {
                        continue;
                    }
                    $number = $cart[$sku_id];
                    $sku = ProductSku::with('product')->find($sku_id);
                    if ($number > $sku->stock) {
                        throw new InvalidRequestException(trans('basic.orders.Insufficient_sku_stock'));
                    }
                    $product = $sku->product;
                    if (!in_array($product->type, $product_types)) {
                        $product_types[] = $product->type;
                    }
                    $price = $sku->price;
                    $discounted_fee = 0;
                    $discounts = $product->discounts()->orderBy('number', 'desc')->get();
                    foreach ($discounts as $discount) {
                        if ($number >= $discount->number) {
                            $price = $discount->price;
                            $discounted_fee = bcsub($sku->price, $discount->price, 2);
                            break;
                        }
                    }
                    /*custom product sku attr value sorting*/
                    $sorted_custom_attr_values = collect();
                    if ($product->type == Product::PRODUCT_TYPE_CUSTOM) {
                        $grouped_custom_attr_values = $sku->custom_attr_values->groupBy('type');
                        foreach (CustomAttr::$customAttrTypeMap as $type) {
                            if (isset($grouped_custom_attr_values[$type])) {
                                $sorted_custom_attr_values[$type] = $grouped_custom_attr_values[$type];
                            }
                        }
                        $sorted_custom_attr_values = $sorted_custom_attr_values->flatten(1);
                    }
                    $attr_values[$sku_id] = $product->type == Product::PRODUCT_TYPE_CUSTOM ? $sorted_custom_attr_values : $sku->attr_values;
                    /*custom product sku attr value sorting*/
                    $items[$key]['sku'] = $sku;
                    $items[$key]['product'] = $product;
                    $items[$key]['number'] = $number;
                    $items[$key]['price'] = $price;
                    $items[$key]['amount'] = bcmul($price, $number, 2);
                    $items[$key]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
                    $saved_fee += bcmul($discounted_fee, $number, 2);
                    $total_amount += bcmul($price, $number, 2);
                    $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                    $is_nil = false;
                }
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
        $saved_fees = [];
        foreach ($available_coupons as $user_coupon) {
            $user_coupon_id = $user_coupon->id;
            $saved_fees[$user_coupon_id] = 0;
            $proto_coupon = $user_coupon->proto_coupon;
            if ($proto_coupon->type == Coupon::COUPON_TYPE_REDUCTION) {
                $saved_fees[$user_coupon_id] = $proto_coupon->reduction;
            } else if ($proto_coupon->type == Coupon::COUPON_TYPE_DISCOUNT) {
                foreach ($items as $item) {
                    if (in_array($item['product']->type, $proto_coupon->supported_product_types)) {
                        $saved_fees[$user_coupon_id] += bcmul(bcadd($item['amount'], $item['shipping_fee'], 2), $proto_coupon->discount, 2);
                    }
                }
            }
        }
        asort($saved_fees, SORT_NUMERIC);
        /* usage of coupon */

        return view('orders.pre_payment', [
            'items' => $items,
            'address' => $address,
            'total_amount' => $total_amount,
            'total_shipping_fee' => $total_shipping_fee,
            'total_fee' => $total_fee,
            'saved_fee' => $saved_fee,
            'available_coupons' => $available_coupons,
            'saved_fees' => $saved_fees,
            // 'countries' => json_encode($countries),
            'countries' => $countries,
            'provinces' => json_encode($provinces),
            'attr_values' => $attr_values
        ]);
    }

    // POST 提交创建订单
    public function store(PostOrderRequest $request)
    {
        $user = $request->user();
        $currency = $request->has('currency') ? $request->input('currency') : 'USD';
        $coupon_id = $request->has('coupon_id') ? $request->input('coupon_id') : null;

        // 开启事务
        $order = DB::transaction(function () use ($request, $user, $currency, $coupon_id) {

            // 生成子订单信息快照 snapshot
            $snapshot = [];
            $total_shipping_fee = 0;
            $total_amount = 0;
            $saved_fee = 0;
            $discount_saved_fee = 0;
            $is_nil = true;
            $is_coupon_used = false;

            /* usage of coupon */
            $user_coupon = null;
            $coupon = null;
            if ($user) {
                $user_coupon = is_null($coupon_id) ? null : UserCoupon::where([
                    'id' => $coupon_id,
                    'order_id' => null,
                    'used_at' => null
                ])->first();

                if ($user_coupon && $user_coupon->user_id == $user->id) {
                    $coupon = $user_coupon->proto_coupon;
                }
            }
            /* usage of coupon */

            if ($request->has('sku_id') && $request->has('number')) {
                // 来自SKU的订单
                $sku_id = $request->input('sku_id');
                $number = $request->input('number');
                $sku = ProductSku::find($sku_id);
                $product = $sku->product;
                $price = $sku->price;
                $discounted_fee = 0;
                $discounts = $product->discounts()->orderBy('number', 'desc')->get();
                foreach ($discounts as $discount) {
                    if ($number >= $discount->number) {
                        $price = $discount->price;
                        $discounted_fee = bcsub($sku->price, $discount->price, 2);
                        break;
                    }
                }
                $snapshot[0]['sku_id'] = $sku_id;
                $snapshot[0]['price'] = $price;
                $snapshot[0]['number'] = $number;
                $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
                $total_amount = bcmul($price, $number, 2);
                $is_nil = false;
                $discount_saved_fee += bcmul($discounted_fee, $number, 2);

                /* usage of coupon */
                if ($coupon && $coupon->status == Coupon::COUPON_STATUS_USING && $coupon->type == Coupon::COUPON_TYPE_REDUCTION && in_array($product->type, $coupon->supported_product_types) && $coupon->threshold <= bcadd($total_amount, $total_shipping_fee, 2)) {
                    $saved_fee += $coupon->reduction;
                    $is_coupon_used = true;
                }
                if ($coupon && $coupon->status == Coupon::COUPON_STATUS_USING && $coupon->type == Coupon::COUPON_TYPE_DISCOUNT && in_array($product->type, $coupon->supported_product_types) && $coupon->threshold <= bcadd($total_amount, $total_shipping_fee, 2)) {
                    $saved_fee += bcmul(bcadd($total_amount, $total_shipping_fee, 2), $coupon->discount, 2);
                    $is_coupon_used = true;
                }
                /* usage of coupon */
                $saved_fee += $discount_saved_fee;
            } elseif ($request->has('sku_ids')) {
                // 来自购物车的订单
                $sku_ids = explode(',', $request->input('sku_ids'));

                /* usage of coupon */
                $is_product_type_supported = false; // flag variable
                if ($coupon && $coupon->status == Coupon::COUPON_STATUS_USING && $coupon->type == Coupon::COUPON_TYPE_REDUCTION) {
                    $saved_fee = $coupon->reduction;
                    $is_product_type_supported = true;
                    $is_coupon_used = true;
                }
                /* usage of coupon */

                if ($user) {
                    foreach ($sku_ids as $key => $sku_id) {
                        $cart = Cart::where(['user_id' => $user->id, 'product_sku_id' => $sku_id])->first();
                        if (!$cart) {
                            array_forget($sku_ids, $key);
                            continue;
                        }
                        $number = $cart->number;
                        $sku = $cart->sku;
                        if ($number > $sku->stock) {
                            throw new InvalidRequestException(trans('basic.orders.Insufficient_sku_stock'));
                        }
                        $product = $sku->product;
                        $price = $sku->price;
                        $discounted_fee = 0;
                        $discounts = $product->discounts()->orderBy('number', 'desc')->get();
                        if ($discounts->isNotEmpty()) {
                            foreach ($discounts as $discount) {
                                if ($number >= $discount->number) {
                                    $price = $discount->price;
                                    $discounted_fee = bcsub($sku->price, $discount->price, 2);
                                    break;
                                }
                            }
                        }
                        $snapshot[$key]['sku_id'] = $sku->id;
                        $snapshot[$key]['price'] = $price;
                        $snapshot[$key]['number'] = $cart->number;
                        $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                        $total_amount += bcmul($price, $number, 2);
                        $is_nil = false;
                        $discount_saved_fee += bcmul($discounted_fee, $number, 2);

                        /* usage of coupon */
                        if ($coupon && $coupon->status == Coupon::COUPON_STATUS_USING && $coupon->type == Coupon::COUPON_TYPE_DISCOUNT && in_array($product->type, $coupon->supported_product_types)) {
                            $saved_fee += bcmul(bcadd($total_amount, $total_shipping_fee, 2), $coupon->discount, 2);
                            $is_product_type_supported = true;
                            $is_coupon_used = true;
                        }
                        /* usage of coupon */
                    }

                    if ($is_nil == false) {
                        // 删除相关购物车记录
                        Cart::where(['user_id' => $user->id])->whereIn('product_sku_id', $sku_ids)->delete();
                    }
                } else {
                    $cart = session('cart', []);
                    // $cart = Session::get('cart', []);
                    foreach ($sku_ids as $key => $sku_id) {
                        if (!isset($cart[$sku_id])) {
                            array_forget($sku_ids, $key);
                            continue;
                        }
                        $number = $cart[$sku_id];
                        $sku = ProductSku::with('product')->find($sku_id);
                        if ($number > $sku->stock) {
                            throw new InvalidRequestException(trans('basic.orders.Insufficient_sku_stock'));
                        }
                        $product = $sku->product;
                        $price = $sku->price;
                        $discounted_fee = 0;
                        $discounts = $product->discounts()->orderBy('number', 'desc')->get();
                        if ($discounts->isNotEmpty()) {
                            foreach ($discounts as $discount) {
                                if ($number >= $discount->number) {
                                    // $price = $discount->price;
                                    $discounted_fee = bcsub($sku->price, $discount->price, 2);
                                    break;
                                }
                            }
                        }
                        $snapshot[$key]['sku_id'] = $sku->id;
                        $snapshot[$key]['price'] = $price;
                        $snapshot[$key]['number'] = $number;
                        $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                        $total_amount += bcmul($price, $number, 2);
                        $is_nil = false;
                        $discount_saved_fee += bcmul($discounted_fee, $number, 2);

                        /* usage of coupon */
                        if ($coupon && $coupon->status == Coupon::COUPON_STATUS_USING && $coupon->type == Coupon::COUPON_TYPE_DISCOUNT && in_array($product->type, $coupon->supported_product_types)) {
                            $saved_fee += bcmul(bcadd($total_amount, $total_shipping_fee, 2), $coupon->discount, 2);
                            $is_product_type_supported = true;
                            $is_coupon_used = true;
                        }
                        /* usage of coupon */
                    }

                    if ($is_nil == false) {
                        // 删除相关购物车记录
                        foreach ($sku_ids as $sku_id) {
                            unset($cart[$sku_id]);
                        }

                        session(['cart' => $cart]);
                        // Session::put('cart', $cart);
                        // Session::put(['cart' => $cart]);
                    }
                }

                /* usage of coupon */
                if (!$is_product_type_supported || ($coupon && $coupon->threshold > bcadd($total_amount, $total_shipping_fee, 2))) {
                    $saved_fee = $discount_saved_fee;
                    $is_coupon_used = false;
                }
                /* usage of coupon */
            }

            if ($is_nil) {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'request_url' => URL::previous(),
                        'mobile_request_url' => URL::previous(),
                    ],
                ]);
            }

            if ($user) {
                $user_info = UserAddress::find($request->input('address_id'))->only('name', 'phone', 'full_address');
                $user_info['address'] = $user_info['full_address'];
                unset($user_info['full_address']);
            } else {
                $user_info = $request->only('name', 'phone', 'address');
            }

            $rate = $currency == ExchangeRate::USD ? 1 : ExchangeRate::where('currency', $currency)->first()->rate;

            // 创建一条支付记录
            $payment = Payment::create([
                'user_id' => $user ? $user->id : null,
                'currency' => $currency,
                'amount' => bcsub(bcadd($total_amount, $total_shipping_fee, 2), $saved_fee, 2),
                'rate' => $rate
            ]);

            // 创建一条订单记录
            $order = new Order([
                'user_id' => $user ? $user->id : null,
                'payment_id' => $payment->id,
                'user_info' => $user_info,
                'status' => Order::ORDER_STATUS_PAYING,
                'currency' => $currency,
                'snapshot' => collect($snapshot)->toArray(),
                'total_shipping_fee' => $total_shipping_fee,
                'total_amount' => $total_amount,
                'saved_fee' => $saved_fee,
                'rate' => $rate,
                'remark' => $request->has('remark') ? $request->input('remark') : '',
                'to_be_closed_at' => Carbon::now()->addSeconds(Order::getSecondsToCloseOrder())->toDateTimeString(),
            ]);

            $order->save();

            /* usage of coupon */
            if (!is_null($user_coupon) && $is_coupon_used) {
                $user_coupon->update([
                    'order_id' => $order->id,
                    'used_at' => Carbon::now()->toDateTimeString()
                ]);
            }
            /* usage of coupon */

            return $order;
        });

        // 分派定时自动关闭订单任务
        // $this->dispatch(new AutoCloseOrderJob($order, Order::getSecondsToCloseOrder())); // 系统自动关闭订单时间（单位：分钟）
        AutoCloseOrderJob::dispatch($order)->delay(Order::getSecondsToCloseOrder()); // 系统自动关闭订单时间（单位：分钟）

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'order' => $order,
                'request_url' => route('payments.method', [
                    'payment' => $order->payment_id,
                ]),
                /*'mobile_request_url' => route('mobile.orders.payment_method', [
                    'order' => $order->id,
                ]),*/
            ],
        ]);
    }

    // POST 多个订单聚合支付
    public function integrate(PostOrderRequest $request)
    {
        $user = $request->user();
        $order_ids = $request->input('order_ids');
        if ($order_ids && preg_match($order_ids, '/^\d+(\,\d+)*$/')) {
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
    /*public function paymentMethod(Request $request, Order $order)
    {
        $user = $request->user();
        if ($user && $order->user_id) {
            $this->authorize('pay', $order);
        }

        if ($order->paid_at != null && $order->payment_method != null && $order->payment_sn != null) {
            return redirect()->route('payments.success', [
                'order' => $order->id,
            ]);
        }

        return view('orders.payment_method', [
            'order' => $order,
        ]);
    }*/

    // GET 判断订单是否已经支付 [for Ajax request]
    public function isPaid(Request $request, Order $order)
    {
        $this->authorize('view', $order);
        if ($order->status != Order::ORDER_STATUS_PAYING && $order->paid_at != null && $order->payment_sn != null) {
            return response()->json([
                'code' => 200,
                'message' => 'Order is paid already',
                'data' => [
                    'order_id' => $order->id,
                    'is_paid' => true,
                    'request_url' => route('payments.success', ['order' => $order->id]),
                ],
            ]);
        }
        return response()->json([
            'code' => 202,
            'message' => 'Order is not paid yet',
            'data' => [
                'order_id' => $order->id,
                'is_paid' => false,
            ],
        ]);
    }

    // PATCH [主动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    public function close(Request $request, Order $order)
    {
        $this->authorize('close', $order);

        event(new OrderClosedEvent($order));

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // PATCH 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    public function complete(Request $request, Order $order)
    {
        $this->authorize('complete', $order);

        event(new OrderCompletedEvent($order));

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    /*--订单评价--*/
    // GET 创建订单评价
    public function createComment(Request $request, Order $order)
    {
        $this->authorize('store_comment', $order);

        if ($order->comments->isNotEmpty()) {
            return redirect()->route('orders.show_comment', [
                'order' => $order->id,
            ]);
        }

        return view('orders.create_comment', [
            'order' => $order,
            // 'order_items' => $order->items()->with('sku.product')->get(),
        ]);
    }

    // POST 发布订单评价 [每款产品都必须发布评价 + 评分]
    public function storeComment(PostOrderCommentRequest $request, Order $order)
    {
        $this->authorize('store_comment', $order);

        if ($request->input('order_id') != $order->id) {
            return redirect()->back()->withInput();
        }

        $order_items = $order->items()->with('sku.product')->get()->groupBy('id');

        foreach ($order_items as $order_item_id => $order_item) {
            $photos = [];
            if ($request->input('photos')[$order_item_id] != '') {
                $photos = explode(',', $request->input('photos')[$order_item_id]);
                $photos = collect($photos)->toArray();
            }
            ProductComment::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'order_item_id' => $order_item_id,
                'product_id' => $order_item[0]->sku->product->id,
                'composite_index' => $request->input('composite_index')[$order_item_id],
                'description_index' => $request->input('composite_index')[$order_item_id],
                'shipment_index' => $request->input('composite_index')[$order_item_id],
                // 'description_index' => $request->input('description_index')[$order_item_id],
                // 'shipment_index' => $request->input('shipment_index')[$order_item_id],
                'content' => $request->input('content')[$order_item_id],
                'photos' => $photos ?? null,
            ]);
        }

        $order->commented_at = Carbon::now()->toDateTimeString();
        $order->save();

        /*if (\Browser::isMobile()) {
            return redirect()->route('mobile.orders.show_comment', [
                'order' => $order->id,
            ]);
        }*/
        return redirect()->route('orders.show_comment', [
            'order' => $order->id,
        ]);
    }

    // GET 查看订单评价
    public function showComment(Request $request, Order $order)
    {
        $this->authorize('show_comment', $order);

        if ($order->comments->isEmpty()) {
            return redirect()->route('orders.create_comment', [
                'order' => $order->id,
            ]);
        }

        return view('orders.show_comment', [
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

        return view('orders.refund', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
        ]);
    }

    // POST 发起退单申请 [订单进入售后状态:status->refunding] [仅退款]
    public function storeRefund(RefundOrderRequest $request, Order $order)
    {
        $this->authorize('refund', $order);

        // 开启事务
        DB::transaction(function () use ($request, $order) {
            OrderRefund::create([
                'order_id' => $order->id,
                'type' => OrderRefund::ORDER_REFUND_TYPE_REFUND,
                'status' => OrderRefund::ORDER_REFUND_STATUS_CHECKING,
                // 'amount' => $request->input('amount'),
                'remark_from_user' => $request->input('remark_from_user'),
            ]);

            $order->status = Order::ORDER_STATUS_REFUNDING;
            $order->save();
        });

        event(new OrderRefundingEvent($order));

        return redirect()->back();
    }

    // PUT 更新退单申请 [仅退款]
    public function updateRefund(RefundOrderRequest $request, Order $order)
    {
        $this->authorize('refund', $order);

        $updated = false;
        /*if ($request->has('amount')) {
            $order->refund->amount = $request->input('amount');
            $updated = true;
        }*/
        if ($request->has('remark_from_user')) {
            $order->refund->remark_from_user = $request->input('remark_from_user');
            $updated = true;
        }
        if ($request->has('remark_from_seller')) {
            $order->refund->remark_from_seller = $request->input('remark_from_seller');
            $updated = true;
        }

        if ($updated) {
            $order->refund->save();
        }

        return redirect()->back();
    }

    // GET 退单申请页面 [退货并退款]
    public function refundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        /*$shipment_company_name = '';
        if (isset($refund)) {
            $shipment_company_name = ShipmentCompany::codeTransformName($refund->shipment_company);
        }*/

        return view('orders.refund_with_shipment', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
            // 'shipment_company' => $shipment_company_name,
        ]);
    }

    // POST 发起退单申请 [订单进入售后状态:status->refunding] [退货并退款]
    public function storeRefundWithShipment(RefundOrderRequest $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        $photos_for_refund = null;
        if ($request->has('photos_for_refund')) {
            $photos_for_refund = explode(',', $request->input('photos_for_refund'));
            $photos_for_refund = collect($photos_for_refund)->toArray();
        }

        // 开启事务
        DB::transaction(function () use ($request, $order, $photos_for_refund) {
            OrderRefund::create([
                'order_id' => $order->id,
                'type' => OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT,
                'status' => OrderRefund::ORDER_REFUND_STATUS_CHECKING,
                // 'amount' => $request->input('amount'),
                'remark_from_user' => $request->input('remark_from_user'),
                'photos_for_refund' => $photos_for_refund,
            ]);

            $order->status = Order::ORDER_STATUS_REFUNDING;
            $order->save();
        });

        event(new OrderRefundingEvent($order));

        return redirect()->back();
    }

    // PUT 更新退单申请 [退货并退款]
    public function updateRefundWithShipment(RefundOrderWithShipmentRequest $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        $updated = false;
        /*if ($request->has('amount')) {
            $order->refund->amount = $request->input('amount');
            $updated = true;
        }*/
        if ($request->has('seller_info')) {
            $order->refund->seller_info = $request->input('seller_info');
            $updated = true;
        }
        if ($request->has('remark_from_user')) {
            $order->refund->remark_from_user = $request->input('remark_from_user');
            $updated = true;
        }
        if ($request->has('remark_from_seller')) {
            $order->refund->remark_from_seller = $request->input('remark_from_seller');
            $updated = true;
        }
        if ($request->has('remark_for_shipment_from_user')) {
            $order->refund->remark_for_shipment_from_user = $request->input('remark_for_shipment_from_user');
            $updated = true;
        }
        if ($request->has('remark_for_shipment_from_seller')) {
            $order->refund->remark_for_shipment_from_seller = $request->input('remark_for_shipment_from_seller');
            $updated = true;
        }
        if ($request->has('shipment_sn') && $request->has('shipment_company')) {
            $order->refund->shipment_sn = $request->input('shipment_sn');
            $order->refund->shipment_company = $request->input('shipment_company');
            $order->refund->shipped_at = Carbon::now()->toDateTimeString();
            $order->refund->status = OrderRefund::ORDER_REFUND_STATUS_RECEIVING;
            $updated = true;
        }
        if ($request->has('photos_for_refund')) {
            $photos_for_refund = explode(',', $request->input('photos_for_refund'));
            $photos_for_refund = collect($photos_for_refund)->toArray();
            $order->refund->photos_for_refund = $photos_for_refund;
            $updated = true;
        }
        if ($request->has('photos_for_shipment')) {
            $photos_for_shipment = explode(',', $request->input('photos_for_shipment'));
            $photos_for_shipment = collect($photos_for_shipment)->toArray();
            $order->refund->photos_for_shipment = $photos_for_shipment;
            $updated = true;
        }

        if ($updated) {
            $order->refund->save();
        }

        return redirect()->back();
    }

    // PATCH 撤销退单申请 [订单恢复状态:status->shipping | receiving]
    public function revokeRefund(Request $request, Order $order)
    {
        $this->authorize('revoke_refund', $order);

        if ($order->refund->type == OrderRefund::ORDER_REFUND_TYPE_REFUND) {
            $order->status = Order::ORDER_STATUS_SHIPPING;
        } else {
            $order->status = Order::ORDER_STATUS_RECEIVING;
        }

        $order->refund->delete();

        $order->save();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // DELETE 删除订单
    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();
        return response()->json([]);
    }

    /*--通用--*/
    // GET 快递100 API 实时查询订单物流状态
    public function shipmentQuery(Request $request, Order $order)
    {
        // $this->authorize('shipment_query', $order);
        $user = $request->user();
        if (!$user || $user->id != $order->user_id || in_array($order->status, [Order::ORDER_STATUS_PAYING, Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_SHIPPING])) {
            return response()->json([
                'order_shipment_traces' => []
            ]);
        }

        // 订单物流状态
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_company != 'etc' && $order->shipment_sn != null) {
            // 快递鸟(kdniao.com) 即时查询API
            if (($order->status == Order::ORDER_STATUS_COMPLETED) || ($order->status == Order::ORDER_STATUS_REFUNDING && $order->refund->status == OrderRefund::ORDER_REFUND_STATUS_REFUNDED) || Carbon::now()->diffInRealSeconds($order->last_queried_at) < 3600 * 6) {
                $order_shipment_traces = $order->shipment_info;
            } else {
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
                $order->update([
                    'shipment_info' => $order_shipment_traces,
                    'last_queried_at' => Carbon::now()->toDateTimeString()
                ]);
            }
        }

        return response()->json([
            'order_shipment_traces' => $order_shipment_traces,
        ]);
    }
}
