<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostOrderRequest;
use App\Http\Requests\RefundOrderRequest;
use App\Jobs\AutoCloseOrderJob;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    ->get();
                break;
            // 待收货订单
            case 'receiving':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'receiving')
                    ->orderByDesc('shipped_at')
                    ->get();
                break;
            // 待评价订单
            case 'uncommented':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where(['status' => 'completed', 'commented_at' => null])
                    ->orderByDesc('completed_at')
                    ->get();
                break;
            // 售后订单
            case 'refunding':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'refunding')
                    ->orderByDesc('updated_at')
                    ->get();
                break;
            // 已完成订单
            case 'completed':
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->where('status', 'completed')
                    ->orderByDesc('completed_at')
                    ->get();
                break;
            // 默认：all 全部订单
            default:
                $orders = $user->orders()
                    ->with('items.sku.product')
                    ->orderByDesc('updated_at')
                    ->get();
                break;
        }
        $guesses = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
        return view('orders.index', [
            'orders' => $orders,
            'guesses' => $guesses,
        ]);
    }

    // GET 订单详情页面
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    // GET 创建订单页面
    public function create(Request $request)
    {
        return view('orders.create');
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
                'user_info' => collect($request->only(['name', 'country_code', 'phone_number', 'address']))->toJson(),
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
        $this->dispatch(new AutoCloseOrderJob($order, config('app.order_ttl')));

        return $order;
    }

    // GET 选择支付方式页面
    public function paymentMethod(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

        return view('orders.payment_method', []);
    }

    // PATCH [主动|被动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    public function close(Order $order)
    {
        $this->authorize('close', $order);

        $order->status = 'closed';
        $order->closed_at = Carbon::now()->toDateTimeString();
        $order->save();
        return response()->json([]);
    }

    // PATCH 卖家配送发货 [订单进入待收货状态:status->receiving]
    public function ship(Request $request, Order $order)
    {
        // TODO ...
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

    public function refund(Request $request, Order $order)
    {
        $this->authorize('refund', $order);

        // TODO ...
    }

    // DELETE 删除订单
    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();
        return response()->json([]);
    }
}
