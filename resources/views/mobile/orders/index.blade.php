@extends('layouts.mobile')
@section('title', '我的订单')
@section('content')
	<div class="orderBox">
		<div class="orderHeadTop">
	    	<div class="headerBar">
				<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
				<span>我的订单</span>
			</div>
			<div class="orderHead">
				<div class="orderActive">全部</div>
				<div>待付款</div>
				<div>待收货</div>
				<div>待评价</div>
				<div>售后</div>
			</div>
	    </div>
		<div class="orderMain">
			<div class="orderItem">
				<div class="orderItemH">
					<span>订单编号:45654645464</span>
					<span class="orderItemState">待付款</span>
				</div>
				<div class="orderItemDetail">
					<img src="{{ asset('static_m/img/blockImg.png') }}"/>
					<div class="orderDal">
						<!--<span>卓业美业长直假发片</span>
						<span>颜色：黄</span>-->
					</div>
					<div class="orderPrice">
						
					</div>
				</div>
				<div class="orderItemTotle">
					
				</div>
			</div>
		</div>
	</div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
