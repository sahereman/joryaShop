<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\PaymentRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;

class PaymentsController extends Controller
{
    protected function getAlipayConfig(Order $order){
        return array_merge(config('payment.alipay'), [
            'notify_url' => route('payments.alipay.notify'),
            'return_url' => route('payments.alipay.return', $order->id),
        ]);
    }

    // POST Alipay 支付
    public function alipay(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if($request->user()->id == $order->user_id){
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING)
        {
            throw new InvalidRequestException('当前订单状态不正确');
        }
        // 调用支付宝的网页支付
        return Pay::alipay($this->getAlipayConfig($order))->web([
            'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount, // 订单金额，单位元，支持小数点后两位
            'subject' => '支付 Laravel Shop 的订单：' . $order->no, // 订单标题
        ]);
    }

    // POST WeChat 支付
    public function wechat()
    {
        return view('payments.wechat');
    }

    // POST Paypal 支付
    public function paypal()
    {
        return view('payments.paypal');
    }

    // GET 支付成功页面
    public function success(Request $request)
    {
        return view('payments.success');
    }

    // POST Alipay 支付通知 [notify_url]
    public function alipayNotify()
    {
        return view('payments.alipay_notify');
    }

    // POST WeChat 支付通知 [notify_url]
    public function wechatNotify()
    {
        return view('payments.wechat_notify');
    }

    // POST Paypal 支付通知 [notify_url]
    public function paypalNotify()
    {
        return view('payments.paypal_notify');
    }

    /*支付回调 [return_url]*/
    // POST Alipay 支付回调 [return url]
    public function alipayReturn()
    {
        // TODO ...
    }

    // POST WeChat 支付回调 [return url]
    public function wechatReturn()
    {
        // TODO ...
    }

    // POST Paypal 支付回调 [return url]
    public function paypalReturn()
    {
        // TODO ...
    }
}
