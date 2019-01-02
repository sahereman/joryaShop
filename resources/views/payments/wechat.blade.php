@extends('layouts.app')
@section('title', (App::isLocale('en') ? 'WeChat payment' : '微信支付') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="wechat_title clear">
                <p class="title">请及时付款，以便订单尽快处理！</p>
                <p class="yxTradeNo" data-url="{{ route('orders.is_paid',$order->id) }}">交易号：{{ $order->order_sn }}</p>
                <p class="actualPrice">
                    <span>实付：</span>
                    <span class="red">{{ (($order->currency === 'CNY') ? "&#165; " : "&#36; ") . bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
                </p>
            </div>
            <div class="payment_success wechat">
                <div class="bd f-clearfix">
                    <div class="code left">
                        <div class="bd">
                            <img src="{!! generate_qr_code($qr_code_url, 'png', 150) !!}">
                        </div>
                        <p class="text" data-url="{{ route('payments.success', ['order' => $order->id]) }}">微信扫一扫支付</p>
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
