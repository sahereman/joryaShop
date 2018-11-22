@extends('layouts.app')
@section('title', '支付成功')
@section('content')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="payment_success">
                <img src="{{ asset('img/reset_success.png') }}">
                <p>@lang('order.Order payment success')</p>
                <p class="clear">
                    @if(isset($order))
                    <a href="{{ route('orders.show', ['order' =>  $order->id]) }}">@lang('order.View order details')</a>
                    @else
                    <a href="{{ route('orders.index') }}">@lang('order.View order list')</a>
                    @endif
                    <a href="{{ route('root') }}">@lang('order.Continue to buy')</a>
                </p>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        });
    </script>
@endsection
