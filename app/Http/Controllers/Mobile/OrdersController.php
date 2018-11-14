<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
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

        return view('mobile.orders.index');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $status = $request->has('status') ? $request->input('status') : 'all';
        $builder = $user->orders();

        switch ($status)
        {
            // 待付款订单
            case 'paying':
                $builder->where('status', 'paying')
                    ->orderByDesc('created_at');
                break;
            // 待收货订单
            case 'receiving':
                $builder->where('status', 'receiving')
                    ->orderByDesc('shipped_at');
                break;
            // 待评价订单
            case 'uncommented':
                $builder->where(['status' => 'completed', 'commented_at' => null])
                    ->orderByDesc('completed_at');
                break;
            // 售后订单
            case 'refunding':
                $builder->where('status', 'refunding')
                    ->orderByDesc('updated_at');
                break;
            // 已完成订单
            case 'completed':
                $builder->where('status', 'completed')
                    ->orderByDesc('completed_at');
                break;
            // 默认：all 全部订单
            default:
                $builder->orderByDesc('updated_at');
                break;
        }

        $orders = $builder->simplePaginate(5);

        return $orders;
    }

    // GET 订单详情页面
    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        // 订单物流状态
        $shipment_company_name = $order->shipment_company;
        $order_shipment_traces = [];
        if ($order->shipment_company != null && $order->shipment_company != 'etc' && $order->shipment_sn != null)
        {
            $shipment_company = ShipmentCompany::where(['code' => $order->shipment_company])->first();
            if ($shipment_company instanceof ShipmentCompany)
            {
                $shipment_company_name = $shipment_company->name;
                // 快递鸟(kdniao.com) 即时查询API
                $order_shipment_traces = kdniao_shipment_query($order->shipment_company, $order->shipment_sn);
            }
        }

        $order_refund_type = 'refund';
        if ($order->status == Order::ORDER_STATUS_REFUNDING)
        {
            $order_refund_type = $order->refund->type;
        }

        return view('mobile.orders.show', [
            'order' => $order,
            'shipment_sn' => $order->shipment_sn,
            'shipment_company' => $shipment_company_name,
            'order_shipment_traces' => $order_shipment_traces,
            'order_refund_type' => $order_refund_type,
        ]);
    }

}
