<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use Yansongda\Pay\Pay;

class PaymentsController extends Controller
{
    /*Alipay Payment*/
    public static function getAlipayConfig(Order $order)
    {
        return array_merge(config('payment.alipay'), [
            'notify_url' => route('payments.alipay.notify', ['order' => $order->id]),
            'return_url' => route('payments.alipay.return', ['order' => $order->id]),
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

        Log::info('A New Alipay Pc-Web Payment Created: order id - ' . $order->id);

        // 调用Alipay的电脑支付(网页支付)
        return Pay::alipay($this->getAlipayConfig($order))->web([
            'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
            'total_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 订单金额，单位元，支持小数点后两位
            'subject' => '请支付来自 Jorya Hair 的订单：' . $order->order_sn, // 订单标题
        ]);
    }

    // POST Alipay 支付通知 [notify_url]
    /**
     * Sample Verified Data:
     */
    /*{
        "gmt_create": "2018-11-22 13:53:38",
        "charset": "GBK",
        "gmt_payment": "2018-11-22 13:53:44",
        "notify_time": "2018-11-22 13:53:45",
        "subject": "请支付来自 Jorya Hair 的订单：20181122100018412042",
        "sign": "e86+m7dAGOgEm5YgWqZiMPxFuyAXpZs2AvehVHuwP8iO2aAvuXbCTty9R2CA0fbK87+gpuEP8Btadr4Oc/3tPxWovks9dXcNuF4Q28T4kAikaRAI9zamvAdKq2ImT6OQTPTDePNQMjFtjJ7FVumCNxRUisdPGZElE3fXQ+dwqYJzqVwCH1CnUGq6jQC4p/+HAxnOetkLGH80ohXXR+d6370fvGH8W7WDAKuXhIvPHdfZeGveoOKRY6Yrvev8RCrOi1I/LeHFdU+lk8Zk1j0zevZYu9pr/FhEAASYcPWJEuwCduMHYpnmESScGajwqnmTbkO1gRMpn7greZlDBQCkxQ==",
        "buyer_id": "2088802499494690",
        "invoice_amount": "0.01",
        "version": "1.0",
        "notify_id": "2018112200222135345094691026908145",
        "fund_bill_list": "[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]",
        "notify_type": "trade_status_sync",
        "out_trade_no": "20181122100018412042",
        "total_amount": "0.01",
        "trade_status": "TRADE_SUCCESS",
        "trade_no": "2018112222001494691008861677",
        "auth_app_id": "2018112162269732",
        "receipt_amount": "0.01",
        "point_amount": "0.00",
        "app_id": "2018112162269732",
        "buyer_pay_amount": "0.01",
        "sign_type": "RSA2",
        "seller_id": "2088231964255230"
    }*/
    public function alipayNotify(Request $request, Order $order)
    {
        Log::info('Alipay Payment Notification Url: ' . $request->getUri());

        try {
            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给支付宝
                Log::info('A Paid Wechat Payment Notified Again: order id - ' . $order->id);
                return Pay::alipay($this->getAlipayConfig($order))->success();
            }

            // 校验输入参数
            $data = Pay::alipay($this->getAlipayConfig($order))->verify();
            Log::info('A Payment Notification From Alipay With Verified Data: ' . $data->toJson());

            // $data->out_trade_no 拿到订单流水号，并在数据库中查询
            $alipayOrder = Order::where('order_sn', $data->out_trade_no)->first();

            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$alipayOrder || $alipayOrder->id != $order->id) {
                Log::error('Alipay Notified With Wrong Out_Trade_No: ' . $data->out_trade_no);
                return '';
            }

            // 支付通知数据校验 [谨慎起见]
            if ($data->trade_status == 'TRADE_SUCCESS' && $data->total_amount == bcadd($order->total_amount, $order->total_shipping_fee, 2)) {
                $order->update([
                    'status' => Order::ORDER_STATUS_SHIPPING,
                    'paid_at' => Carbon::now()->toDateTimeString(), // 支付时间
                    'payment_method' => Order::PAYMENT_METHOD_ALIPAY, // 支付方式
                    'payment_sn' => $data->trade_no, // 支付宝订单号
                ]);
                Log::info('A New Alipay Payment Completed: order id - ' . $order->id);
            } else {
                Log::error('A New Alipay Payment Failed: order id - ' . $order->id . '; With Wrong Data: ' . $data->toJson());
            }
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Alipay Payment Notification Failed: order id - ' . $order->id . '; With Error Message: ' . $e->getMessage());
        }

        return Pay::alipay($this->getAlipayConfig($order))->success();
    }

