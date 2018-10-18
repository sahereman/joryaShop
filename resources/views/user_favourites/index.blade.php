@extends('layouts.app')
@section('title', '个人中心-我的收藏')
@section('content')
@include('common.error')
<div class="User_collection">
	<div class="m-wrapper">
		<div>
	 		<p class="Crumbs">
	 			<a href="{{ route('root') }}">首页</a>
	 			<span>></span>
	 			<a href="{{ route('users.home') }}">个人中心</a>
	 			<span>></span>
	 			<a href="{{ route('user_favourites.index') }}">我的收藏</a>
	 		</p>
	 	</div>
	 	<!--左侧导航栏-->
	 	@include('users._left_navigation')
	 	<!--右侧内容-->
	 	<div class="user_collection_content">
	 		<!--当没有收藏列表时显示,如需显示当前内容需要调整一下样式-->
	 		<div class="no_collectionList">
	 			<img src="{{ asset('img/no_collection.png') }}">
	 			<p>还没有任何收藏哦~</p>
	 			<a class="new_address" href="{{ route('root') }}">去逛逛</a>
	 		</div>
	 		<!--存在收藏列表-->
	 		<div class="receive_collection">
	 			<!--收藏列表-->
	 			<div class="address_list">
	 				<ul>
	 					@for ($i = 0; $i < 8; $i++)
	 						<li>
	 							<div class="collection_shop_img">
	 								<img src="{{ asset('img/collection_history.png') }}">
	 							</div>
	 							<p class="commodity_title">时尚渐变色</p>
	 							<p class="collection_price">  
	 							    <span class="new_price">￥2556.00</span>
                                    <span class="old_price">￥580.00</span>
                                </p>
                                <a class="add_to_cart" href="">加入购物车</a>
                                <a class="delete_mark" title="点击删除该商品"></a> 
	 						</li>
	 					@endfor
	 				</ul>
	 			</div>
	 		</div>
	 	</div>
	</div>
</div>
<!--是否确认删除弹出层-->
<div class="dialog_popup confirm_delete">
	<div class="dialog_content">
		<div class="close">
            <i></i>
        </div>
        <div class="dialog_textarea">
        	<div class="textarea_title">
        		<span>提示</span>
        	</div>
        	<div class="textarea_content">
        		<p>
        			<img src="{{ asset('img/warning.png') }}">
        			<span>确定要删除此商品？</span>
        		</p>
        	</div>
        </div>
        <div class="btn_area">
        	<a class="cancel">取消</a>
        	<a class="success">确定</a>
        </div>
	</div>
</div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_collection").addClass("active");
            //点击表格中的删除
            $(".address_list ul").on("click",".delete_mark",function(){
            	$(".confirm_delete").show();
            })
        });
    </script>
@endsection