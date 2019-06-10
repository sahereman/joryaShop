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
use App\Jobs\AutoCompleteOrderJob;
use App\Models\Cart;
use App\Models\Config;
use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductSku;
use App\Models\ShipmentCompany;
use App\Models\User;
use App\Models\UserAddress;
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

        return view('orders.show', [
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
        // $total_amount_en = 0;
        $total_shipping_fee = 0;
        // $total_shipping_fee_en = 0;
        $items = [];
        $is_nil = true;
        if ($request->has('sku_id') && $request->has('number')) {
            $sku = ProductSku::find($request->query('sku_id'));
            $product = $sku->product;
            $number = $request->query('number');
            $items[0]['sku'] = $sku;
            $items[0]['product'] = $product;
            $items[0]['number'] = $number;
            $items[0]['amount'] = bcmul($sku->price, $number, 2);
            // $items[0]['amount_en'] = bcmul($sku->price_in_usd, $number, 2);
            $items[0]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
            // $items[0]['shipping_fee_en'] = bcmul($product->shipping_fee_in_usd, $number, 2);
            $total_amount = bcmul($sku->price, $number, 2);
            // $total_amount_en = bcmul($sku->price_in_usd, $number, 2);
            $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
            // $total_shipping_fee_en = bcmul($product->shipping_fee_in_usd, $number, 2);
            $is_nil = false;
        } elseif ($request->has('cart_ids')) {
            $cart_ids = explode(',', $request->query('cart_ids', ''));
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
                // $sku->price_in_usd = ExchangeRate::exchangePrice($sku->price, 'USD');
                $product = $sku->product;
                // $product->shipping_fee_in_usd = ExchangeRate::exchangePrice($product->shipping_fee, 'USD');
                $items[$key]['sku'] = $sku;
                $items[$key]['product'] = $product;
                $items[$key]['number'] = $number;
                $items[$key]['amount'] = bcmul($sku->price, $number, 2);
                // $items[$key]['amount_en'] = bcmul($sku->price_in_usd, $number, 2);
                $items[$key]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
                // $items[$key]['shipping_fee_en'] = bcmul($product->shipping_fee_in_usd, $number, 2);
                $total_amount += bcmul($sku->price, $number, 2);
                // $total_amount_en += bcmul($sku->price_in_usd, $number, 2);
                $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                // $total_shipping_fee_en += bcmul($product->shipping_fee_in_usd, $number, 2);
                $is_nil = false;
            }
        }
        $total_fee = bcadd($total_amount, $total_shipping_fee, 2);
        // $total_fee_en = bcadd($total_amount_en, $total_shipping_fee_en, 2);

        if ($is_nil) {
            return redirect()->back();
        }

        $address = false;
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

        return view('orders.pre_payment', [
            'items' => $items,
            'address' => $address,
            'total_amount' => $total_amount,
            // 'total_amount_en' => $total_amount_en,
            'total_shipping_fee' => $total_shipping_fee,
            // 'total_shipping_fee_en' => $total_shipping_fee_en,
            'total_fee' => $total_fee,
            // 'total_fee_en' => $total_fee_en,
        ]);
    }

    // GET 选择地址+币种页面
    public function prePaymentBySkuParameters(PostOrderRequest $request)
    {
        $user = $request->user();
        $items = [];

        $base_size = $request->query('base_size');
        $hair_colour = $request->query('hair_colour');
        $hair_density = $request->query('hair_density');
        $product = Product::find($request->query('product_id'));
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

        $number = $request->query('number');
        $items[0]['sku'] = $sku;
        $items[0]['product'] = $product;
        $items[0]['number'] = $number;
        $items[0]['amount'] = bcmul($sku->price, $number, 2);
        // $items[0]['amount_en'] = bcmul($sku->price_in_usd, $number, 2);
        $items[0]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
        // $items[0]['shipping_fee_en'] = bcmul($product->shipping_fee_in_usd, $number, 2);
        $total_amount = bcmul($sku->price, $number, 2);
        // $total_amount_en = bcmul($sku->price_in_usd, $number, 2);
        $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
        // $total_shipping_fee_en = bcmul($product->shipping_fee_in_usd, $number, 2);

        $total_fee = bcadd($total_amount, $total_shipping_fee, 2);
        // $total_fee_en = bcadd($total_amount_en, $total_shipping_fee_en, 2);

        $address = false;
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

        return view('orders.pre_payment', [
            'items' => $items,
            'address' => $address,
            'total_amount' => $total_amount,
            // 'total_amount_en' => $total_amount_en,
            'total_shipping_fee' => $total_shipping_fee,
            // 'total_shipping_fee_en' => $total_shipping_fee_en,
            'total_fee' => $total_fee,
            // 'total_fee_en' => $total_fee_en,
        ]);
    }

    // POST 提交创建订单
    public function store(PostOrderRequest $request)
    {
        $user = $request->user();
        $currency = $request->has('currency') ? $request->input('currency') : 'USD';

        // 开启事务
        $order = DB::transaction(function () use ($request, $user, $currency) {

            // 生成子订单信息快照 snapshot
            $snapshot = [];
            $total_shipping_fee = 0;
            $total_amount = 0;
            $is_nil = true;
            if ($request->has('sku_id') && $request->has('number')) {
                // 来自SKU的订单
                $sku_id = $request->input('sku_id');
                $number = $request->input('number');
                $sku = ProductSku::find($sku_id);
                $product = $sku->product;
                // $price = ($currency == 'CNY') ? $sku->price : $sku->price_in_usd;
                $price = exchange_price($sku->price, $currency);
                $snapshot[0]['sku_id'] = $sku_id;
                $snapshot[0]['price'] = $price;
                $snapshot[0]['number'] = $number;
                // $total_shipping_fee = ($currency == 'CNY') ? bcmul($product->shipping_fee, $number, 2) : bcmul($product->shipping_fee_in_usd, $number, 2);
                $total_shipping_fee = bcmul(exchange_price($product->shipping_fee, $currency), $number, 2);
                $total_amount = bcmul($price, $number, 2);
                $is_nil = false;
            } elseif ($request->has('cart_ids')) {
                // 来自购物车的订单
                $cart_ids = explode(',', $request->input('cart_ids', ''));
                foreach ($cart_ids as $key => $cartId) {
                    $cart = Cart::find($cartId);
                    if ($cart->user_id != $user->id) {
                        array_forget($cart_ids, $key);
                        continue;
                    }
                    $number = $cart->number;
                    $sku = $cart->sku;
                    if ($number > $sku->stock) {
                        throw new InvalidRequestException(trans('basic.orders.Insufficient_sku_stock'));
                    }
                    $product = $sku->product;
                    // $price = ($currency == 'CNY') ? $sku->price : $sku->price_in_usd;
                    $price = exchange_price($sku->price, $currency);
                    $snapshot[$key]['sku_id'] = $sku->id;
                    $snapshot[$key]['price'] = $price;
                    $snapshot[$key]['number'] = $cart->number;
                    // $total_shipping_fee += ($currency == 'CNY') ? bcmul($product->shipping_fee, $number, 2) : bcmul($product->shipping_fee_in_usd, $number, 2);
                    $total_shipping_fee = bcmul(exchange_price($product->shipping_fee, $currency), $number, 2);
                    $total_amount += bcmul($price, $number, 2);
                    $is_nil = false;
                }
                if ($is_nil == false) {
                    // 删除相关购物车记录
                    Cart::destroy($cart_ids);
                }
            }

            if ($is_nil) {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'order' => $user->orders()->latest()->first(),
                        'request_url' => URL::previous(),
                        'mobile_request_url' => URL::previous(),
                    ],
                ]);
            }

            $user_info = UserAddress::find($request->input('address_id'))->only('name', 'phone', 'full_address');
            $user_info['address'] = $user_info['full_address'];
            unset($user_info['full_address']);
            // 创建一条订单记录
            $order = new Order([
                'user_id' => $user->id,
                // 'user_info' => UserAddress::select(['name', 'phone', 'address',])->find($request->input('address_id'))->toArray(),
                'user_info' => $user_info,
                'status' => Order::ORDER_STATUS_PAYING,
                'currency' => $currency,
                'snapshot' => collect($snapshot)->toArray(),
                'total_shipping_fee' => $total_shipping_fee,
                'total_amount' => $total_amount,
                'remark' => $request->has('remark') ? $request->input('remark') : '',
                'to_be_closed_at' => Carbon::now()->addSeconds(Order::getSecondsToCloseOrder())->toDateTimeString(),
            ]);

            // $order->user()->associate($user);

            $order->save();

            return $order;
        });

        // 分派定时自动关闭订单任务
        $this->dispatch(new AutoCloseOrderJob($order, Order::getSecondsToCloseOrder())); // 系统自动关闭订单时间（单位：分钟）

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'order' => $order,
                'request_url' => route('orders.payment_method', [
                    'order' => $order->id,
                ]),
                'mobile_request_url' => route('mobile.orders.payment_method', [
                    'order' => $order->id,
                ]),
            ],
        ]);
    }

    // POST 提交创建订单
    public function storeBySkuParameters(PostOrderRequest $request)
    {
        $user = $request->user();
        $currency = $request->has('currency') ? $request->input('currency') : 'USD';

        // 开启事务
        $order = DB::transaction(function () use ($request, $user, $currency) {

            // 生成子订单信息快照 snapshot
            $snapshot = [];

            // 来自SKU的订单
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

            $sku_id = $sku->id;
            $number = $request->input('number');
            $sku = ProductSku::find($sku_id);
            $product = $sku->product;
            // $price = ($currency == 'CNY') ? $sku->price : $sku->price_in_usd;
            $price = exchange_price($sku->price, $currency);
            $snapshot[0]['sku_id'] = $sku_id;
            $snapshot[0]['price'] = $price;
            $snapshot[0]['number'] = $number;
            // $total_shipping_fee = ($currency == 'CNY') ? bcmul($product->shipping_fee, $number, 2) : bcmul($product->shipping_fee_in_usd, $number, 2);
            $total_shipping_fee = bcmul(exchange_price($product->shipping_fee, $currency), $number, 2);
            $total_amount = bcmul($price, $number, 2);

            $user_info = UserAddress::find($request->input('address_id'))->only('name', 'phone', 'full_address');
            $user_info['address'] = $user_info['full_address'];
            unset($user_info['full_address']);
            // 创建一条订单记录
            $order = new Order([
                'user_id' => $user->id,
                // 'user_info' => UserAddress::select(['name', 'phone', 'address',])->find($request->input('address_id'))->toArray(),
                'user_info' => $user_info,
                'status' => Order::ORDER_STATUS_PAYING,
                'currency' => $currency,
                'snapshot' => collect($snapshot)->toArray(),
                'total_shipping_fee' => $total_shipping_fee,
                'total_amount' => $total_amount,
                'remark' => $request->has('remark') ? $request->input('remark') : '',
                'to_be_closed_at' => Carbon::now()->addSeconds(Order::getSecondsToCloseOrder())->toDateTimeString(),
            ]);

            // $order->user()->associate($user);

            $order->save();

            return $order;
        });

        // 分派定时自动关闭订单任务
        $this->dispatch(new AutoCloseOrderJob($order, Order::getSecondsToCloseOrder())); // 系统自动关闭订单时间（单位：分钟）

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'order' => $order,
                'request_url' => route('orders.payment_method', [
                    'order' => $order->id,
                ]),
                'mobile_request_url' => route('mobile.orders.payment_method', [
                    'order' => $order->id,
                ]),
            ],
        ]);
    }

    // GET 选择支付方式页面
    public function paymentMethod(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

        if ($order->paid_at != null && $order->payment_method != null && $order->payment_sn != null) {
            return redirect()->route('payments.success', [
                'order' => $order->id,
            ]);
        }

        return view('orders.payment_method', [
            'order' => $order,
        ]);
    }

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

        // 通过事务执行 sql
        DB::transaction(function () use ($order) {
            // 将订单的 status 字段标记为 closed，即关闭订单
            $order->update([
                'status' => Order::ORDER_STATUS_CLOSED,
                'close_at' => Carbon::now()->toDateTimeString(),
            ]);
        });

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

        // 通过事务执行 sql
        DB::transaction(function () use ($order) {
            // 将订单的 status 字段标记为 completed，即确认订单
            $order->update([
                'status' => Order::ORDER_STATUS_COMPLETED,
                'completed_at' => Carbon::now()->toDateTimeString(),
            ]);
        });
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
                'description_index' => $request->input('description_index')[$order_item_id],
                'shipment_index' => $request->input('shipment_index')[$order_item_id],
                'content' => $request->input('content')[$order_item_id],
                'photos' => $photos ?? null,
            ]);
        }

        $order->commented_at = Carbon::now()->toDateTimeString();
        $order->save();

        if (\Browser::isMobile()) {
            return redirect()->route('mobile.orders.show_comment', [
                'order' => $order->id,
            ]);
        }
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
        $this->authorize('shipment_query', $order);

        // 订单物流状态
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_sn != null) {
            // 快递鸟(kdniao.com) 即时查询API
            $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
        }

        return response()->json([
            'order_shipment_traces' => $order_shipment_traces,
        ]);
    }
}
