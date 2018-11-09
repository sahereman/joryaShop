<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;

class PaymentsController extends Controller
{
    protected function getAlipayConfig()
    {
        return array_merge(config('payment.alipay'), [
            'notify_url' => route('payments.alipay.notify'),
            'return_url' => route('payments.return'),
        ]);
    }

    // GET Alipay 支付 页面
    public function alipay(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id == $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }
        // 调用支付宝的网页支付
        return Pay::alipay($this->getAlipayConfig())->web([
            'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
            'total_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 订单金额，单位元，支持小数点后两位
            'subject' => '请支付来自 Jorya Shop 的订单：' . $order->order_sn, // 订单标题
        ]);
    }

    // POST Alipay 支付通知 [notify_url]
    /**
     * 服务器端回调
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function alipayNotify()
    {
        // 校验输入参数
        $data = Pay::alipay($this->getAlipayConfig())->verify();

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $order = Order::where('order_sn', $data->out_trade_no)->first();

        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$order) {
            return 'fail';
        }

        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给支付宝
            return Pay::alipay($this->getAlipayConfig())->success();
        }

        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING,
            'paid_at' => now(), // 支付时间
            'payment_method' => 'alipay', // 支付方式
            'payment_no' => $data->trade_no, // 支付宝订单号
        ]);

        return Pay::alipay($this->getAlipayConfig())->success();
    }

    // GET 支付回调 [return url]
    /**
     * 前端回调页面
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function paymentReturn()
    {
        try {
            // 校验提交的参数是否合法
            $data = Pay::alipay($this->getAlipayConfig())->verify();
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error($e->getMessage());
            return view('payments.error', [
                'message' => $e->getMessage(),
            ]);
        }

        /*return view('pages.success', [
            'msg' => '付款成功',
        ]);*/
        return view('payments.success', [
            'order' => $data,
        ]);
    }

    // GET WeChat 支付 页面
    public function wechat()
    {
        return view('payments.wechat');
    }

    // GET Paypal 支付 页面
    public function paypal()
    {
        return view('payments.paypal');
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
}
