<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\PostOrderRequest;
use App\Http\Requests\RefundOrderRequest;

class OrdersController extends Controller
{
    // GET 订单列表页面
    public function index (OrderRequest $request)
    {
        return view('orders.index', []);
    }

    // GET 订单详情页面
    public function show (Order $order)
    {
        return view('orders.show', [
            'order' => $order,
        ]);
    }

    // GET 创建订单页面
    public function create ()
    {
        return view('orders.create');
    }

    // POST 提交创建订单
    public function store (Request $request)
    {
        // TODO ...
    }

    // GET 选择支付方式页面
    public function paymentMethod (PostOrderRequest $request)
    {
        return view('orders.payment_method', []);
    }

    // PATCH [主动|被动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    public function close (Order $order)
    {
        // TODO ...
    }

    // PATCH 卖家配送发货 [订单进入待收货状态:status->receiving]
    public function ship (Request $request)
    {
        // TODO ...
    }

    // PATCH 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    public function complete (Order $order)
    {
        // TODO ...
    }

    // DELETE 删除订单
    public function destroy (Order $order)
    {
        // TODO ...
    }
}
