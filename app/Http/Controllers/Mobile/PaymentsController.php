<?php

namespace App\Http\Controllers\Mobile;

use App\Events\OrderPaidEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
// use App\Http\Middleware\GetWechatOpenId;
use App\Models\Order;
// use App\Models\OrderRefund;
use App\Models\Payment as LocalPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment as PayPalPayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
// use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use Yansongda\Pay\Pay;

class PaymentsController extends Controller
{
    protected function isAuthorized(Request $request, LocalPayment $payment)
    {
        $user = $request->user();
        if (($user && $payment->user_id && $payment->user_id = $user->id) || ($user == null && $payment->user_id == null)) {
            return true;
        }

        // throw new InvalidRequestException('您没有权限操作此订单');
        throw new InvalidRequestException('You do not have access to the payment of this order');
    }

    // GET 选择支付方式页面
    public function method(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        if ($payment->paid_at != null && $payment->payment_method != null && $payment->payment_sn != null) {
            return redirect()->route('mobile.payments.success', [
                'payment' => $payment->id,
            ]);
        }
        return view('mobile.payments.payment_method', [
            'payment' => $payment,
        ]);
    }

    /*Alipay Payment*/
    public static function getAlipayConfig(LocalPayment $payment)
    {
        return array_merge(config('payment.alipay'), [
            'notify_url' => route('payments.alipay.notify', ['payment' => $payment->id]),
            'return_url' => route('mobile.payments.alipay.return', ['payment' => $payment->id]),
        ]);
    }

