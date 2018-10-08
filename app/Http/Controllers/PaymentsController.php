<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;

class PaymentsController extends Controller
{
    // GET Alipay支付页面
    public function alipay ()
    {
        return view('payments.alipay');
    }

    // GET WeChat支付页面
    public function wechat ()
    {
        return view('payments.wechat');
    }

    // GET Paypal支付页面
    public function paypal ()
    {
        return view('payments.paypal');
    }

    // GET 支付成功页面 [notify_url]
    public function success (Request $request)
    {
        return view('payments.success');
    }

    /*支付回调 [return_url]*/
    // POST Alipay支付回调 [return url]
    public function alipayCallback ()
    {
        // TODO ...
    }

    // POST WeChat支付回调 [return url]
    public function wechatCallback ()
    {
        // TODO ...
    }

    // POST Paypal支付回调 [return url]
    public function paypalCallback ()
    {
        // TODO ...
    }
}