    // GET Alipay 支付回调 [return url]
    /**
     * Sample Verified Data:
     */
    /*{
        "charset": "GBK",
        "out_trade_no": "20181122100018412042",
        "method": "alipay.trade.page.pay.return",
        "total_amount": "0.01",
        "sign": "idyslD5/a1EoCa8vgU7iQo97jnWrOTzhgZLWWidFpKUNViobxs3FFqvq2kRfFA2SqCOOKnY1cxy4W7Cqsd0anH/A/VPSCSZc+bhAfKmE/KqOFXlQw2XumOtRJYYB4ozKQbIu7VY+xmK0cml8h53e7MxyUxhoWUoAwqBM+JfiKm9Lj1m5dclD34WbLhYZVOr2E3LXu404hDv/rvfNJwPeQf/7rrY7643/lkYFu1CKN7Y+i1AVaAgIYWFFm+p6CETCYYEX8Pa2T82/rRbo2ZX8epCnqeAVkdXhT3kCtM7dhQ4B46YW/K4w2cRE3DK7strZqeDz8ntzrw9BDJaY69uZqA==",
        "trade_no": "2018112222001494691008861677",
        "auth_app_id": "2018112162269732",
        "version": "1.0",
        "app_id": "2018112162269732",
        "sign_type": "RSA2",
        "seller_id": "2088231964255230",
        "timestamp": "2018-11-22 13:53:52"
    }*/
    public function alipayReturn(Request $request, Order $order)
    {
        Log::info('Alipay Payment Return-Url : ' . $request->getUri());

        try {
            // 校验提交的参数是否合法
            $data = Pay::alipay($this->getAlipayConfig($order))->verify();
            Log::info('Alipay Payment Return With Verified Data: ' . $data->toJson());

            //return $alipay->success();
            /*return view('pages.success', [
                'msg' => '付款成功',
            ]);*/
            return view('payments.success', [
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('Alipay Payment Return Failed: order id - ' . $order->id . '; With Error Message: ' . $e->getMessage());
            return view('payments.error', [
                'order' => $order,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Alipay 退款
    /**
     * Sample Response:
     */
    /*{
        "code": 200,
        "message": "success",
        "response": {
            "code": "10000",
            "msg": "Success",
            "buyer_logon_id": "152****0075",
            "buyer_user_id": "2088802499494690",
            "fund_change": "Y", // 重复发起退款请求时，Y -> N
            "gmt_refund_pay": "2018-11-22 13:54:13",
            "out_trade_no": "20181122100018412042",
            "refund_fee": "0.01",
            "send_back_fee": "0.00",
            "trade_no": "2018112222001494691008861677"
        }
    }*/
    public function alipayRefund(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.exists' => '该订单不存在',
        ], [
            'order_id' => '订单ID',
        ]);
        $order = Order::find($request->input('order_id'));

        // TODO ... (for production)
        /*if ($order->status !== Order::ORDER_STATUS_REFUNDING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }*/

        Log::info('A New Alipay Refund Begins: order refund id - ' . $order->refund->id);

        try {
            // 调用支付宝支付实例的 refund 方法
            $response = Pay::alipay($this->getAlipayConfig($order))->refund([
                'out_trade_no' => $order->order_sn, // 之前的订单流水号
                'refund_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 退款金额，单位元
            ]);

            Log::info('A New Alipay Refund Responded: ' . $response->toJson());

            // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
            if ($response->sub_code || $response->code != 10000 || $response->msg != 'Success') {
                Log::error('A New Alipay Refund Failed: ' . json_encode($response));
            }

            // 将退款订单的状态标记为退款成功并保存退款時間
            $order->refund->update([
                'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
                'refunded_at' => Carbon::now()->toDateTimeString(), // 退款时间
            ]);

            Log::info('A New Alipay Refund Completed: order refund id - ' . $order->refund->id);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Alipay Refund Completed: order refund id - ' . $order->refund->id . '; With Error Message: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /*Wechat Payment*/
    public static function getWechatConfig(Order $order)
    {
        return array_merge(config('payment.wechat'), [
            'notify_url' => route('payments.wechat.notify', ['order' => $order->id]),
        ]);
    }

    // GET WeChat 支付 页面
    /**
     * Sample Response:
     */
    /*{
        "return_code": "SUCCESS",
        "return_msg": "OK",
        "appid": "wx0b3f800e268b1e85",
        "mch_id": "1516915751",
        "nonce_str": "GPkXE1Ys7nwxLsBg",
        "sign": "E985BBC0F922FCB7FBF43DECAD3102E5",
        "result_code": "SUCCESS",
        "prepay_id": "wx22124515680415b6b9fc61ba2782759201",
        "trade_type": "NATIVE",
        "code_url": "weixin://wxpay/bizpayurl?pr=fHd7mdg"
    }*/
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
            $response = Pay::wechat($this->getWechatConfig($order))->scan([
                'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
                'body' => '请支付来自 Jorya Hair 的订单：' . $order->order_sn, // 订单标题
                'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，参数值不能带小数点
            ]);

            if ($response->return_code == 'SUCCESS' && $response->result_code == 'SUCCESS' && $response->return_msg == 'OK' && $response->code_url) {
                // 二维码内容：
                $qr_code_url = $response->code_url;

                Log::info('A New Wechat Pc-Scan Payment Created: ' . $response->toJSON() . '; With Qr Code Url: ' . $qr_code_url);
                return view('payments.wechat', [
                    'order' => $order,
                    'qr_code_url' => $qr_code_url,
                ]);
            } else {
                Log::error('A New Wechat Pc-Scan Payment Responded With Wrong Response: ' . $response->toJSON());
                return view('payments.error', [
                    'order' => $order,
                    'message' => $response->toJson(),
                ]);
            }
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Wechat Pc-Scan Payment Failed: order id - ' . $order->id . '; With Error Message: ' . $e->getMessage());
            return view('payments.error', [
                'order' => $order,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // POST WeChat 支付通知 [notify_url]
    /**
     * Sample Verified Data:
     */
    /*{
        "appid": "wx0b3f800e268b1e85",
        "bank_type": "CFT",
        "cash_fee": "1",
        "fee_type": "CNY",
        "is_subscribe": "N",
        "mch_id": "1516915751",
        "nonce_str": "L8xzvnU1iVBHLOQQ",
        "openid": "owS050gDeK9SQSD0Np1R9DfwZP3Y",
        "out_trade_no": "20181122100018304978",
        "result_code": "SUCCESS",
        "return_code": "SUCCESS",
        "sign": "763FFA33DAEB535AB8C7F85FC8518649",
        "time_end": "20181122124550",
        "total_fee": "1",
        "trade_type": "NATIVE",
        "transaction_id": "4200000228201811224246112891"
    }*/
    public function wechatNotify(Request $request, Order $order)
    {
        Log::info('Wechat Payment Notification Url: ' . $request->getUri());

        try {
            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Wechat
                Log::info('A Paid Wechat Payment Notified Again: order id - ' . $order->id);
                return Pay::wechat($this->getWechatConfig($order))->success();
            }

            // 校验输入参数
            $data = Pay::wechat($this->getWechatConfig($order))->verify();
            Log::info('A Payment Notification From Wechat With Verified Data: ' . $data->toJson());

            // $data->out_trade_no 拿到订单流水号，并在数据库中查询
            $wechatOrder = Order::where('order_sn', $data->out_trade_no)->first();

            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$wechatOrder || $wechatOrder->id != $order->id) {
                Log::error('Wechat Notified With Wrong Out_Trade_No: ' . $data->out_trade_no);
                return '';
            }

            // 支付通知数据校验 [谨慎起见]
            if ($data->result_code == 'SUCCESS' && $data->return_code == 'SUCCESS' && $data->total_fee == bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0)) {
                $order->update([
                    'status' => Order::ORDER_STATUS_SHIPPING,
                    'paid_at' => Carbon::now()->toDateTimeString(), // 支付时间
                    'payment_method' => Order::PAYMENT_METHOD_WECHAT, // 支付方式
                    'payment_sn' => $data->transaction_id, // Wechat 订单号
                ]);
                Log::info('A New Wechat Payment Completed: order id - ' . $order->id);
            } else {
                Log::error('A New Wechat Payment Failed: order id - ' . $order->id . '; With Wrong Data: ' . $data->toJson());
            }
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Wechat Payment Notification Failed: order id - ' . $order->id . '; With Error Message: ' . $e->getMessage());
        }

        return Pay::wechat($this->getWechatConfig($order))->success();
    }

    // Wechat 退款
    /**
     * Sample Response:
     */
    /*{
        "code": 200,
        "message": "success",
        "response": {
            "return_code": "SUCCESS",
            "return_msg": "OK",
            "appid": "wx0b3f800e268b1e85",
            "mch_id": "1516915751",
            "nonce_str": "gkECrULoMEXvArq3",
            "sign": "34559A4E2CEDA2D3D2121900BFE603AD",
            "result_code": "SUCCESS",
            "transaction_id": "4200000228201811224246112891",
            "out_trade_no": "20181122100018304978",
            "out_refund_no": "15f6c3b751e24a5687dc97f4d8bf9dc1",
            "refund_id": "50000308892018112207183007600",
            "refund_channel": [],
            "refund_fee": "1",
            "coupon_refund_fee": "0",
            "total_fee": "1",
            "cash_fee": "1",
            "coupon_refund_count": "0",
            "cash_refund_fee": "1"
        }
    }*/
    public function wechatRefund(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.exists' => '该订单不存在',
        ], [
            'order_id' => '订单ID',
        ]);
        $order = Order::find($request->input('order_id'));

        // TODO ... (for production)
        /*if ($order->status !== Order::ORDER_STATUS_REFUNDING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }*/

        Log::info('A New Wechat Refund Begins: order refund id - ' . $order->refund->id);

        try {
            // 调用Wechat支付实例的 refund 方法
            $response = Pay::wechat($this->getWechatConfig($order))->refund([
                'out_trade_no' => $order->order_sn, // 之前的订单流水号
                'out_refund_no' => $order->refund->refund_sn, // 退款订单流水号
                'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，只能为整数
                'refund_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 退款金额，单位分，只能为整数
                'refund_desc' => '这是来自 Jorya Hair 的退款订单' . $order->refund->refund_sn,
            ]);

            Log::info('A New Wechat Refund Responded: ' . $response->toJson());

            // 根据Wechat的文档，如果返回值里有 sub_code 字段说明退款失败
            if ($response->return_code != 'SUCCESS' || $response->result_code != 'SUCCESS') {
                Log::error('A New Wechat Refund Failed: ' . json_encode($response));
            }

            // 将退款订单的状态标记为退款成功并保存退款時間
            $order->refund->update([
                'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
                'refunded_at' => Carbon::now()->toDateTimeString(), // 退款时间
            ]);

            Log::info('A New Wechat Refund Completed: order refund id - ' . $order->refund->id);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Wechat Refund Completed: order refund id - ' . $order->refund->id . '; With Error Message: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /*Paypal Payment*/
    public static function getPaypalConfig(Order $order)
    {
        return array_merge(config('payment.paypal'), [
            'redirect_urls' => [
                'return_url' => route('payments.paypal.execute', ['order' => $order->id]),
                'cancel_url' => route('payments.paypal.execute', ['order' => $order->id]),
                'notify_url' => route('payments.paypal.notify', ['order' => $order->id]),
            ],
        ]);
        // return config('payment.paypal');
    }

    // GET Paypal: create a new payment
    /**
     * Sample Response:
     */
    /*{
        "intent": "sale",
        "payer": {
            "payment_method": "paypal"
        },
        "transactions": [
            {
                "amount": {
                    "total": "0.01",
                    "currency": "USD"
                },
                "related_resources": [],
                "notify_url": "https://test.joryahair.com/payments/7/paypal/notify"
            }
        ],
        "redirect_urls": {
            "return_url": "https://test.joryahair.com/payments/7/paypal/execute",
            "cancel_url": "https://test.joryahair.com/payments/7/paypal/execute"
        },
        "id": "PAYID-LP3G6EQ7KL05583F3463784B",
        "state": "created",
        "create_time": "2018-11-22T08:55:46Z",
        "links": [
            {
                "href": "https://api.paypal.com/v1/payments/payment/PAYID-LP3G6EQ7KL05583F3463784B",
                "rel": "self",
                "method": "GET"
            },
            {
                "href": "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-97L263940M076301C",
                "rel": "approval_url",
                "method": "REDIRECT"
            },
            {
                "href": "https://api.paypal.com/v1/payments/payment/PAYID-LP3G6EQ7KL05583F3463784B/execute",
                "rel": "execute",
                "method": "POST"
            }
        ]
    }*/
    public function paypalCreate(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        // 判断当前订单状态是否支持支付
        if ($order->status !== Order::ORDER_STATUS_PAYING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }
        // 判断PayPal是否支持当前订单支付币种
        if (in_array($order->currency, ['CNY'])) {
            throw new InvalidRequestException('Paypal暂不支持当前订单支付币种: ' . $order->currency);
        }

        // Step-1: get an access token && create the api context
        $config = $this->getPaypalConfig($order);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        // Step-2: create a new payment
        $payer = new Payer();
        $payer->setPaymentMethod(Order::PAYMENT_METHOD_PAYPAL); // paypal

        $amount = new Amount();
        $totalFee = bcadd($order->total_amount, $order->total_shipping_fee, 2);
        $amount->setTotal($totalFee);
        $amount->setCurrency($order->currency);

        $transaction = new Transaction($apiContext);
        $transaction->setAmount($amount);
        $transaction->setNotifyUrl($config['redirect_urls']['notify_url']);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($config['redirect_urls']['return_url'])
            ->setCancelUrl($config['redirect_urls']['cancel_url']);

        $payment = new Payment($apiContext);
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        try {
            $payment->create($apiContext, $restCall);
            if ($payment->getState() == 'created') {
                $order->update([
                    // 'payment_method' => $payment->getPayer()->getPaymentMethod(), // paypal
                    'payment_method' => Order::PAYMENT_METHOD_PAYPAL,
                    // 'payment_sn' => $payment->getToken(), // token
                    'payment_sn' => $payment->getId(), // paymentId
                ]);
                Log::info("A New Paypal Pc Payment Created: " . $payment->toJSON());
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'payment' => $payment->toArray(),
                        'redirect_url' => $payment->getApprovalLink(),
                    ],
                ]);
            } else {
                Log::error("A New Paypal Pc Payment Creation Failed: " . $payment->toJSON());
                return response()->json([
                    'code' => 400,
                    'message' => 'A New Paypal Pc Payment Creation Failed',
                    'data' => [
                        'payment' => $payment->toArray(),
                        'failure_reason' => $payment->getFailureReason(),
                    ],
                ]);
            }
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error("A New Paypal Pc Payment Creation Failed: order id - " . $order->id . '; With Error Message: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
            /*return view('payments.error', [
                'order' => $order,
                'message' => $e->getMessage(),
            ]);*/
        }
    }

    // GET Paypal: get the info of a payment [Test API]
    public function paypalGet(Request $request, Order $order)
    {
        // 判断订单是否属于当前用户
        if ($request->user()->id !== $order->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }
        if ($order->payment_method !== Order::PAYMENT_METHOD_PAYPAL) {
            throw new InvalidRequestException('This order is not a payment from paypal: payment method - ' . $order->payment_method);
        }
        // 判断PayPal是否支持当前订单支付币种
        if (in_array($order->currency, ['CNY'])) {
            throw new InvalidRequestException('Paypal暂不支持当前订单支付币种: ' . $order->currency);
        }

        $config = $this->getPaypalConfig($order);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        $paymentId = $order->payment_sn;
        $payment = Payment::get($paymentId, $apiContext, $restCall);

        /*$transactions = $payment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();
        $saleId = $sale->getId();

        $payer = $payment->getPayer();
        $payerInfo = $payer->getPayerInfo();
        $payerId = $payerInfo->getPayerId();*/

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'payment' => $payment->toArray(),
                // 'transaction' => $transactions[0]->toArray(),
                // 'sale' => $sale->toArray(),
                // 'payer' => $payer->toArray(),
            ],
        ]);
    }

