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
    protected function getAlipayConfig(Order $order)
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

    /**
     * 服务器端回调
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    // POST Alipay 支付通知 [notify_url]
    public function alipayNotify(Request $request, Order $order)
    {
        Log::info('A Payment Notification From Alipay: ' . collect($request->all())->toJson());

        // 校验输入参数
        $data = Pay::alipay($this->getAlipayConfig($order))->verify();
        Log::info('A Payment Notification From Alipay With Verified Data: ' . $data->toJson());

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $alipay_order = Order::where('order_sn', $data->out_trade_no)->first();

        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$alipay_order || $alipay_order->id != $order->id) {
            Log::error('Alipay Notified With Wrong Out_Trade_No: ' . $data->out_trade_no);
            return 'fail';
        }

        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给支付宝
            Log::info('A Paid Wechat Payment Notified Again: order id - ' . $order->id);
            return Pay::alipay($this->getAlipayConfig($order))->success();
        }

        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING,
            'paid_at' => Carbon::now()->toDateTimeString(), // 支付时间
            'payment_method' => Order::PAYMENT_METHOD_ALIPAY, // 支付方式
            'payment_sn' => $data->trade_no, // 支付宝订单号
        ]);

        Log::info('A New Alipay Payment Completed: order id - ' . $order->id);
        return Pay::alipay($this->getAlipayConfig($order))->success();
    }

    /**
     * 前端回调页面
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    // GET Alipay 支付回调 [return url]
    public function alipayReturn(Request $request, Order $order)
    {
        $alipay = Pay::alipay($this->getAlipayConfig($order));
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

        // TODO ...
        /*return view('pages.success', [
            'msg' => '付款成功',
        ]);*/
        /*return view('payments.success', [
            'order' => $data,
        ]);*/
        return $alipay->success();
    }

    // Alipay 退款
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

        // 调用支付宝支付实例的 refund 方法
        $response = Pay::alipay($this->getAlipayConfig($order))->refund([
            'out_trade_no' => $order->order_sn, // 之前的订单流水号
            'refund_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 退款金额，单位元
        ]);

        // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
        if ($response->sub_code) {
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
    }

    /*Wechat Payment*/
    protected function getWechatConfig(Order $order)
    {
        return array_merge(config('payment.wechat'), [
            'notify_url' => route('payments.wechat.notify', ['order' => $order->id]),
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
            $result = Pay::wechat($this->getWechatConfig($order))->scan([
                'out_trade_no' => $order->order_sn, // 订单编号，需保证在商户端不重复
                'body' => '请支付来自 Jorya Hair 的订单：' . $order->order_sn, // 订单标题
                'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，参数值不能带小数点
            ]);

            // 二维码内容：
            $qr_code_url = $result->code_url;

            Log::info('A New Wechat Pc-Scan Payment Created: ' . $result->toJSON() . '; Qr Code Url: ' . $qr_code_url);
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

    // GET 判断订单是否已经支付 [for Ajax request]
    public function isPaid(Request $request, Order $order)
    {
        if ($order->status != Order::ORDER_STATUS_PAYING && $order->paid_at != null && $order->payment_sn != null) {
            return response()->json([
                'code' => 200,
                'message' => 'Order is paid already',
            ]);
        }
        return response()->json([
            'code' => 202,
            'message' => 'Order is not paid yet',
        ]);
    }

    // POST WeChat 支付通知 [notify_url]
    public function wechatNotify(Request $request, Order $order)
    {
        Log::info('A Payment Notification From Wechat: ' . collect($request->all())->toJson());

        // 校验输入参数
        $data = Pay::wechat($this->getWechatConfig($order))->verify();
        Log::info('A Payment Notification From Wechat With Verified Data: ' . $data->toJson());

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $wechat_order = Order::where('order_sn', $data->out_trade_no)->first();

        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$wechat_order || $wechat_order->id != $order->id) {
            Log::error('Wechat Notified With Wrong Out_Trade_No: ' . $data->out_trade_no);
            return 'fail';
        }

        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at) {
            // 返回数据给 Wechat
            Log::info('A Paid Wechat Payment Notified Again: order id - ' . $order->id);
            return Pay::wechat($this->getWechatConfig($order))->success();
        }

        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING,
            'paid_at' => Carbon::now()->toDateTimeString(), // 支付时间
            'payment_method' => Order::PAYMENT_METHOD_WECHAT, // 支付方式
            'payment_sn' => $data->trade_no, // Wechat 订单号
        ]);

        Log::info('A New Wechat Payment Completed: order id - ' . $order->id);
        return Pay::wechat($this->getWechatConfig($order))->success();
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

        // 调用Wechat支付实例的 refund 方法
        $response = Pay::wechat($this->getWechatConfig($order))->refund([
            'out_trade_no' => $order->order_sn, // 之前的订单流水号
            'out_refund_no' => $order->refund->refund_sn, // 退款订单流水号
            'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，只能为整数
            'refund_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 退款金额，单位分，只能为整数
            'refund_desc' => '这是来自 Jorya Hair 的退款订单' . $order->refund->refund_sn,
        ]);

        // 根据Wechat的文档，如果返回值里有 sub_code 字段说明退款失败
        if ($response->return_code == 'FAIL') {
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
    }

    /*Paypal Payment*/
    protected function getPaypalConfig(Order $order)
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
                "total": "1.00",
                "currency": "USD"
            },
            "related_resources": []
        }
    ],
    "redirect_urls": {
        "return_url": "https://example.com/your_redirect_url.html",
        "cancel_url": "https://example.com/your_cancel_url.html"
    },
    "id": "PAY-3MC96102SY030652JLHXXPMA",
    "state": "created",
    "create_time": "2017-10-24T17:26:07Z",
    "links": [
        {
            "href": "https://api.sandbox.paypal.com/v1/payments/payment/PAY-3MC96102SY030652JLHXXPMA",
            "rel": "self",
            "method": "GET"
        },
        {
            "href": "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-1NT485541R0509947",
            "rel": "approval_url",
            "method": "REDIRECT"
        },
        {
            "href": "https://api.sandbox.paypal.com/v1/payments/payment/PAY-3MC96102SY030652JLHXXPMA/execute",
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
        $apiContext->setConfig($config['log']);
        $restCall = new PayPalRestCall($apiContext);

        // Step-2: create a new payment
        $payer = new Payer();
        $payer->setPaymentMethod(Order::PAYMENT_METHOD_PAYPAL);

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
                Log::info("A New Paypal Pc Payment Creation Failed: " . $payment->toJSON());
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
            Log::error($e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
            /*return view('payments.error', [
                'message' => $e->getMessage(),
            ]);*/
        }
    }

    // GET Paypal: get the info of a payment
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
        $apiContext->setConfig($config['log']);
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

            /*$order = Order::where('payment_method', Order::PAYMENT_METHOD_PAYPAL)
                ->where('payment_sn', $token)
                ->first();

            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$order) {
                $order = Order::where('payment_method', Order::PAYMENT_METHOD_PAYPAL)
                    ->where('payment_sn', $paymentId)
                    ->first();
                if (!$order) {
                    Log::error('Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token);
                    return response()->json([
                        'code' => 400,
                        'message' => 'Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token,
                    ], 400);
                }
            }*/

            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: order id - ' . $order->id);
                return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);
            }

            $config = $this->getPaypalConfig($order);
            $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
            $apiContext = new ApiContext($oAuthTokenCredential);
            $apiContext->setConfig($config['log']);
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
                    return response()->json([
                        'code' => 200,
                        'message' => 'Paypal Payment Executed - Synchronously',
                        'data' => [
                            'payment' => $payment->toArray(),
                        ],
                    ]);
                } else {
                    Log::info("A New Paypal Pc Payment Execution Failed - Synchronously: " . $payment->toJSON());
                    return response()->json([
                        'code' => 400,
                        'message' => 'A New Paypal Pc Payment Execution Failed - Synchronously',
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
                Log::error($e->getMessage());
                return response()->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);
                /*return view('payments.error', [
                    'message' => $e->getMessage(),
                ]);*/
            }
        } else {
            // Payment Cancelled.
            $token = $request->query('token');
            if (!$token) {
                return response()->json([
                    'code' => 400,
                    'message' => 'PayPal Notified With Wrong Parameters: Cancel Url Without Token - Synchronously',
                    'data' => $request->all(),
                ], 400);
            }

            /*$order = Order::where('payment_method', Order::PAYMENT_METHOD_PAYPAL)
                ->where('payment_sn', $token)
                ->first();

            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$order) {
                Log::error('Paypal Notified With Wrong Token: ' . $token);
                return response()->json([
                    'code' => 400,
                    'message' => 'Paypal Notified With Wrong Token: ' . $token,
                ], 400);
            }*/

            // 如果这笔订单的状态已经是已支付
            if ($order->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: order id - ' . $order->id);
                return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);
            }

            /*$order->update([
                'payment_method' => '',
                'payment_sn' => '',
            ]);*/

            return response()->json([
                'code' => 200,
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
        $apiContext->setConfig($config['log']);
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
        $refundRequest->setReason($order->refund->remark_by_user);
        try {
            $detailedRefund = $sale->refundSale($refundRequest, $apiContext, $restCall);
            Log::info("A New Paypal Payment Refund Created: " . $detailedRefund->toJSON());

            $state = $detailedRefund->getState();
            $refundId = $detailedRefund->getId();
            $refundToPayer = $detailedRefund->getRefundToPayer();

            if ($detailedRefund->getState()) {
                // TODO ...
            }

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
            Log::error($e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
            /*return view('payments.error', [
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

        switch ($order->payment_method) {
            case Order::PAYMENT_METHOD_ALIPAY:
                $this->alipayRefund($request);
                break;
            case Order::PAYMENT_METHOD_WECHAT:
                $this->wechatRefund($request);
                break;
            case Order::PAYMENT_METHOD_PAYPAL:
                $this->paypalRefund($request);
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
