@extends('layouts.mobile')
@section('title', '个人中心')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}


     {{--<a href="{{ route('mobile.root') }}">首页</a> 

    <a href="{{ route('mobile.users.edit',Auth::id()) }}">编辑个人信息</a> 

    <a href="{{ route('mobile.users.password',Auth::id()) }}">修改密码</a> /

    <a href="{{ route('mobile.users.setting',Auth::id()) }}">设置</a> /

    <a href="{{ route('mobile.user_addresses.index') }}">收货地址 列表</a> /
    <a href="{{ route('mobile.user_addresses.create') }}">收货地址 新增</a> /


    <a href="{{ route('mobile.orders.index') }}">我的订单 列表</a> /
    <a href="{{ route('mobile.orders.show',\App\Models\Order::where('user_id',Auth::id())->first()) }}"> 我的订单 详情</a> /

    <a href="{{ route('mobile.orders.create_comment',\App\Models\Order::where('user_id',Auth::id())->where('status',\App\Models\Order::ORDER_STATUS_COMPLETED)->first()) }}"> 创建评价</a> /
    <a href="{{ route('mobile.orders.show_comment',\App\Models\Order::where('user_id',Auth::id())->where('status',\App\Models\Order::ORDER_STATUS_COMPLETED)->first()) }}"> 查看评价</a> /--}}


	<div class="userBox">
		<div class="userH">
			<div class="userHTop">
				<img src="{{ asset('static_m/img/icon_search2.png') }}" class="searchImg"/>
				<span>个人中心</span>
				<img src="{{ asset('static_m/img/icon_setting.png') }}" class="setImg"/>
			</div>
			<div class="userHMain">
				<img src="{{ asset('static_m/img/icon_Headportrait1.png') }}" class="userHeaderImg"/>
				<div class="userInfo">
					<span>谭某某</span>
					<img src="{{ asset('static_m/img/icon_Editpersonal.png') }}"/>
					<p>152****5561</p>
				</div>
			</div>
		</div>
		<div class="userOrder">
			<div class="userOdrHead">
				<span>我的订单</span>
				<a href="{{ route('mobile.orders.index') }}">查看全部订单></a>
			</div>
			<div class="userOrderMain">
				<a href="">
					<div class="userOrderMainItem">
						<img src="{{ asset('static_m/img/icon_Pendingpayment.png') }}"/>
						<span>待付款</span>
					</div>
				</a>
				<a href="">
					<div class="userOrderMainItem">
						<label class="num">3</label>
						<img src="{{ asset('static_m/img/icon_Goodstobereceived2.png') }}"/>
						<span>待收货</span>
					</div>
				</a>
				<a href="">
					<div class="userOrderMainItem">
						<img src="{{ asset('static_m/img/icon_stayevaluate.png') }}"/>
						<span>待评价</span>
					</div>
				</a>
				<a href="">
					<div class="userOrderMainItem">
						<img src="{{ asset('static_m/img/icon_After-saleorder.png') }}"/>
						<span>售后订单</span>
					</div>
				</a>
				
			</div>
		</div>
		<div class="userItemBox">
			<a href="{{ route('mobile.user_favourites.index') }}">
				<div class="userItem userItemCollection">
					<img src="{{ asset('static_m/img/icon_Collection.png') }}" class="userItemImgF"/>
					<span>我的收藏</span>
					<img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
				</div>
			</a>
			<a href="{{ route('mobile.user_addresses.index') }}">
				<div class="userItem userItemAddress">
					<img src="{{ asset('static_m/img/icon_Myaddress.png') }}" class="userItemImgD"/>
					<span>我的地址</span>
					<img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
				</div>
			</a>
			<a href="{{ route('mobile.user_histories.index') }}">
				<div class="userItem userItemFootprint">
					<img src="{{ asset('static_m/img/icon_MyTracks.png') }}" class="userItemImgF"/>
					<span>我的足迹</span>
					<img src="{{ asset('static_m/img/icon_more.png') }}" class="userItemBack"/>
				</div>
			</a>
			
		</div>
	</div>


    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
		$(".itemsF").removeClass("itemsActive");
		$(".itemsW").addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Unchecked_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Select_my.png') }}");
        $(".userHMain").on("click",function(){
        	//跳转修改个人信息页面
        	window.location.href = "{{route('mobile.users.edit',Auth::id())}}";
        });
        $(".setImg").on("click",function(){
        	//跳转设置页面
        	window.location.href = "{{route('mobile.users.setting',Auth::id())}}";
        });
        $(".searchImg").on("click",function(){
        	window.location.href = "{{route('mobile.search')}}";
        });
    </script>
@endsection
