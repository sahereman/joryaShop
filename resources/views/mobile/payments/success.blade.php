@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '支付成功' : 'Payment Success') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="payment_method">
        <div class="payment_success">
            <img src="{{ asset('static_m/img/icon_Success.png') }}">
            <p>@lang('order.Order payment success')</p>
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
                <a href="{{ route('mobile.root') }}">@lang('order.Continue to buy')</a>
            </p>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        });
    </script>
@endsection
