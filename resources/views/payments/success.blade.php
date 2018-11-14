@extends('layouts.app')
@section('title', '支付成功')
@section('content')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="payment_success">
                <img src="{{ asset('img/reset_success.png') }}">
                <p>订单支付成功</p>
                <p class="clear">
                	@if(isset($order))
                    <a href="{{ route('orders.show', ['order' =>  $order->id]) }}">查看订单详情</a>
                    @else
                    <a href="{{ route('orders.index') }}">查看订单列表</a>
                    @endif
                    <a href="{{ route('root') }}">继续购买</a>
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
