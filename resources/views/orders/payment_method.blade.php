@extends('layouts.app')
@section('title', '选择支付方式')
@section('content')
    @include('common.error')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="methods">
            	<p class="trade_number">交易号：<span>20181012112935YXMPD</span></p>
            	<div class="methods_choose">
            		<p>支付方式</p>
            		<ul>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="1" id="alipay" checked>
            					<img src="{{ asset('img/alipay.png') }}">
            				</label>
            			</li>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="2" id="wxpay">
            					<img src="{{ asset('img/wxpay.png') }}">
            				</label>
            			</li>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="3" id="paypal">
            					<img src="{{ asset('img/paypal.png') }}">
            				</label>
            			</li>
            		</ul>
            	</div>
            	<div class="methods_footer clear">
            		<div class="left">
            			<p>实付：<span id="needToPay">&yen;888.00</span></p>
            		</div>
            		<div class="right">
            			<button class="pay_btn">付款</button>
            			<p class="cunt_down paying_time">剩余付款时间：<span>59分58秒</span></p>
            		</div>
            	</div>
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
