@extends('layouts.app')
@section('title', '个人中心')
@section('content')
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
                    @auth
                    <li>
                        <div class="user_img">
                            <img src="{{ $user->avatar_url }}">
                        </div>
                        <div class="user_name">
                            <span>昵称：{{ $user->name }}</span>
                            <a href="{{ route('users.edit', $user->id) }}">修改个人信息></a>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('user_favourites.index') }}">
                            <span>我的收藏</span>
                            <img src="{{ asset('img/collection.png') }}">
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_addresses.index') }}">
                            <span>收货地址</span>
                            <img src="{{ asset('img/receive_address.png') }}">
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}">
                            <span>我的订单</span>
                            <img src="{{ asset('img/record.png') }}">
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_histories.index') }}">
                            <span>浏览历史</span>
                            <img src="{{ asset('img/history_record.png') }}">
                        </a>
                    </li>
                    @endauth
                </ul>
                <ul class="order_classification">
                    <li>
                        <a href="{{ route('orders.index') . '?status=paying' }}">
                            <img src="{{ asset('img/tobe_paid.png') }}">
                            <span>待付款</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=receiving' }}">
                            <img src="{{ asset('img/tobe_received.png') }}">
                            <span>待收货</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=uncommented' }}">
                            <img src="{{ asset('img/tobe_evaluated.png') }}">
                            <span>待评价</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=refunding' }}">
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
                        <span>订单状态</span>
                    </li>
                    <li class="order_operation">
                        <span>操作</span>
                    </li>
                </ul>
                <!--订单列表分为两部分，1、暂无订单时展现其他时候隐藏。2、存在订单时显示.需进行判断-->
                <div class="order_list">
                    @if($orders->isEmpty())
                            <!--暂无订单部分-->
                    <div class="no_order">
                        <img src="{{ asset('img/no_order.png') }}">
                        <p>还没有任何订单哦~</p>
                        <a href="{{ route('root') }}">去逛逛</a>
                    </div>
                    @else
                            <!--订单部分-->
                    <div class="order-group">
                        @foreach($orders as $order)
                            <div class="order-group-item">
                                <div class="o-info">
                                    <div class="col-info pull-left">
                                     <span class="o-no">
                                         订单编号：
                                         <a href="{{ route('orders.show', $order->id) }}">{{ $order->order_sn }}</a>
                                     </span>
                                    </div>
                                    @if(in_array($order->status, [\App\Models\Order::ORDER_STATUS_CLOSED, \App\Models\Order::ORDER_STATUS_COMPLETED]))
                                        <div class="col-delete pull-right"
                                             code="{{ route('orders.destroy', $order->id) }}">
                                            <a>
                                                <img src="{{ asset('img/delete.png') }}">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="o-pro">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                        @foreach($order->items as $key => $item)
                                        @if($key == 0)
                                                <!--当循环的子订单数量为1时第一个tr整体作为一个单独的模板进行渲染，超过两个时请看第二个tr前的注释-->
                                        <tr>
                                            <td class="col-pro-img">
                                                <p class="p-img">
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">
                                                        <img src="{{ $item->sku->product->thumb_url }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a code="{{ $item->sku->id }}" href="{{ route('products.show', $item->sku->product->id) }}">{{ $item->sku->product->name_zh }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    <em>¥</em>
                                                    <span>{{ $item->price }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">1</td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-pay">
                                                <p>
                                                    <em>¥</em>
                                                    <span>{{ $order->total_amount }}</span>
                                                </p>
                                            </td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-status">
                                                <p>{{ $order->translateStatus($order->status) }}</p>
                                            </td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-operate">
                                                <p class="p-button">
                                                    @if($order->status == 'completed' && $order->commented_at == null)
                                                        <a class="evaluate"
                                                           href="{{ route('orders.create_comment', $order->id) }}">评价</a>
                                                    @endif
                                                    <a class="buy_more" data-url="{{ route('carts.store') }}" href="javascript:void(0)">再次购买</a>
                                                </p>
                                            </td>
                                        </tr>
                                        @endif
                                        @if($key > 0)
                                                <!--当循环的数据中超过两个子订单时从第二个子订单开始采用这种布局-->
                                        <tr class="order_top">
                                            <td class="col-pro-img">
                                                <p class="p-img">
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">
                                                        <img src="{{ $item->sku->product->thumb_url }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a code="{{ $item->sku->id }}" href="{{ route('products.show', $item->sku->product->id) }}">{{ $item->sku->product->name_zh }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    <em>¥</em>
                                                    <span>{{ $item->price }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">1</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--分页-->
                    <!--<div class="paging_box">
                        <a class="pre_page" href="javascript:void(0)">上一页</a>
                        <a class="next_page" href="javascript:void(0)">下一页</a>
                    </div>-->
                    @endif
                </div>
                <!--猜你喜欢-->
                <div class="guess_like">
                    <div class="ordertable_title">
                        <p>猜你喜欢</p>
                    </div>
                    <div class="guess_like_content">
                        <ul>
                            @foreach($guesses as $guess)
                                <li>
                                    <div class="collection_shop_img">
                                        <img src="{{ $guess->thumb_url }}">
                                    </div>
                                    <p class="commodity_title">{{ $guess->name_zh }}</p>
                                    <p class="collection_price">
                                        <span class="new_price">¥ {{ $guess->price }}</span>
                                        <span class="old_price">¥ {{ bcmul($guess->price, 1.2, 2) }}</span>
                                    </p>
                                    <a class="add_to_cart" href="{{ route('products.show', $guess->id) }}">查看详情</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--是否确认删除弹出层-->
    <div class="dialog_popup order_delete">
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
                        <span class="tips_content">确定要删除订单信息？</span>
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
        	var allHadAdd = 0;  //用来判断是否已经订单找那个全部的商品添加至购物车中
        	var shops_list;  //单个订单中包含的商品的数量,用于再次购买时判断时候可以进行跳页
        	var loading_animation;  //loading动画的全局name
            $(".navigation_left ul li").removeClass("active");
            $(".user_index").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete .textarea_content").find("span").attr("code", $(this).attr("code"));
                $(".order_delete").show();
            });
            $(".order_delete").on("click", ".success", function () {
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(".textarea_content span").attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
            //再次购买
            $(".buy_more").on("click",function(){
            	shops_list = $(this).parents("table").find("tr");
            	var sku_id_lists = "";  //用于页面跳转在购物车页面通过判断这个参数的值选中商品
            	var sku_id;
            	var number;
            	var url = $(this).attr("data-url");
            	$.each(shops_list, function(i,n) {
            		sku_id = $(n).find(".p-info").find("a").attr("code");
            		number = $(n).find(".col-quty").html();
            		sku_id_lists+=$(n).find(".p-info").find("a").attr("code")+",";
            		allHadAdd++;
            		add_to_carts(sku_id,number,url,sku_id_lists,allHadAdd);
            	});
            })
            //添加购物车
            function add_to_carts(sku_id,number,url,sku_id_lists,allHadAdd){
            	var data = {
        			_token: "{{ csrf_token() }}",
        			sku_id: sku_id,
        			number: number
        		}
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function(){
        			loading_animation = layer.msg('请稍候', {
			                icon: 16,
			                shade: 0.4,
			                time:false //取消自动关闭
						});
	        		},
                    success: function (data) {
                    	if(allHadAdd==shops_list.length){
                    		window.location.href=url+"?sku_id_lists="+sku_id_lists;	
                    	}
                    },
                    error: function (err) {
                        console.log(err);
                    },
                    complete:function(){
                    	if(allHadAdd==shops_list.length){
                    		layer.close(loading_animation);
                    	}
                    }
                });
            }
        });
    </script>
@endsection
