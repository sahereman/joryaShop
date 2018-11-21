@extends('layouts.mobile')
@section('title', '物流详情')
@section('content')
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>物流详情</span>
	</div>
	<div class="logisticsBox">
		<div class="lgtHead">
			<div class="lgtHeadMain">
				<img src="{{ asset('static_m/img/icon_express.png') }}"/>
				<div class="lgtHeadInfo">
					<div>中通快递</div>
					<div class="lgtHeadInfoCode">运单号:14545121255875414545</div>
				</div>
			</div>
		</div>
		<div class="lgtCon">
			<div class="lgtConItem">
				<div class="lgtConItemDate"></div>
				<div class="lgtConRight">
					<img src="{{ asset('static_m/img/icon_Collectgoods.png') }}"/>
					<div class="lgtConRightMain">
						<span></span>
						<span>【收货地址】安徽省图图市包图区图图街道 图图车终点站对面大耳朵图图书店</span>
					</div>
				</div>
			</div>
			<div class="lgtConItem">
				<div class="lgtConItemDate">
					<div>05-09</div>
					<div class="lgtConItemDateTime">11:47</div>
				</div>
				<div class="lgtConRight">
					<img src="{{ asset('static_m/img/icon_Indelivery.png') }}"/>
					<div class="lgtConRightMain lgtConRightFrist">
						<div>运输中</div>
						<span>【青岛市】快件离开青岛市中转部已发往东莞</span>
					</div>
				</div>
			</div>
			@for($i = 0; $i <5;$i++)
				<div class="lgtConItem lgtConItemC">
					<div class="lgtConItemDate">
						<div>05-09</div>
						<div class="lgtConItemDateTime">11:47</div>
					</div>
					<div class="lgtConRight">
						<div class="dot"></div>
						<div class="lgtConRightMain">
							<div></div>
							<span>【青岛市】快件已到达青岛市市中转部</span>
						</div>
					</div>
				</div>
			@endfor
			<div class="lgtConItem lgtConItemLast">
				<div class="lgtConItemDate">
					<div>05-09</div>
					<div class="lgtConItemDateTime">11:47</div>
				</div>
				<div class="lgtConRight">
					<img src="{{ asset('static_m/img/icon_Alreadyordered.png') }}"/>
					<div class="lgtConRightMain">
						<div>已下单</div>
						<span>【包子市】包子市的百斯特已揽件</span>
					</div>
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
