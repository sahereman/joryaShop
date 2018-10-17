@extends('layouts.app')
@section('title', '个人中心-收货地址')
@section('content')
@include('common.error')
<div class="User_addresses">
	<div class="m-wrapper">
		<div>
	 		<p class="Crumbs">
	 			<a href="{{ route('root') }}">首页</a>
	 			<span>></span>
	 			<a href="{{ route('users.home') }}">个人中心</a>
	 			<span>></span>
	 			<a href="{{ route('user_addresses.index') }}">收货地址</a>
	 		</p>
	 	</div>
	 	<!--左侧导航栏-->
	 	@include('users._left_navigation')
	 	<!--右侧内容-->
	 	<div class="user_addresses_content">
	 		<!--当没有收获地址列表时显示,如需显示当前内容需要调整一下样式-->
	 		<div class="no_addressList">
	 			<img src="{{ asset('img/location.png') }}">
	 			<p>您还没有收货地址</p>
	 			<a class="new_address">新建收货地址</a>
	 		</div>
	 		<!--存在收获地址列表-->
	 		<div class="receive_address">
	 			<div class="address_note">
	 				<div class="pull-left">
	 					<p>已保存收货地址（地址最多20条，还能保存<span class="residual">18</span>条）</p>
	 				</div>
	 				<div class="pull-right">
	 					<a class="new_address">+新建地址</a>
	 				</div>
	 			</div>
	 			<!--地址列表-->
	 			<div class="address_list">
	 				<table>
	 					<thead>
	 						<tr>
	 							<th class="address_name">收货人</th>
	 							<th class="address_info">地址</th>
	 							<th class="address_tel">联系方式</th>
	 							<th class="address_operation">操作</th>
	 							<th class="default_address"></th>
	 						</tr>
	 					</thead>
	 					<tbody>
	 						@for ($i = 0; $i < 5; $i++)
	 							<tr>
	 								<td class="address_name">谈某某</td>
	 								<td class="address_info">山东省青岛市李沧区青山路237号福临万家14号楼7单元</td>
	 								<!--电话建议后台正则处理前端处理容易泄露-->
	 								<td class="address_tel">154****0021</td>
	 								<td class="address_operation">
	 									<a>编辑</a>
	 									<a>删除</a>
	 								</td>
	 								<td class="default_address">
	 									<a>默认地址</a>
	 									<!--<a>设为默认地址</a>-->
	 								</td>
	 							</tr>
	 						@endfor
	 					</tbody>
	 				</table>
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
            $(".user_address").addClass("active");
            //点击新建收获地址
            $(".new_address").on("click",function(){
            	
            })
        });
    </script>
@endsection