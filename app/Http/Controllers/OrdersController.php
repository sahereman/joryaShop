<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostOrderCommentRequest;
use App\Http\Requests\PutOrderCommentRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    // GET 订单列表页面
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->has('status') ? $request->input('status') : 'all';
        switch ($status) {
            // 待付款订单
            case Order::ORDER_STATUS_PAYING:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where('status', Order::ORDER_STATUS_PAYING)
                    ->orderByDesc('created_at')
                    ->simplePaginate(5);
                break;
            // 待发货订单
            case Order::ORDER_STATUS_SHIPPING:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where('status', Order::ORDER_STATUS_SHIPPING)
                    ->orderByDesc('paid_at')
                    ->simplePaginate(5);
                break;
            // 待收货订单
            case Order::ORDER_STATUS_RECEIVING:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where('status', Order::ORDER_STATUS_RECEIVING)
                    ->orderByDesc('shipped_at')
                    ->simplePaginate(5);
                break;
            // 待评价订单
            case Order::ORDER_STATUS_UNCOMMENTED:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where(['status' => Order::ORDER_STATUS_COMPLETED, 'commented_at' => null])
                    ->orderByDesc('completed_at')
                    ->simplePaginate(5);
                break;
            // 售后订单
            case Order::ORDER_STATUS_REFUNDING:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where('status', Order::ORDER_STATUS_REFUNDING)
                    ->orderByDesc('updated_at')
                    ->simplePaginate(5);
                break;
            // 已完成订单
            case Order::ORDER_STATUS_COMPLETED:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->where('status', Order::ORDER_STATUS_COMPLETED)
                    ->orderByDesc('completed_at')
                    ->simplePaginate(5);
                break;
            // 默认：all 全部订单
            default:
                $orders = $user->orders()
                    // ->with('items.sku.product')
                    ->orderByDesc('updated_at')
                    ->simplePaginate(5);
                break;
        }
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
            $shipment_company = ShipmentCompany::where(['code' => $order->shipment_company])->first();
            if ($shipment_company instanceof ShipmentCompany) {
                $shipment_company_name = $shipment_company->name;
                // 快递鸟(kdniao.com) 即时查询API
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
            }
        }

        $order_refund_type = OrderRefund::ORDER_REFUND_TYPE_REFUND;
        if ($order->status == Order::ORDER_STATUS_REFUNDING) {
            $order_refund_type = $order->refund->type;
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
            'order_refund_type' => $order_refund_type,
            'seconds_to_close_order' => $seconds_to_close_order,
            'seconds_to_complete_order' => $seconds_to_complete_order,
        ]);
    }

    // GET 选择地址+币种页面
    public function prePayment(Request $request)
    {
        $this->validate($request, [
            'sku_id' => [
                'bail',
                'required_without:cart_ids',
                'required_with:number',
                'integer',
                'exists:product_skus,id',
                function ($attribute, $value, $fail) {
                    $sku = ProductSku::find($value);
                    if ($sku->product->on_sale == 0) {
                        $fail('该商品已下架');
                    }
                    if ($sku->stock == 0) {
                        $fail('该商品已售罄');
                    }
                    /*if ($sku->stock < $this->input('number')) {
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }*/
                },
            ],
            'number' => [
                'bail',
                'required_without:cart_ids',
                'required_with:sku_id',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $sku = ProductSku::find($request->input('sku_id'));
                    if ($sku->stock < $value) {
                        $fail('该商品库存不足，请重新调整商品购买数量');
                    }
                },
            ],
            'cart_ids' => [
                'bail',
                'required_without_all:sku_id,number',
                'string',
                'regex:/^\d+(\,\d+)*$/',
            ],
        ], [], [
            'sku_id' => '商品SKU-ID',
            'number' => '商品购买数量',
            'cart_ids' => '购物车IDs',
        ]);

        $total_amount = 0;
        $total_amount_en = 0;
        $total_shipping_fee = 0;
        $total_shipping_fee_en = 0;
        $items = [];
        if ($request->has('sku_id') && $request->has('number')) {
            $sku = ProductSku::find($request->query('sku_id'));
            $sku->price_en = ExchangeRate::exchangePriceByCurrency($sku->price, 'USD');
            $product = $sku->product;
            $product->shipping_fee_en = ExchangeRate::exchangePriceByCurrency($product->shipping_fee, 'USD');
            $number = $request->query('number');
            $items[0]['sku'] = $sku;
            $items[0]['product'] = $product;
            $items[0]['number'] = $number;
            $items[0]['amount'] = bcmul($sku->price, $number, 2);
            $items[0]['amount_en'] = bcmul($sku->price_en, $number, 2);
            $items[0]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
            $items[0]['shipping_fee_en'] = bcmul($product->shipping_fee_en, $number, 2);
            $total_amount = bcmul($sku->price, $number, 2);
            $total_amount_en = bcmul($sku->price_en, $number, 2);
            $total_shipping_fee = bcmul($product->shipping_fee, $number, 2);
            $total_shipping_fee_en = bcmul($product->shipping_fee_en, $number, 2);
        } elseif ($request->has('cart_ids')) {
            $cart_ids = explode(',', $request->query('cart_ids'));
            foreach ($cart_ids as $key => $cart_id) {
                $cart = Cart::find($cart_id);
                $number = $cart->number;
                $sku = $cart->sku;
                $sku->price_en = ExchangeRate::exchangePriceByCurrency($sku->price, 'USD');
                $product = $sku->product;
                $product->shipping_fee_en = ExchangeRate::exchangePriceByCurrency($product->shipping_fee, 'USD');
                $items[$key]['sku'] = $sku;
                $items[$key]['product'] = $product;
                $items[$key]['number'] = $number;
                $items[$key]['amount'] = bcmul($sku->price, $number, 2);
                $items[$key]['amount_en'] = bcmul($sku->price_en, $number, 2);
                $items[$key]['shipping_fee'] = bcmul($product->shipping_fee, $number, 2);
                $items[$key]['shipping_fee_en'] = bcmul($product->shipping_fee_en, $number, 2);
                $total_amount += bcmul($sku->price, $number, 2);
                $total_amount_en += bcmul($sku->price_en, $number, 2);
                $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                $total_shipping_fee_en += bcmul($product->shipping_fee_en, $number, 2);
            }
        }
        $total_fee = bcadd($total_amount, $total_shipping_fee, 2);
        $total_fee_en = bcadd($total_amount_en, $total_shipping_fee_en, 2);

        $address = false;
        $userAddress = UserAddress::where('user_id', $request->user()->id);
        if ($userAddress->where('is_default', 1)->exists()) {
            // 默认地址
            $address = $userAddress->where('is_default', 1)
                ->first();
        } elseif ($userAddress->exists()) {
            // 上次使用地址
            $address = $userAddress->latest('last_used_at')
                ->latest('updated_at')
                ->latest()
                ->first();
        }

        return view('orders.pre_payment', [
            'items' => $items,
            'address' => $address,
            'total_amount' => $total_amount,
            'total_amount_en' => $total_amount_en,
            'total_shipping_fee' => $total_shipping_fee,
            'total_shipping_fee_en' => $total_shipping_fee_en,
            'total_fee' => $total_fee,
            'total_fee_en' => $total_fee_en,
        ]);
    }

    // POST 提交创建订单
    public function store(PostOrderRequest $request)
    {
        $user = $request->user();
        $currency = $request->has('currency') ? $request->input('currency') : 'CNY';

        // 开启事务
        $order = DB::transaction(function () use ($request, $user, $currency) {

            // 生成子订单信息快照 snapshot
            $snapshot = [];
            $total_shipping_fee = 0;
            $total_amount = 0;
            if ($request->has('cart_ids')) {
                // 来自购物车的订单
                $cartIds = explode(',', $request->input('cart_ids'));
                foreach ($cartIds as $key => $cartId) {
                    $cart = Cart::find($cartId);
                    $number = $cart->number;
                    $sku = $cart->sku;
                    $product = $sku->product;
                    $price = ExchangeRate::exchangePriceByCurrency($sku->price, $currency);
                    $snapshot[$key]['sku_id'] = $sku->id;
                    $snapshot[$key]['price'] = $price;
                    $snapshot[$key]['number'] = $cart->number;
                    $total_shipping_fee += bcmul($product->shipping_fee, $number, 2);
                    $total_amount += bcmul($price, $number, 2);
                }
                $total_shipping_fee = ExchangeRate::exchangePriceByCurrency($total_shipping_fee, $currency);
                // 删除相关购物车记录
                Cart::destroy($cartIds);
            } else {
                // 来自SKU的订单
                $sku_id = $request->input('sku_id');
                $number = $request->input('number');
                $sku = ProductSku::find($sku_id);
                $product = $sku->product;
                $price = ExchangeRate::exchangePriceByCurrency($sku->price, $currency);
                $snapshot[0]['sku_id'] = $sku_id;
                $snapshot[0]['price'] = $price;
                $snapshot[0]['number'] = $number;
                $total_shipping_fee = ExchangeRate::exchangePriceByCurrency(bcmul($product->shipping_fee, $number, 2), $currency);
                $total_amount = bcmul($price, $number, 2);
            }

            // 创建一条订单记录
            $order = new Order([
                'user_id' => $user->id,
                'user_info' => collect($request->only(['name', 'phone', 'address']))->toArray(),
                'status' => Order::ORDER_STATUS_PAYING,
                'currency' => $currency,
                'snapshot' => collect($snapshot)->toArray(),
                'total_shipping_fee' => $total_shipping_fee,
                'total_amount' => $total_amount,
                'remark' => $request->has('remark') ? $request->input('remark') : '',
                'to_be_close_at' => Carbon::now()->addSeconds(Order::getSecondsToCloseOrder())->toDateTimeString(),
            ]);

            $order->user()->associate($user);

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
            ],
        ]);
    }

    // GET 选择支付方式页面
    public function paymentMethod(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

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
                'status' => 'closed',
                'close_at' => Carbon::now()->toDateTimeString(),
            ]);
            // 恢复 Product & Sku +库存 & -销量
            foreach ($order->items as $item) {
                $item->sku->increment('stock', $item->number);
                $item->sku->decrement('sales', $item->number);
                $item->sku->product->increment('stock', $item->number);
                $item->sku->product->decrement('sales', $item->number);
            }
        });
        return response()->json([]);
    }

    // PATCH 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    public function complete(Request $request, Order $order)
    {
        $this->authorize('complete', $order);

        $order->status = Order::ORDER_STATUS_COMPLETED;
        $order->completed_at = Carbon::now()->toDateTimeString();
        $order->save();
        return response()->json([]);
    }

    /*--订单评价--*/
    // GET 创建订单评价
    public function createComment(Request $request, Order $order)
    {
        $this->authorize('store_comment', $order);

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
            ProductComment::create([
                'parent_id' => 0,
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'order_item_id' => $order_item_id,
                'product_id' => $order_item[0]->sku->product->id,
                'composite_index' => $request->input('composite_index')[$order_item_id],
                'description_index' => $request->input('description_index')[$order_item_id],
                'shipment_index' => $request->input('shipment_index')[$order_item_id],
                'content' => $request->input('content')[$order_item_id],
                'photos' => $request->input('photos')[$order_item_id],
            ]);
        }

        $order->commented_at = Carbon::now()->toDateTimeString();
        $order->save();

        /*return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);*/
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

    // POST 追加订单评价 [可针对某一款产品单独追加评论]
    /*public function appendComment(PutOrderCommentRequest $request, Order $order)
    {
        $this->authorize('append_comment', $order);

        if ($request->input('order_id') != $order->id) {
            return redirect()->back()->withInput();
        }

        $order_items = $order->items()->with('sku.product')->get()->groupBy('id');

        ProductComment::create([
            'parent_id' => $request->input('parent_id'),
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'order_item_id' => $request->input('order_item_id'),
            'product_id' => $order_items[$request->input('order_item_id')][0]->sku->product->id,
            'content' => $request->input('content'),
            'photos' => $request->input('photos'),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }*/

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

        OrderRefund::create([
            'order_id' => $order->id,
            'type' => OrderRefund::ORDER_REFUND_TYPE_REFUND,
            'status' => 'checking',
            // 'amount' => $request->input('amount'),
            'remark_from_user' => $request->input('remark_from_user'),
            // 'photos_for_refund' => $request->has('photos_for_refund') ? $request->input('photos_for_refund') : '',
        ]);

        $order->status = Order::ORDER_STATUS_REFUNDING;
        $order->save();

        return redirect()->route('orders.refund', [
            'order' => $order->id,
        ]);
        /*return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);*/
    }

    // GET 更新退单申请页面 [仅退款]
    /*public function editRefund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        return view('orders.refund', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
            'is_edit' => true,
        ]);
    }*/

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
        /*if ($request->has('photos_for_refund')) {
            $order->refund->photos_for_refund = $request->input('photos_for_refund');
            $updated = true;
        }*/

        if ($updated) {
            $order->refund->save();
        }

        return redirect()->route('orders.refund', [
            'order' => $order->id,
        ]);
        /*return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);*/
    }

    // GET 退单申请页面 [退货并退款]
    public function refundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        $shipment_company_name = $order->shipment_company;
        if ($order->shipment_company != null && $order->shipment_sn != null) {
            $shipment_company = ShipmentCompany::where(['code' => $order->shipment_company])->first();
            if ($shipment_company instanceof ShipmentCompany) {
                $shipment_company_name = $shipment_company->name;
            }
        }

        return view('orders.refund_with_shipment', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
            'shipment_company' => $shipment_company_name,
        ]);
    }

    // POST 发起退单申请 [订单进入售后状态:status->refunding] [退货并退款]
    public function storeRefundWithShipment(RefundOrderRequest $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        OrderRefund::create([
            'order_id' => $order->id,
            'type' => OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT,
            'status' => 'checking',
            // 'amount' => $request->input('amount'),
            'remark_from_user' => $request->input('remark_from_user'),
            'photos_for_refund' => $request->has('photos_for_refund') ? $request->input('photos_for_refund') : '',
        ]);

        $order->status = Order::ORDER_STATUS_REFUNDING;
        $order->save();

        return redirect()->route('orders.refund_with_shipment', [
            'order' => $order->id,
        ]);
        /*return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);*/
    }

    // GET 更新退单申请页面 [退货并退款]
    /*public function editRefundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        return view('orders.refund_with_shipment', [
            'order' => $order,
            'refund' => $order->refund,
            'snapshot' => $order->snapshot,
            'is_edit' => true,
        ]);
    }*/

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
            $order->refund->photos_for_refund = $request->input('photos_for_refund');
            $updated = true;
        }
        if ($request->has('photos_for_shipment')) {
            $order->refund->photos_for_shipment = $request->input('photos_for_shipment');
            $updated = true;
        }

        if ($updated) {
            $order->refund->save();
        }

        return redirect()->route('orders.refund_with_shipment', [
            'order' => $order->id,
        ]);
        /*return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);*/
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
