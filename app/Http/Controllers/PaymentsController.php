<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;

class PaymentsController extends Controller
{
    /*Alipay Payment*/
    protected function getAlipayConfig()
    {
        return array_merge(config('payment.alipay'), [
            'notify_url' => route('payments.alipay.notify'),
            'return_url' => route('payments.alipay.return'),
        ]);
    }

    // GET Alipay 支付页面
    public function alipay(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        // 调用Alipay的电脑支付(网页支付)
        return Pay::alipay($this->getAlipayConfig())->web([
            'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
            'total_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 订单金额，单位元，支持小数点后两位
            'subject' => '请支付来自 Jorya Hair 的订单：' . $order->order_sn, // 订单标题
        ]);
    }

    /**
     * 服务器端回调
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    // POST Alipay 支付通知 [notify_url]
    public function alipayNotify(Request $request)
    {
        // 校验输入参数
        $data = Pay::alipay($this->getAlipayConfig())->verify();

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $order = Order::where('order_sn', $data->out_trade_no)->first();

        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$order) {
            Log::error('Alipay notifies with wrong out_trade_no: ' . $data->out_trade_no);
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
            'payment_sn' => $data->trade_no, // 支付宝订单号
        ]);

        return Pay::alipay($this->getAlipayConfig())->success();
    }

    /**
     * 前端回调页面
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    // GET Alipay 支付回调 [return url]
    public function alipayReturn(Request $request)
    {
        $alipay = Pay::alipay($this->getAlipayConfig());
        try {
            // 校验提交的参数是否合法
            $data = $alipay->verify();
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
        /*return view('payments.success', [
            'order' => $data,
        ]);*/
        return $alipay->success();
    }

    // Alipay 退款
    public function alipayRefund(Request $request, Order $order)
    {
        // 调用支付宝支付实例的 refund 方法
        $response = Pay::alipay($this->getAlipayConfig())->refund([
            'out_trade_no' => $order->order_sn, // 之前的订单流水号
            'refund_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 退款金额，单位元
        ]);

        // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
        if ($response->sub_code) {
            Log::error(json_encode($response));
        }

        // 将退款订单的状态标记为退款成功并保存退款時間
        $order->refund->update([
            'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
            'refunded_at' => now(), // 退款时间
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'response' => $response,
        ]);
    }

    /*Wechat Payment*/
    protected function getWechatConfig()
    {
        return array_merge(config('payment.wechat'), [
            'notify_url' => route('payments.wechat.notify'),
            // 'return_url' => route('payments.wechat.return'),
        ]);
    }

    // GET WeChat 支付 页面
    public function wechat(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        try {
            // 调用Wechat的扫码支付(网页支付)
            $result = Pay::wechat($this->getWechatConfig())->scan([
                'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
                'body' => '请支付来自 Jorya Hair 的订单：' . $order->order_sn, // 订单标题
                'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，参数值不能带小数点
            ]);

            // 二维码内容：
            $qr_code_url = $result->code_url;

            return view('payments.wechat', [
                'order' => $order,
                'qr_code_url' => $qr_code_url,
            ]);
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
    }

    // POST WeChat 支付通知 [notify_url]
    public function wechatNotify(Request $request)
    {
        // 校验输入参数
        $data = Pay::wechat($this->getWechatConfig())->verify();

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $order = Order::where('order_sn', $data->out_trade_no)->first();

        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$order) {
            Log::error('Wechat notifies with wrong out_trade_no: ' . $data->out_trade_no);
            return 'fail';
        }

        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给 Wechat
            return Pay::wechat($this->getWechatConfig())->success();
        }

        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING,
            'paid_at' => now(), // 支付时间
            'payment_method' => 'wechat', // 支付方式
            'payment_sn' => $data->trade_no, // Wechat 订单号
        ]);

        return Pay::wechat($this->getWechatConfig())->success();
    }

    // GET Wechat 前端JS监听订单支付，回调成功|失败页面
    public function wechatReturn(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }

        // 判断当前订单状态是否已经支付成功
        if ($order->status === Order::ORDER_STATUS_SHIPPING) {
            /*return view('pages.success', [
                'msg' => '付款成功',
            ]);*/
            return view('payments.success', [
                'order' => $order,
            ]);
        }

        return;
    }

    // Wechat 退款
    public function wechatRefund(Request $request, Order $order)
    {
        // 调用Wechat支付实例的 refund 方法
        $response = Pay::wechat($this->getWechatConfig())->refund([
            'out_trade_no' => $order->order_sn, // 之前的订单流水号
            'out_refund_no' => $order->refund->refund_sn, // 退款订单流水号
            'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，只能为整数
            'refund_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 退款金额，单位分，只能为整数
            'refund_desc' => '这是来自 Jorya Hair 的退款订单' . $order->refund->refund_sn,
        ]);

        // 根据Wechat的文档，如果返回值里有 sub_code 字段说明退款失败
        if ($response->return_code == 'FAIL') {
            Log::error(json_encode($response));
        }

        // 将退款订单的状态标记为退款成功并保存退款時間
        $order->refund->update([
            'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
            'refunded_at' => now(), // 退款时间
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'response' => $response,
        ]);
    }


    protected function getPaypalConfig()
    {
        return array_merge(config('payment.paypal'), [
            'notify_url' => route('payments.paypal.notify'),
            'return_url' => route('payments.paypal.return'),
        ]);
    }

    // GET Paypal 支付页面
    public function paypal(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        return view('payments.paypal');
    }

    // POST Paypal 支付通知 [notify_url]
    public function paypalNotify(Request $request)
    {
        // TODO ...
    }

    /**
     * 前端回调页面
     */
    // GET Paypal 支付回调 [return url]
    public function paypalReturn(Request $request)
    {
        // TODO ...
    }

    // Paypal 退款
    public function paypalRefund(Request $request, Order $order)
    {
        // TODO ...
    }

    // joryashop.test/payments/get_wechat_open_id
    public function getWechatOpenId(Request $request)
    {
        header("Content-type: text/html; charset=utf-8");
        if (!isset($_GET['code'])) {
            $app_id = config('payment.wechat.app_id'); // 公众号在微信的app_id
            $redirect_uri = route('payments.get_wechat_open_id'); // 要请求的url
            // $scope = 'snsapi_base';
            $scope = 'snsapi_userinfo';
            // $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=wx' . '#wechat_redirect';
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=1' . '#wechat_redirect';
            header("Location:" . $url);
            exit();
        } else {
            $app_id = config('payment.wechat.app_id'); // 公众号在微信的app_id
            $app_secret = config('payment.wechat.app_secret'); // 公众号在微信的app_secret
            // $code = $_GET["code"];
            $code = $request->query('code');
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $app_id . '&secret=' . $app_secret . '&code=' . $code . '&grant_type=authorization_code';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_token_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_array = json_decode($response, true);
            //根据openid和access_token查询用户信息
            $access_token = $response_array['access_token'];
            $openid = $response_array['openid'];

            session(['wechat_mp_userinfo' => $response]);
            // return $response_array;

            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            //解析json
            $user_obj = json_decode($response, true);
            $wechat_mp_userinfo = session('wechat_mp_userinfo');
            dump($wechat_mp_userinfo);
            $_SESSION['user'] = $user_obj;
            print_r($user_obj);
        }
    }

    // GET 通用 - 支付成功页面
    public function success(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if (in_array($order->status, [Order::ORDER_STATUS_PAYING, Order::ORDER_STATUS_CLOSED])) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        return view('payments.success', [
            'order' => $order,
        ]);
    }
}
