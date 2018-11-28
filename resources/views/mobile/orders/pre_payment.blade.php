@extends('layouts.mobile')
@section('title', '确认订单')
@section('content')
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>确认订单</span>
	</div>
    <div class="pre_payment">
        <div class="pre_paymentCon">
        </div>
        <div class="pre_paymentTotal">
        	
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	
        });
    </script>
@endsection
