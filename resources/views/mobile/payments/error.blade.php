@extends('layouts.mobile')
@section('title', (App::isLocale('en') ? 'Payment Failed' : '支付失败') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
            <span>@lang('order.Order payment failed')</span>
        @endif
    </div>
    <div class="payment_method">
        <div class="payment_success">
            <img src="{{ asset('static_m/img/refund_4.png') }}">
            <p class="failed_text">@lang('order.Order payment failed')</p>
            <p class="clear">
                @if(isset($order))
                    <a href="{{ route('mobile.orders.show', ['order' =>  $order->id]) }}">@lang('order.View order details')</a>
                @else
                    <a href="{{ route('mobile.orders.index') }}">@lang('order.View order list')</a>
                @endif
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