    // GET Alipay Mobile-Wap 支付页面
    public function alipayWap(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $payment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/
        // 判断当前订单状态是否支持支付
        if ($payment->paid_at) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        Log::info('A New Alipay Mobile-Wap Payment Begins: local payment id - ' . $payment->id);

        // 调用Alipay的手机网站支付
        return Pay::alipay($this->getAlipayConfig($payment))->wap([
            'out_trade_no' => $payment->sn, // 支付序列号，需保证在商户端不重复
            'total_amount' => $payment->payment_amount, // 支付金额，单位元，支持小数点后两位
            'subject' => '请支付来自 Lyrical Hair 的订单：' . $payment->sn, // 订单标题
        ]);
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
    public function alipayReturn(Request $request, LocalPayment $payment)
    {
        Log::info('Alipay Mobile-Wap Payment Return-Url : ' . $request->getUri());

        try {
            // 校验提交的参数是否合法
            $data = Pay::alipay($this->getAlipayConfig($payment))->verify();
            Log::info('A New Alipay Mobile-Wap Payment Return With Verified Data: ' . $data->toJson());

            //return $alipay->success();
            /*return view('mobile.pages.success', [
                'msg' => '付款成功',
            ]);*/
            return view('mobile.payments.success', [
                'payment' => $payment,
                // 'orders' => $payment->orders,
                'msg' => '付款成功',
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('mobile.pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Alipay Mobile-Wap Payment Return Failed: local payment id - ' . $payment->id . '; With Error Message: ' . $e->getMessage());
            return view('mobile.payments.error', [
                'payment' => $payment,
                // 'orders' => $payment->orders,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /*Wechat Payment*/
    public static function getWechatConfig(LocalPayment $payment)
    {
        return array_merge(config('payment.wechat'), [
            'notify_url' => route('payments.wechat.notify', ['payment' => $payment->id]),
            'return_url' => route('mobile.payments.wechat_return', ['payment' => $payment->id]),
        ]);
    }

    // GET Wechat Mobile-MP(公众号) 支付页面
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
    public function wechatMp(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $payment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/
        // 判断当前订单状态是否支持支付
        if ($payment->paid_at) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        Log::info('A New Wechat Mobile-Mp Payment Begins: local payment id - ' . $payment->id);

        try {
            $basic_user_info = Session::get('wechat-basic_user_info');

            // 调用Wechat的公众号支付(微信浏览器内支付)
            $response = Pay::wechat($this->getWechatConfig($payment))->mp([
                'out_trade_no' => $payment->sn, // 支付序列号，需保证在商户端不重复
                'body' => '请支付来自 Lyrical Hair 的订单：' . $payment->sn, // 订单标题
                'total_fee' => bcmul($payment->payment_amount, 100, 0), // 支付金额，单位分，参数值不能带小数点
                'openid' => $basic_user_info['openid'],
            ]);

            Log::info('A New Wechat Mobile-Mp Payment Finished: local payment id - ' . $payment->id . '; With Response: ' . $response->toJson());

            return response()->json($response->toArray());

            /*Sample response*/
            /*{
                "appId":"wx0b3f800e268b1e85",
                "timeStamp":"1543801042",
                "nonceStr":"ZfYKA9xrF7tZw6eX",
                "package":"prepay_id=wx03093723016488f08695c0f62974033491",
                "signType":"MD5",
                "paySign":"3B74D2147CB604894208B3838C09D4EE"
            }*/

            /*后续调用示例*/
            /*$.ajax({
                url: "{{ route('mobile.payments.wechat.mp', ['order' => $order->id]) }}",
                type: "GET",
                data: {},
                success: function (data) {
                    WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', {
                            "appId": data.appId, //公众号名称，由商户传入
                            "timeStamp": data.timeStamp, //时间戳，自1970年以来的秒数
                            "nonceStr": data.nonceStr, //随机串
                            "package": data.package,
                            "signType": data.signType, //微信签名方式：
                            "paySign": data.paySign //微信签名
                        },
                        function (res) {
                            if (res.err_msg == "get_brand_wcpay_request:ok") {
                                window.location.reload();
                            }
                        });
                },
                error: function (e) {
                    alert("支付失败");
                }
            });*/

        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('mobile.pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Wechat Mobile-Mp Payment Failed: local payment id - ' . $payment->id . '; With Error Message: ' . $e->getMessage());
            return view('mobile.payments.error', [
                'payment' => $payment,
                // 'orders' => $payment->orders,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // GET Wechat Mobile-Wap 支付页面
    public function wechatWap(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $payment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/
        // 判断当前订单状态是否支持支付
        if ($payment->paid_at) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        Log::info('A New Wechat Mobile-Wap Payment Begins: local payment id - ' . $payment->id);
        try {
            // 调用Wechat的手机网站支付
            return Pay::wechat($this->getWechatConfig($payment))->wap([
                'out_trade_no' => $payment->sn, // 支付序列号，需保证在商户端不重复
                'body' => '请支付来自 Lyrical Hair 的订单：' . $payment->sn, // 订单标题
                'total_fee' => bcmul($payment->payment_amount, 100, 0), // 支付金额，单位分，参数值不能带小数点
            ]);
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('mobile.pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error('A New Wechat Mobile-Wap Payment Failed: local payment id - ' . $payment->id . '; With Error Message: ' . $e->getMessage());
            return view('mobile.payments.error', [
                'payment' => $payment,
                // 'orders' => $payment->orders,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /*Paypal Payment*/
    public static function getPaypalConfig(LocalPayment $localPayment)
    {
        return array_merge(config('payment.paypal'), [
            'redirect_urls' => [
                'return_url' => route('mobile.payments.paypal.execute', ['payment' => $localPayment->id]),
                'cancel_url' => route('mobile.payments.paypal.execute', ['payment' => $localPayment->id]),
                'notify_url' => route('payments.paypal.notify', ['payment' => $localPayment->id]),
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
                "notify_url": "https://lyrical.joryahair.com/payments/7/paypal/notify"
            }
        ],
        "redirect_urls": {
            "return_url": "https://lyrical.joryahair.com/payments/7/paypal/execute",
            "cancel_url": "https://lyrical.joryahair.com/payments/7/paypal/execute"
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
    public function paypalCreate(Request $request, LocalPayment $localPayment)
    {
        $this->isAuthorized($request, $localPayment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $localPayment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/
        // 判断当前订单状态是否支持支付
        if ($localPayment->paid_at) {
            throw new InvalidRequestException('当前订单状态不正确');
        }
        // 判断PayPal是否支持当前订单支付币种
        if (in_array($localPayment->currency, ['CNY'])) {
            throw new InvalidRequestException('Paypal暂不支持当前订单支付币种: ' . $localPayment->currency);
        }

        // Step-1: get an access token && create the api context
        $config = $this->getPaypalConfig($localPayment);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        // Step-2: create a new payment
        $payer = new Payer();
        $payer->setPaymentMethod(LocalPayment::PAYMENT_METHOD_PAYPAL); // paypal

        $amount = new Amount();
        $amount->setCurrency($localPayment->currency)
            ->setTotal($localPayment->payment_amount);

        $transaction = new Transaction($apiContext);
        $transaction->setAmount($amount);
        $transaction->setNotifyUrl($config['redirect_urls']['notify_url']);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($config['redirect_urls']['return_url'])
            ->setCancelUrl($config['redirect_urls']['cancel_url']);

        $paypalPayment = new PayPalPayment($apiContext);
        $paypalPayment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        try {
            $paypalPayment->create($apiContext, $restCall);
            if ($paypalPayment->getState() == 'created') {
                $localPayment->update([
                    // 'method' => $paypalPayment->getPayer()->getPaymentMethod(), // paypal
                    'method' => LocalPayment::PAYMENT_METHOD_PAYPAL,
                    // 'payment_sn' => $paypalPayment->getToken(), // token
                    'payment_sn' => $paypalPayment->getId(), // paymentId
                ]);
                Log::info("A New Paypal Mobile Payment Created: " . $paypalPayment->toJSON());
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'payment' => $paypalPayment->toArray(),
                        'redirect_url' => $paypalPayment->getApprovalLink(),
                    ],
                ]);
            } else {
                Log::error("A New Paypal Mobile Payment Creation Failed: " . $paypalPayment->toJSON());
                return response()->json([
                    'code' => 400,
                    'message' => 'A New Paypal Mobile Payment Creation Failed',
                    'data' => [
                        'payment' => $paypalPayment->toArray(),
                        'failure_reason' => $paypalPayment->getFailureReason(),
                    ],
                ]);
            }
        } catch (\Exception $e) {
            // error_log($e->getMessage());
            /*return view('mobile.pages.error', [
                'msg' => '付款失败',
            ]);*/
            Log::error("A New Paypal Mobile Payment Creation Failed: local payment id - " . $localPayment->id . '; With Error Message: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
            /*return view('mobile.payments.error', [
                'payment' => $payment,
                // 'orders' => $payment->orders
                'message' => $e->getMessage(),
            ]);*/
        }
    }

    // GET Paypal: get the info of a payment [Test API]
    public function paypalGet(Request $request, LocalPayment $localPayment)
    {
        $this->isAuthorized($request, $localPayment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $localPayment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/
        if ($localPayment->method !== LocalPayment::PAYMENT_METHOD_PAYPAL) {
            throw new InvalidRequestException('This order is not a payment from paypal: payment method - ' . $localPayment->method);
        }
        // 判断PayPal是否支持当前订单支付币种
        if (in_array($localPayment->currency, ['CNY'])) {
            throw new InvalidRequestException('Paypal暂不支持当前订单支付币种: ' . $localPayment->currency);
        }

        $config = $this->getPaypalConfig($localPayment);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        $paymentId = $localPayment->payment_sn;
        $paypalPayment = PayPalPayment::get($paymentId, $apiContext, $restCall);

        /*$transactions = $paypalPayment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();
        $saleId = $sale->getId();

        $payer = $paypalPayment->getPayer();
        $payerInfo = $payer->getPayerInfo();
        $payerId = $payerInfo->getPayerId();*/

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'payment' => $paypalPayment->toArray(),
                // 'transaction' => $transactions[0]->toArray(),
                // 'sale' => $sale->toArray(),
                // 'payer' => $payer->toArray(),
            ],
        ]);
    }

    // GET Paypal: synchronously execute[approve|cancel] an approved|cancelled PayPal payment. 支付同步通知
    public function paypalExecute(Request $request, LocalPayment $localPayment)
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
            $payment = LocalPayment::where('method', LocalPayment::PAYMENT_METHOD_PAYPAL)
                ->where('payment_sn', $paymentId)
                ->first();
            // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
            if (!$payment || $payment->id != $localPayment->id) {
                Log::error('Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token);
                /*return response()->json([
                    'code' => 400,
                    'message' => 'Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token,
                ], 400);*/
                /*return view('mobile.pages.error', [
                    'msg' => '付款失败',
                ]);*/
                return view('mobile.payments.error', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
                    'message' => 'Paypal Notified With Wrong Payment Id: ' . $paymentId . ' or Wrong Token: ' . $token,
                ]);
            }

            // 如果这笔订单的状态已经是已支付
            if ($localPayment->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: local payment id - ' . $localPayment->id);
                /*return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);*/
                return view('mobile.payments.success', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
                ]);
            }

            $config = $this->getPaypalConfig($localPayment);
            $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
            $apiContext = new ApiContext($oAuthTokenCredential);
            $apiContext->setConfig($config);
            $restCall = new PayPalRestCall($apiContext);

            $paypalPayment = PayPalPayment::get($paymentId, $apiContext, $restCall);

            $amount = new Amount();
            $amount->setCurrency($localPayment->currency)
                ->setTotal($localPayment->payment_amount);

            $transaction = new Transaction();
            $transaction->setAmount($amount);

            $paymentExecution = new PaymentExecution();
            $paymentExecution->setPayerId($payerId);
            $paymentExecution->setTransactions(array($transaction));
            try {
                $paypalPayment->execute($paymentExecution, $apiContext, $restCall);
                if ($paypalPayment->getState() == 'approved') {
                    try {
                        DB::transaction(function () use ($localPayment, $paymentId) {
                            // MySQL InnoDB 默认行级锁。行级锁都是基于索引的，如果一条SQL语句用不到索引是不会使用行级锁的，会使用表级锁把整张表锁住，这点需要注意。
                            // where(['id' => $localPayment->id]) 意义在于：使用索引以触发行级锁
                            $payment = LocalPayment::where(['id' => $localPayment->id])->lockForUpdate()->first();
                            if ($payment) {
                                if (!$payment->paid_at) {
                                    $payment->update([
                                        'method' => LocalPayment::PAYMENT_METHOD_PAYPAL,
                                        // 'payment_sn' => $paypalPayment->getId(),
                                        'payment_sn' => $paymentId,
                                        'paid_at' => Carbon::now()->toDateTimeString(),
                                    ]);
                                    $localPayment->orders->each(function (Order $order) {
                                        event(new OrderPaidEvent($order));
                                    });
                                }
                            } else {
                                throw new \Exception('MySQL lock-for-update of local payment is out of time - local payment id: ' . $localPayment->id);
                            }
                        });
                    } catch (\Exception $e) {
                        Log::error('MySQL lock-for-update of local payment is out of time - local payment id: ' . $localPayment->id);
                    }
                    Log::info("A New Paypal Payment Executed - Synchronously: " . $paypalPayment->toJSON());
                    return view('mobile.payments.success', [
                        'payment' => $localPayment,
                        // 'orders' => $localPayment->orders
                    ]);
                    /*return response()->json([
                        'code' => 200,
                        'message' => 'Paypal Payment Executed - Synchronously',
                        'data' => [
                            'payment' => $paypalPayment->toArray(),
                        ],
                    ]);*/
                } else {
                    Log::info("A New Paypal Mobile Payment Execution Failed - Synchronously: " . $paypalPayment->toJSON());
                    return view('mobile.payments.error', [
                        'payment' => $localPayment,
                        // 'orders' => $localPayment->orders
                        'message' => "A New Paypal Mobile Payment Execution Failed - Synchronously: " . $paypalPayment->toJSON(),
                    ]);
                    /*return response()->json([
                        'code' => 400,
                        'message' => 'A New Paypal Mobile Payment Execution Failed - Synchronously',
                        'data' => [
                            'payment' => $paypalPayment->toArray(),
                            'failure_reason' => $paypalPayment->getFailureReason(),
                        ],
                    ]);*/
                }
            } catch (\Exception $e) {
                // error_log($e->getMessage());
                /*return view('mobile.pages.error', [
                    'msg' => '付款失败',
                ]);*/
                Log::error("A New Paypal Mobile Payment Execution Failed - Synchronously: local payment id - " . $localPayment->id . '; With Error Message: ' . $e->getMessage());
                return view('mobile.payments.error', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
                    'message' => "A New Paypal Mobile Payment Execution Failed - Synchronously: local payment id - " . $localPayment->id . '; With Error Message: ' . $e->getMessage(),
                ]);
                /*return response()->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);*/
                /*return view('mobile.payments.error', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
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
                /*return view('mobile.pages.error', [
                    'msg' => '付款失败',
                ]);*/
                return view('mobile.payments.error', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
                    'message' => 'PayPal Notified With Wrong Parameters: Cancel Url Without Token - Synchronously',
                ]);
            }

            // 如果这笔订单的状态已经是已支付
            if ($localPayment->paid_at) {
                // 返回数据给 Paypal
                Log::info('A Paid Paypal Payment Notified Again - Synchronously: local payment id - ' . $localPayment->id);
                /*return response()->json([
                    'code' => 200,
                    'message' => 'Paypal Payment Paid Already - Synchronously',
                ]);*/
                return view('mobile.payments.success', [
                    'payment' => $localPayment,
                    // 'orders' => $localPayment->orders
                ]);
            }

            // Do Nothing
            /*$localPayment->update([
                'method' => '',
                'payment_sn' => '',
            ]);*/

            /*return response()->json([
                'code' => 200,
                'message' => 'Paypal Payment Cancelled - Synchronously',
            ]);*/
            /*return view('mobile.pages.error', [
                'msg' => '付款失败',
            ]);*/
            return view('mobile.payments.error', [
                'payment' => $localPayment,
                // 'orders' => $localPayment->orders,
                'message' => 'Paypal Payment Cancelled - Synchronously',
            ]);
        }
    }

    /**
     * Reference:
     * https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
     */
    // lyrical.joryahair.com/payments/get_wechat_open_id
    public function getWechatOpenId(Request $request)
    {
        header('Content-type: text/html; charset=utf-8');
        if (!isset($_GET['code'])) {
            /*Step-1*/
            $app_id = config('payment.wechat.app_id'); // 公众号在微信的app_id
            $redirect_uri = route('mobile.payments.get_wechat_open_id'); // 要请求的url

            // $scope = 'snsapi_userinfo'; // for access to advanced user info.
            // $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=wx' . '#wechat_redirect';
            $scope = 'snsapi_base'; // for access to basic user info.
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=1' . '#wechat_redirect';

            header('Location:' . $url);
            // exit();
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

            Log::info('Wechat-Basic-User-Info: ' . $response);
            $response_array = json_decode($response, true);
            Session::put('wechat-basic_user_info', $response_array);

            if (Session::has('previous_url')) {
                return redirect(Session::get('previous_url'));
            } else {
                return redirect(URL::previous());
            }

            // session(['wechat-basic_user_info' => $response_array]);
            // return $response_array;
            // dd($response_array);
            // return response()->json($response);

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

    public function wechatReturn(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $payment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/

        // 判断当前订单状态是否已经支付
        // if ($payment->paid_at != null && $payment->method != null && $payment->payment_sn != null) {
        if ($payment->paid_at) {
            return redirect()->route('mobile.payments.success', [
                'payment' => $payment->id,
            ]);
        }

        return view('mobile.payments.wechat_return', [
            'payment' => $payment,
            // 'orders' => $payment->orders
        ]);
    }

    // GET 通用 - 支付成功页面
    public function success(Request $request, LocalPayment $payment)
    {
        $this->isAuthorized($request, $payment);
        // 判断订单是否属于当前用户
        /*if ($request->user()->id !== $payment->user_id) {
            throw new InvalidRequestException('您没有权限操作此订单');
        }*/

        // 判断当前订单状态是否已经支付
        if (!$payment->paid_at) {
            throw new InvalidRequestException('当前订单状态不正确');
        }

        return view('mobile.payments.success', [
            'payment' => $payment,
            // 'orders' => $payment->orders
        ]);
    }
}
