@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '支付失败' : 'Payment Failed') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('order.Order payment failed')</span>
        @endif
    </div>
    <div class="payment_method">
        <div class="payment_success">
            <img src="{{ asset('static_m/img/refund_4.png') }}">
            <p class="failed_text">@lang('order.Order payment failed')</p>
            <p class="clear">
                @if(isset($payment))
                    <span>Local Payment SN: {{ $payment->sn }}</span>
                    <span>Orders:</span>
                    @foreach($payment->orders as $order)
                        <a href="{{ route('mobile.orders.show', ['order' => $order->id]) }}">
                            Order SN: {{ $order->order_sn }}@lang('order.View order details')
                        </a>
                    @endforeach
                @endif
                {{--@if(isset($orders))
                    @foreach($orders as $order)
                        <a href="{{ route('mobile.orders.show', ['order' => $order->id]) }}">
                            Order SN: {{ $order->order_sn }}@lang('order.View order details')
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('mobile.orders.index') }}">@lang('order.View order list')</a>
                @endif--}}
                <a href="{{ route('mobile.orders.index') }}">@lang('order.View order list')</a>
                <a href="{{ route('mobile.root') }}">@lang('basic.users.Return to the home page')</a>
            </p>
            {{--<h3>错误信息：</h3>
            {{ $message }}--}}
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        });
    </script>
@endsection
