@extends('layouts.mobile')
@section('title', '订单详情')
@section('content')
	<div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>订单详情</span>
	</div>
	<div class="orderDetailBox">
			@if(true)
			<!--待付款-->
				<div class="orderDHead">
					<div class="odrHeadLeft">
						<img src="{{ asset('static_m/img/icon_wait.png') }}"/>
						<span>等待买家付款</span>
					</div>
					<div class="odrHeadRight">
						<div>
							<span>剩余:</span>
							<span>00:58:45支付</span>
						</div>
						<div class="odrHeadRightPri">
							<span>需付款:</span>
							<span>￥268.00</span>
						</div>
					</div>
				</div>
			@elseif(false)
			<!--待收货-->
				<div class="orderDHead">
					<div class="odrHeadLeft">
						<img src="{{ asset('static_m/img/icon_wait.png') }}"/>
						<span>等待卖家发货</span>
					</div>
					<div class="odrHeadRight">
						<img src="{{ asset('static_m/img/img_goods.png') }}"/>
					</div>
				</div>
			@elseif(false)
			<!--交易完成-->
				<div class="orderDHead">
					<div class="odrHeadLeft">
						<img src="{{ asset('static_m/img/icon_wait.png') }}"/>
						<span>交易完成</span>
					</div>
				</div>
			@elseif(false)
			<!--卖家已发货，等待买家收货-->
				<div class="orderDHead">
					<div class="odrHeadLeft">
						<img src="{{ asset('static_m/img/icon_wait.png') }}"/>
						<span>卖家已发货，等待买家收货</span>
						<p class="odrLeftS">剩余9天1小时  自动确认</p>
					</div>
				</div>
			@endif
		<div class="ordUser">
			@if(false)
			<!--查看物流-->
			<div class="orderUserLogistics">
				<img src="{{ asset('static_m/img/icon_Delivery.png') }}" alt="" />
				<div class="ordUserInfoRight">
					<div class="logisticsBox">
						<span>【青岛市】快件已从青岛四方区南昌路31号发出，准备发往四方</span>
					</div>
					<div class="logisticsDate">
						2010-10-22 01:22:13
					</div>
					<img src="{{ asset('static_m/img/icon_more.png') }}"/>
				</div>
			</div>
			
			@endif
			<div class="ordUserInfo">
				<img src="{{ asset('static_m/img/icon_address.png') }}" alt="" />
				<div class="ordUserInfoRight">
					<div>
						<span>胡一天</span>
						<label>152****8012</label>
					</div>
					<div>
						地址：山东省青岛市四方区南昌路31号家丁山花14号楼2单元402
					</div>
				</div>
			</div>
		</div>
		<div class="ordDetail">
			<img src="{{ asset('static_m/img/blockImg.png') }}"/>
			<div>
				<div class="ordDetailName">卓业美业长直假发片卓业美业长直假发片卓业美业长直假发片卓业美业长直假发片</div>
				<div>
					<span>数量：2</span>
					<span>颜色：黄</span>
				</div>
				<div class="ordDetailPri">￥500.00</div>
			</div>
		</div>
		<div class="ordDetailCode">
			<div>订单编号：48554545777</div>
			<div>下单时间: 2018-12-23 12:22:00</div>
		</div>
		<div class="ordPriBox">
			<div class="ordPriItem">
				<label>商品总额</label>
				<label>￥500.00</label>
			</div>
			<div class="ordPriItem">
				<label>运费</label>
				<label>￥0.00</label>
			</div>
		</div>
		<div class="ordDetailRealPri">
			<label>需付款:</label>
			<span>￥500.00</span>
		</div>
		<div class="ordDetailBtn">
			<button class="ordDetailBtnC">取消订单</button>
			<button class="ordDetailBtnS">立即付款</button>
		</div>
	</div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
