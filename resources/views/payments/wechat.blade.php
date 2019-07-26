@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '微信支付' : 'WeChat payment') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="wechat_title clear">
                <p class="title">请及时付款，以便订单尽快处理！</p>
                <p class="yxTradeNo" data-url="{{ route('payments.is_completed', ['payment' => $payment->id]) }}">交易号：{{ $payment->sn }}</p>
                <p class="actualPrice">
                    <span>实付：</span>
                    {{--<span class="red">{{ (($payment->currency === 'CNY') ? "&#165; " : "&#36; ") . $payment->amount }}</span>--}}
                    <span class="red">{{ (get_symbol_by_currency($payment->currency)) . ' ' . $payment->amount }}</span>
                </p>
            </div>
            <div class="payment_success wechat">
                <div class="bd f-clearfix">
                    <div class="code left">
                        <div class="bd">
                            <img src="{!! generate_qr_code($qr_code_url, 'png', 150) !!}">
                        </div>
                        <p class="text" data-url="{{ route('payments.success', ['payment' => $payment->id]) }}">微信扫一扫支付</p>
                    </div>
                    <img class="phone left" src="{{ asset('img/wechat_pay.png') }}">
                </div>
                {{--<img src="{!! generate_qr_code($qr_code_url) !!}">
                <p>Scan the qr code to wechat-pay.</p>--}}
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            window.onload = function () {
                function _fresh() {
                    $.ajax({
                        type: "get",
                        url: $(".yxTradeNo").attr("data-url"),
                        success: function (json) {
                            console.log(json);
                            if (json.code == 200) {
                                // clearInterval(sh);
                                window.location.href = json.data.request_url;
                                // window.location.href = $(".text").attr("data-url");
                            }
                        }
                    });
                }

                _fresh();
                var sh = setInterval(_fresh, 2000);
            }
        })
    </script>
@endsection
