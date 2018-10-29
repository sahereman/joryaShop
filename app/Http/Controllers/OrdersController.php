<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostOrderCommentRequest;
use App\Http\Requests\PutOrderCommentRequest;
use App\Http\Requests\PostOrderRequest;
use App\Http\Requests\RefundOrderRequest;
use App\Jobs\AutoCloseOrderJob;
use App\Jobs\AutoCompleteOrderJob;
use App\Models\Cart;
use App\Models\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductSku;
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
            case 'paying':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'paying')
                    ->orderByDesc('created_at')
                    ->simplePaginate(2);
                break;
            // 待收货订单
            case 'receiving':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'receiving')
                    ->orderByDesc('shipped_at')
                    ->simplePaginate(2);
                break;
            // 待评价订单
            case 'uncommented':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where(['status' => 'completed', 'commented_at' => null])
                    ->orderByDesc('completed_at')
                    ->simplePaginate(2);
                break;
            // 售后订单
            case 'refunding':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'refunding')
                    ->orderByDesc('updated_at')
                    ->simplePaginate(2);
                break;
            // 已完成订单
            case 'completed':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'completed')
                    ->orderByDesc('completed_at')
                    ->simplePaginate(2);
                break;
            // 默认：all 全部订单
            default:
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->orderByDesc('updated_at')
                    ->simplePaginate(2);
                break;
        }
        $guesses = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
        return view('orders.index', [
            'status' => $status,
            'orders' => $orders,
            'guesses' => $guesses,
        ]);
    }

    // GET 订单详情页面
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        // 订单物流状态
        $order_shipment_information = [];
        if ($order->shipment_company != null && $order->shipment_sn != null) {
            $order_shipment_information = shipment_query($order->shipment_company, $order->shipment_sn);
        }

        return view('orders.show', [
            'order' => $order,
            'order_shipment_information' => $order_shipment_information,
        ]);
    }

    // POST 提交创建订单
    public function store(PostOrderRequest $request)
    {
        //$user = Auth::user();
        $user = $request->user();
        $currency = $request->input('currency');

        // 开启事务
        $order = DB::transaction(function () use ($request, $user, $currency) {

            // 生成子订单信息快照 snapshot
            $skuIds = [];
            $snapshot = [];
            $totalShippingFee = 0;
            $totalAmount = 0;
            if ($request->has('cart_ids')) {
                // 来自购物车的订单
                $cartIds = explode(',', $request->input('cart_ids'));
                foreach ($cartIds as $cartId) {
                    $cart = Cart::find($cartId);
                    $sku = $cart->sku;
                    $price = $sku->getRealPriceByCurrency($currency);
                    $skuIds[] = $sku->id;
                    $snapshot[]['sku_id'] = $sku->id;
                    $snapshot[]['price'] = $price;
                    $snapshot[]['number'] = $cart->number;
                    $totalShippingFee += $sku->getRealShippingFeeByCurrency($currency) * $cart->number;
                    $totalAmount += $price * $cart->number;
                }
                // 删除相关购物车记录
                Cart::destroy($cartIds);
            } else {
                // 来自SKU的订单
                $skuIds[] = $request->input('sku_id');
                $sku = ProductSku::find($request->input('sku_id'));
                $price = $sku->getRealPriceByCurrency($currency);
                $snapshot[]['sku_id'] = $request->input('sku_id');
                $snapshot[]['price'] = $price;
                $snapshot[]['number'] = $request->input('number');
                $totalShippingFee += $sku->getRealShippingFeeByCurrency($currency) * $request->input('number');
                $totalAmount += $price * $request->input('number');
            }

            // 创建一条订单记录
            $order = new Order([
                'user_id' => $user->id,
                'user_info' => collect($request->only(['name', 'phone', 'address']))->toJson(),
                'status' => 'paying',
                'currency' => $request->input('currency'),
                'snapshot' => collect($snapshot)->toJson(),
                'total_shipping_fee' => $totalShippingFee,
                'total_amount' => $totalAmount,
                'remark' => $request->has('remark') ? $request->input('remark') : '',
            ]);

            $order->user()->associate($user);

            $order->save();

            return $order;
        });

        // 分派定时自动关闭订单任务
        $this->dispatch(new AutoCloseOrderJob($order, Config::config('time_to_close_order')));

        return $order;
    }

    // GET 选择支付方式页面
    public function paymentMethod(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

        return view('orders.payment_method', [
            'order' => $order,
        ]);
    }

    // PATCH [主动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    public function close(Order $order)
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

    // PATCH 卖家配送发货 [订单进入待收货状态:status->receiving]
    public function ship(Request $request, Order $order)
    {
        // TODO ...

        // 分派定时自动关闭订单任务
        $this->dispatch(new AutoCompleteOrderJob($order, Config::config('time_to_complete_order') * 3600 * 24));

        return $order;
    }

    // PATCH 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    public function complete(Order $order)
    {
        $this->authorize('complete', $order);

        $order->status = 'completed';
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
            'order_items' => $order->items()->with('sku.product')->get()->groupBy('id'),
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
                'product_id' => $order_item[0]->sku->product->id,
                'composite_index' => $request->input('composite_index')[$order_item_id],
                'description_index' => $request->input('description_index')[$order_item_id],
                'shipment_index' => $request->input('shipment_index')[$order_item_id],
                'content' => $request->input('content')[$order_item_id],
                'photos' => $request->input('photos')[$order_item_id],
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    // GET 查看订单评价
    public function showComment(Request $request, Order $order)
    {
        $this->authorize('show_comment', $order);

        return view('orders.show_comment', [
            'order' => $order,
            'order_items' => $order->items()->with('sku.product')->get()->groupBy('id'),
            'comments' => $order->comments,
        ]);
    }

    // POST 追加订单评价 [可针对某一款产品单独追加评论]
    public function appendComment(PutOrderCommentRequest $request, Order $order)
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
            'product_id' => $order_items[$request->input('order_item_id')][0]->sku->product->id,
            'content' => $request->input('content'),
            'photos' => $request->input('photos'),
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success',
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
        ]);
    }

    // POST 发起退单申请 [订单进入售后状态:status->refunding] [仅退款]
    public function storeRefund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        // TODO ...
    }

    // PUT 更新退单申请 [仅退款]
    public function updateRefund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        // TODO ...
    }

    // GET 退单申请页面 [退货并退款]
    public function refundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        return view('orders.refund_with_shipment', [
            'order' => $order,
            'refund' => $order->refund,
        ]);
    }

    // POST 发起退单申请 [订单进入售后状态:status->refunding] [退货并退款]
    public function storeRefundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        // TODO ...
    }

    // PUT 更新退单申请 [退货并退款]
    public function updateRefundWithShipment(Request $request, Order $order)
    {
        $this->authorize('refund_with_shipment', $order);

        // TODO ...
    }

    // PATCH 撤销退单申请 [订单恢复状态:status->shipping | receiving]
    public function revokeRefund(Request $request, Order $order)
    {
        $this->authorize('revoke_refund', $order);
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
        $order_shipment_information = [];
        if ($order->shipment_company != null && $order->shipment_sn != null) {
            $order_shipment_information = shipment_query($order->shipment_company, $order->shipment_sn);
        }

        return response()->json([
            'order_shipment_information' => $order_shipment_information,
        ]);
    }
}
