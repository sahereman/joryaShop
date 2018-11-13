@extends('layouts.app')
@section('title', '微信支付')
@section('content')
    @include('common.error')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="payment_success">
                <img src="{!! generate_qr_code($qr_code_url) !!}">
                <p>Scan the qr code to wechat-pay.</p>
            </div>
        </div>
    </div>
@endsection