    // GET Paypal: synchronously execute[approve|cancel] an approved|cancelled PayPal payment. 支付同步通知
    public function paypalExecute(Request $request, Order $order)
    {
        Log::info('Paypal Payment Synchronous Redirection Url: ' . $request->getUri());
        Log::info('An Approved|Cancelled Payment Redirection From Paypal - Synchronously: ' . collect($request->all())->toJson());

        // Step-3: execute an approved PayPal payment.
        if ($request->query('paymentId') && $request->query('token') && $request->query('PayerID')) {
            // Payment approved.
            $paymentId = $request->query('paymentId');
            $token = $request->query('token');
            $payerId = $request->query('PayerID');

            // 拿到订单流水号 payment_sn [$paymentId]，并在数据库中查询
            $paypalOrder = Order::where('payment_method', Order::PAYMENT_METHOD_PAYPAL)
                ->where('payment_sn', $paymentId)
                ->first();
            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$paypalOrder || $paypalOrder->id != $order->id) {
                Log::error('Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token);
                /*return response()->json([
                    'code' => 400,
                    'message' => 'Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token,
                ], 400);*/
                /*return view('pages.error', [
                    'msg' => '付款失败',
                ]);*/
                return view('payments.error', [
                    'order' => $order,
                    'message' => 'Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token,
                ]);
            }

            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: order id - ' . $order->id);
                /*return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);*/
                return view('payments.success', [
                    'order' => $order,
                ]);
            }

            $config = $this->getPaypalConfig($order);
            $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
            $apiContext = new ApiContext($oAuthTokenCredential);
            $apiContext->setConfig($config);
            $restCall = new PayPalRestCall($apiContext);

            $payment = Payment::get($paymentId, $apiContext, $restCall);

            $amount = new Amount();
            $totalFee = bcadd($order->total_amount, $order->total_shipping_fee, 2);
            $amount->setTotal($totalFee);
            $amount->setCurrency($order->currency);

            $transaction = new Transaction();
            $transaction->setAmount($amount);

            $paymentExecution = new PaymentExecution();
            $paymentExecution->setPayerId($payerId);
            $paymentExecution->setTransactions(array($transaction));
            try {
                $payment->execute($paymentExecution, $apiContext, $restCall);
                if ($payment->getState() == 'approved') {
                    $order->update([
                        'payment_method' => Order::PAYMENT_METHOD_PAYPAL,
                        // 'payment_sn' => $payment->getId(),
                        'payment_sn' => $paymentId,
                        'status' => Order::ORDER_STATUS_SHIPPING,
                        'paid_at' => Carbon::now()->toDateTimeString(),
                    ]);
                    Log::info("A New Paypal Payment Executed - Synchronously: " . $payment->toJSON());
                    return view('payments.success', [
                        'order' => $order,
                    ]);
                    /*return response()->json([
                        'code' => 200,
                        'message' => 'Paypal Payment Executed - Synchronously',
                        'data' => [
                            'payment' => $payment->toArray(),
                        ],
                    ]);*/
                } else {
                    Log::info("A New Paypal Pc Payment Execution Failed - Synchronously: " . $payment->toJSON());
                    return view('payments.error', [
                        'order' => $order,
                        'message' => "A New Paypal Pc Payment Execution Failed - Synchronously: " . $payment->toJSON(),
                    ]);
                    /*return response()->json([
                        'code' => 400,
                        'message' => 'A New Paypal Pc Payment Execution Failed - Synchronously',
                        'data' => [
                            'payment' => $payment->toArray(),
                            'failure_reason' => $payment->getFailureReason(),
                        ],
                    ]);*/
                }
            } catch (\Exception $e) {
                // error_log($e->getMessage());
                /*return view('pages.error', [
                    'msg' => '付款失败',
                ]);*/
                Log::error("A New Paypal Pc Payment Execution Failed - Synchronously: order id - " . $order->id . '; With Error Message: ' . $e->getMessage());
                return view('payments.error', [
                    'order' => $order,
                    'message' => "A New Paypal Pc Payment Execution Failed - Synchronously: order id - " . $order->id . '; With Error Message: ' . $e->getMessage(),
                ]);
                /*return response()->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);*/
                /*return view('payments.error', [
                    'order' => $order,
                    'message' => $e->getMessage(),
                ]);*/
            }
        } else {
            // Payment Cancelled.
            $token = $request->query('token');
            if (!$token) {
                /*return response()->json([
                    'code' => 400,
                    'message' => 'PayPal Notified With Wrong Parameters: Cancel Url Without Token - Synchronously',
                    'data' => $request->all(),
                ], 400);*/
                /*return view('pages.error', [
                    'msg' => '付款失败',
                ]);*/
                return view('payments.error', [
                    'order' => $order,
                    'message' => 'PayPal Notified With Wrong Parameters: Cancel Url Without Token - Synchronously',
                ]);
            }

            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: order id - ' . $order->id);
                /*return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);*/
                return view('payments.success', [
                    'order' => $order,
                ]);
            }

