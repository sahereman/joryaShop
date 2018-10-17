@extends('layouts.app')
@section('title', '个人中心')
@section('content')
@include('common.error')
<div class="User_center">
	 <div class="m-wrapper">
	 	<div>
	 		<p class="Crumbs">
	 			<a href="{{ route('root') }}">首页</a>
	 			<span>></span>
	 			<a href="{{ route('users.home') }}">个人中心</a>
	 		</p>
	 	</div>
	 	<!--左侧导航栏-->
	 	@include('users._left_navigation')
	 	<!--右侧内容-->
	 	<div class="UserInfo_content">
	 		<ul class="userInfo_list">
	 			@guest
			    @else
			    	<li>
			    		<div class="user_img">
					    	<img src="{{ Auth::user()->avatar_url }}">
					    </div>
					    <div class="user_name">
					    	<span>昵称：{{ Auth::user()->name }}</span>
					    	<a href="{{ route('root') }}">修改个人信息></a>
					    </div>	
			    	</li>
			    	<li>
			    		<a href="{{ route('root') }}">
			    			<span>我的收藏</span>
			    		    <img src="{{ asset('img/collection.png') }}">
			    		</a>
			    	</li>
			    	<li>
			    		<a href="{{ route('root') }}">
				    		<span>收货地址</span>
				    		<img src="{{ asset('img/receive_address.png') }}">
			    	    </a>
			    	</li>
			    	<li>
			    		<a href="{{ route('root') }}">
				    		<span>成交记录</span>
				    		<img src="{{ asset('img/record.png') }}">
			    		</a>
			    	</li>
			    	<li>
			    		<a href="{{ route('root') }}">
				    		<span>浏览历史</span>
				    		<img src="{{ asset('img/history_record.png') }}">
				    	</a>
			    	</li>
			    @endguest		
	 		</ul>
	 		<ul class="order_classification">
	 			<li>
	 				<a href="{{ route('root') }}">
	 					<img src="{{ asset('img/tobe_paid.png') }}">
	 				    <span>待付款</span>
	 				</a>
	 			</li>
	 			<li>
	 				<a href="{{ route('root') }}">
	 					<img src="{{ asset('img/tobe_received.png') }}">
	 				    <span>待收货</span>
	 				</a>
	 			</li>
	 			<li>
	 				<a href="{{ route('root') }}">
	 					<img src="{{ asset('img/tobe_evaluated.png') }}">
	 				    <span>待评价</span>
	 				</a>
	 			</li>
	 			<li>
	 				<a href="{{ route('root') }}">
	 					<img src="{{ asset('img/after-sale.png') }}">
	 				    <span>售后订单</span>
	 				</a>
	 			</li>
	 		</ul>
	 		<ul class="ordertable_title">
	 			<li class="order_details">
	 				<span>订单详情</span>
	 			</li>
	 			<li class="order_price">
	 				<span>单价</span>
	 			</li>
	 			<li class="order_num">
	 				<span>数量</span>
	 			</li>
	 			<li class="order_pay">
	 				<span>实付款</span>
	 			</li>
	 			<li class="order_status">
	 				<span>交易状态</span>
	 			</li>
	 			<li class="order_operation">
	 				<span>交易操作</span>
	 			</li>
	 		</ul>
	 		<!--订单列表分为两部分，1、暂无订单时展现其他时候隐藏。2、存在订单时显示.需进行判断-->
	 		<div class="order_list">
	 			<!--暂无订单部分-->
	 			<div class="no_order">
	 				<img src="{{ asset('img/no_order.png') }}">
	 				<p>还没有任何订单哦~</p>
	 				<a href="{{ route('root') }}">去逛逛</a>
	 			</div>
	 			<!--订单部分-->
	 			<div class="order-group">
	 				@for ($i = 0; $i < 5; $i++)
		 				<div class="order-group-item">
		 					<div class="o-info">
		 						<div class="col-info pull-left">
		 							<span class="o-no">
		 								订单编号：
		 								<a href="{{ route('root') }}">13161641651441565564</a>
		 							</span>
		 						</div>
		 						<div class="col-delete pull-right">
		 							<a>
		 								<img src="{{ asset('img/delete.png') }}">
		 							</a>
		 						</div>
		 					</div>
		 					<div class="o-pro">
		 						<table border="0" cellpadding="0" cellspacing="0">
		 							<tbody>
		 								<!--当循环的子订单数量为1时第一个tr整体作为一个单独的模板进行渲染，超过两个时请看第二个tr前的注释-->
		 								<tr>
		 									<td class="col-pro-img">
		 										<p class="p-img">
		 											<a href="{{ route('root') }}">
		 												<img src="{{ asset('img/order-pro.png') }}">
		 											</a>
		 										</p>
		 									</td>
		 									<td class="col-pro-info">
		 										<p class="p-info">
		 											<a href="{{ route('root') }}">卓页美业长直假发片</a>
		 										</p>
		 									</td>
		 									<td class="col-price">
		 										<p class="p-price">
		 											<em>¥</em>
		 											<span>50.00</span>
		 										</p>
		 									</td>
		 									<td class="col-quty">1</td>
		 									<td rowspan="2" class="col-pay">
		 										<p>
		 											<em>¥</em>
		 											<span>50.00</span>
		 										</p>
		 									</td>
		 									<td rowspan="2" class="col-status">
		 										<p>交易成功</p>
		 									</td>
		 									<td rowspan="2" class="col-operate">
		 										<p class="p-button">
		 											<a class="evaluate">评价</a>
		 											<a>再次购买</a>
		 										</p>
		 									</td>
		 								</tr>
		 								<!--当循环的数据中超过两个子订单时从第二个子订单开始采用这种布局-->
		 								<tr class="order_top">
		 									<td class="col-pro-img">
		 										<p class="p-img">
		 											<a href="{{ route('root') }}">
		 												<img src="{{ asset('img/order-pro.png') }}">
		 											</a>
		 										</p>
		 									</td>
		 									<td class="col-pro-info">
		 										<p class="p-info">
		 											<a href="{{ route('root') }}">卓页美业长直假发片</a>
		 										</p>
		 									</td>
		 									<td class="col-price">
		 										<p class="p-price">
		 											<em>¥</em>
		 											<span>50.00</span>
		 										</p>
		 									</td>
		 									<td class="col-quty">1</td>
		 								</tr>
		 							</tbody>
		 						</table>
		 					</div>
		 				</div>
	 				@endfor
	 			</div>
	 		</div>
	 	</div>
	 </div>
</div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".user_index").addClass("active");
        });
    </script>
@endsection
