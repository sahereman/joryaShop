@extends('layouts.app')
@section('title', '支付成功')
@section('content')
    @include('common.error')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="payment_success">
                <img src="{{ asset('img/reset_success.png') }}">
                <p>订单支付失败</p>
                <p class="clear">
                    <a href="{{ route('orders.show', ['order' => $order_id ? : 3]) }}">查看订单详情</a>
                    <a href="{{ route('root') }}">继续购买</a>
                </p>
                <h3>错误信息：</h3>
                {{ $message }}
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