            // Do Nothing
            /*$order->update([
                'payment_method' => '',
                'payment_sn' => '',
            ]);*/

            /*return response()->json([
                'code' => 200,
                'message' => 'Paypal Payment Cancelled - Synchronously',
            ]);*/
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            return view('payments.error', [
                'order' => $order,
                'message' => 'Paypal Payment Cancelled - Synchronously',
            ]);
        }
    }

    // POST Paypal 支付异步通知 [notify_url]
    public function paypalNotify(Request $request, Order $order)
    {
        Log::info('Paypal Payment Asynchronous Notification Url: ' . $request->getUri());
        Log::info('An Approved|Cancelled Payment Notification From Paypal - Asynchronously: ' . collect($request->all())->toJson());

        // ### Approval Status
        // Determine if the user approved the payment or not
        if ($request->input('payment_status') == 'Completed' && $request->input('payer_status') == 'verified') {
            // Payment approved.
            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Asynchronously: order id - ' . $order->id);
                return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Asynchronously',
                ]);
            }

            $order->update([
                'payment_method' => Order::PAYMENT_METHOD_PAYPAL,
                // 'payment_sn' => $payment->getId(),
                // 'payment_sn' => $paymentId,
                'status' => Order::ORDER_STATUS_SHIPPING,
                'paid_at' => Carbon::now()->toDateTimeString(),
            ]);
            Log::info("A New Paypal Payment Executed - Asynchronously: order id - " . $order->id);
            return response()->json([
                'code' => 200,
                'message' => 'Paypal Payment Executed - Asynchronously',
            ]);
        } else {
            // Payment Cancelled.
            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Asynchronously: order id - ' . $order->id);
                return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Asynchronously',
                ]);
            }

            // Do Nothing
            /*$order->update([
                'payment_method' => '',
                'payment_sn' => '',
            ]);*/

            return response()->json([
                'code' => 200,
                'message' => 'Paypal Payment Cancelled - Asynchronously',
            ]);
        }
    }

    // Paypal 退款
    public function paypalRefund(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.exists' => '该订单不存在',
        ], [
            'order_id' => '订单ID',
        ]);
        $order = Order::find($request->input('order_id'));

        // TODO ... (for production)
        /*if ($order->status !== Order::ORDER_STATUS_REFUNDING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }*/

        Log::info('A New Paypal Refund Begins: order refund id - ' . $order->refund->id);

        $config = $this->getPaypalConfig($order);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        $paymentId = $order->payment_sn;
        $payment = Payment::get($paymentId, $apiContext, $restCall);

        $transactions = $payment->getTransactions();
        $amount = $transactions[0]->getAmount();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();
        // $saleId = $sale->getId();
        // $sale->setAmount($amount);
        $sale->setReceiptId($order->payment_sn);

        /*$payer = $payment->getPayer();
        $payerInfo = $payer->getPayerInfo();
        $payerId = $payerInfo->getPayerId();*/

        $refundRequest = new RefundRequest();
        $refundRequest->setAmount($amount); // refund with the same amount & currency as previously.
        $refundRequest->setDescription('This is a refund order from Jorya Hair. [' . $order->refund->refund_sn . ']');
        $refundRequest->setReason($order->refund->remark_from_user);
        try {
            $detailedRefund = $sale->refundSale($refundRequest, $apiContext, $restCall);
            Log::info("A New Paypal Payment Refund Created: " . $detailedRefund->toJSON());

            /*$state = $detailedRefund->getState();
            $refundId = $detailedRefund->getId();
            $refundToPayer = $detailedRefund->getRefundToPayer();*/

            if ($detailedRefund->getState() != 'completed') {
                Log::error('A New Paypal Refund Failed: order refund id - ' . $order->refund->id);
            }

            // 将退款订单的状态标记为退款成功并保存退款時間
            $order->refund->update([
                'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
                'refunded_at' => Carbon::now()->toDateTimeString(), // 退款时间
            ]);

            Log::info('A New Paypal Refund Completed: order refund id - ' . $order->refund->id);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'detailedRefund' => $detailedRefund->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error("A New Paypal Pc Payment Refund Failed: order refund id - " . $order->refund->id . '; With Error Message: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
            /*return view('payments.error', [
                'order' => $order,
                'message' => $e->getMessage(),
            ]);*/
        }
    }

    /**
     * Reference:
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
     */
    // joryashop.test/payments/get_wechat_open_id
    public function getWechatOpenId(Request $request)
    {
        header('Content-type: text/html; charset=utf-8');
        if (!isset($_GET['code'])) {
            /*Step-1*/
            $app_id = config('payment.wechat.app_id'); // 公众号在微信的app_id
            $redirect_uri = route('payments.get_wechat_open_id'); // 要请求的url

            // $scope = 'snsapi_userinfo'; // for access to advanced user info.
            // $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=wx' . '#wechat_redirect';
            $scope = 'snsapi_base'; // for access to basic user info.
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=1' . '#wechat_redirect';

            header('Location:' . $url);
            exit();
        } else {
            /*Step-2*/
            //根据code查询用户基础信息：openid和access_token
            $app_id = config('payment.wechat.app_id'); // 公众号在微信的app_id
            $app_secret = config('payment.wechat.app_secret'); // 公众号在微信的app_secret
            $code = $request->query('code');
            $get_access_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $app_id . '&secret=' . $app_secret . '&code=' . $code . '&grant_type=authorization_code';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_access_token_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            $response_array = json_decode($response, true);
            Session::put('wechat-basic_user_info', $response_array);
            // session(['wechat-basic_user_info' => $response_array]);
            // return $response_array;
            // dd($response_array);
            return response()->json($response);

            /*Step-3*/
            //根据openid和access_token查询用户信息
            /*$access_token = $response_array['access_token'];
            $openid = $response_array['openid'];
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            $response_array = json_decode($response, true);
            Session::put('wechat-advanced_user_info', $response_array);*/
            // session(['wechat-advanced_user_info' => $response_array]);
            // return $response_array;
            // dd($response_array);
        }
    }

    // POST 通用 - 模拟后台发起订单退款
    public function refund(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.exists' => '该订单不存在',
        ], [
            'order_id' => '订单ID',
        ]);
        $order = Order::find($request->input('order_id'));

        // TODO ... (for production)
        /*if ($order->status !== Order::ORDER_STATUS_REFUNDING) {
            throw new InvalidRequestException('当前订单状态不正确');
        }*/

        if ($order->refund->status == OrderRefund::ORDER_REFUND_STATUS_REFUNDED) {
            return response()->json([
                'code' => 200,
                'message' => 'Order Refunded Already',
            ]);
        } else if ($order->refund->status == OrderRefund::ORDER_REFUND_STATUS_DECLINED) {
            return response()->json([
                'code' => 400,
                'message' => 'Order Refund Declined',
            ]);
        }

        switch ($order->payment_method) {
            case Order::PAYMENT_METHOD_ALIPAY:
                return $this->alipayRefund($request);
                break;
            case Order::PAYMENT_METHOD_WECHAT:
                return $this->wechatRefund($request);
                break;
            case Order::PAYMENT_METHOD_PAYPAL:
                return $this->paypalRefund($request);
                break;
            default:
                throw new InvalidRequestException('Invalid Payment Method: ' . $order->payment_method);
                break;
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
