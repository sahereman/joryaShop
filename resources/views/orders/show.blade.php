@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    <div class="orders_details">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">我的订单</a>
                    <span>></span>
                    <a href="#">订单详情</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="order_content">
                <div class="order_info">
                    <!--订单状态，根据订单状态不同将显示不同的按钮

                        **注：有判断机制之后将每一个div后的去掉！！！、
                        *
                        -->
                    <div class="pull-left order_status_opera">
                        @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                <!--待付款状态-->
                        <div class="pending_payment status_area">
                            <p>
                                <img src="{{ asset('img/exclamation.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">等待买家付款</span>
                            </p>
                            <p id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}" class="cunt_down paying_time"
                               created_at="{{ strtotime($order->created_at) }}"
                               time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 3600 }}">{{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
                                支付（若超时未支付订单，系统将自动取消订单）</p>
                            <p class="operation_area">
                                <a class="main_operation"
                                   href="{{ route('orders.payment_method', $order->id) }}">立即付款</a>
                                <a data-url="{{ route('orders.close', $order->id) }}">取消</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                <!--待发货状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">买家已付款，等待卖家发货</span>
                            </p>
                            <p class="operation_area">
                                <a>提醒发货</a>
                                <a class="main_operation" href="{{ route('orders.refund', $order->id) }}">申请退款</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                <!--待收货状态-->
                        <div class="pending_payment status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">卖家已发货，等待买家收货 </span>
                            </p>
                            <p id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}" class="cunt_down tobe_received_count"
                               shipped_at="{{ strtotime($order->shipped_at) }}"
                               time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}">{{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}
                                确认（若超时未确认订单，系统将自动确认订单）</p>
                            <p class="operation_area">
                                <a class="main_operation" data-url="{{ route('orders.complete', $order->id) }}">确认收货</a>
                                <a href="{{ route('orders.refund_with_shipment', $order->id) }}">申请退款</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                <!--待评价状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">交易完成</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation" href="{{ route('orders.create_comment', $order->id) }}">去评价</a>
                                <a class="delete_order" data-url="{{ route('orders.destroy', $order->id) }}">删除订单</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                <!--已评价状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">交易完成</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation" href="{{ route('orders.show_comment', $order->id) }}">查看评价</a>
                                <a class="delete_order" data-url="{{ route('orders.destroy', $order->id) }}">删除订单</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                <!--订单关闭-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">交易已关闭</span>
                            </p>
                            <p class="operation_area">
                                <a class="delete_order" data-url="{{ route('orders.destroy', $order->id) }}">删除订单</a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                                <!--售后订单-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>订单状态：</span>
                                <span class="order_status_tips">售后中</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation revocation_after_sale" data-url="{{ route('orders.revoke_refund', $order->id) }}">撤销售后</a>
                            </p>
                        </div>
                        @endif
                    </div>
                    <!--订单信息-->
                    <div class="pull-left number_infor">
                        <p>
                            <span>订单时间：</span>
                            <span>{{ $order->created_at }}</span>
                        </p>
                        <p>
                            <span>订单编号：</span>
                            <span>{{ $order->order_sn }}</span>
                        </p>
                        <p>
                            <span>收货人：</span>
                            <span>{{ $order->user_info['name'] }}</span>
                        </p>
                        <p>
                            <span>收货地址：</span>
                            <span>{{ $order->user_info['address'] }}</span>
                        </p>
                    </div>
                </div>
                <!--物流信息根据需要判断是否显示，
                    **目前显示的订单状态：待收货、未评价、已评价、退款订单
                    *
                    -->
                @if(!empty($order_shipment_information))
                    <div class="logistics_infor">
                        <p class="logistics_title">物流信息</p>
                        <ul class="logistics_lists">
                            <li>
                                <span>发货方式：</span>
                                <span>快递</span>
                            </li>
                            <li>
                                <span>物流公司：</span>
                                <span>圆通公司</span>
                            </li>
                            <li>
                                <span>运单号码：</span>
                                <span>3832863910951</span>
                            </li>
                            <li>
                                <span>物理跟踪：</span>
                            </li>
                            <li>
                                <span>2018-08-29 11:29:31卖家发货</span>
                            </li>
                            <li>
                                <span>2018-08-29 21:52:38【汕头市】韵达快递 广东汕头潮阳区公司潮南区峡山分部收件员 已揽件</span>
                            </li>
                            <li>
                                <span>2018-08-29 21:56:12【汕头市】广东汕头潮阳区公司潮南区峡山分部 已发出</span>
                            </li>
                            <li>
                                <span>2018-08-30 05:56:26【汕头市】快件已到达 广东揭阳分拨中心</span>
                            </li>
                            <li>
                                <span>2018-08-30 05:57:35【汕头市】广东揭阳分拨中心 已发出</span>
                            </li>
                            <li>
                                <span>2018-08-31 13:04:30【潍坊市】快件已到达 山东潍坊分拨中心</span>
                            </li>
                            <li>
                                <span>2018-08-31 13:07:14【潍坊市】山东潍坊分拨中心 已发出</span>
                            </li>
                            <li>
                                <span>2018-08-31 13:07:14【潍坊市】山东潍坊分拨中心 已发出</span>
                            </li>
                            <li>
                                <span>2018-08-31 13:07:14【潍坊市】山东潍坊分拨中心 已发出</span>
                            </li>
                            <li>
                                <span>2018-08-31 13:07:14【潍坊市】山东潍坊分拨中心 已发出</span>
                            </li>
                        </ul>
                    </div>
                    @endif

                            <!--订单商品列表-->
                    <div class="order_list">
                        <!--订单表格与我的订单首页的判断方式一样-->
                        <table>
                            <thead>
                            <th></th>
                            <th>商品</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>小计</th>
                            <th>订单状态</th>
                            </thead>
                            <tbody>
                            @foreach($order->snapshot as $key => $order_item)
                                @if($key == 0)
                                    <tr>
                                        <td class="col-pro-img">
                                            <a href="">
                                                <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                            </a>
                                        </td>
                                        <td class="col-pro-info">
                                            <p class="p-info">
                                                <a class="commodity_description"
                                                   href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['product']['name_zh'] }}</a>
                                                <br><br>
                                                <a class="commodity_description"
                                                   href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['name_zh'] }}</a>
                                            </p>
                                        </td>
                                        <td class="col-price">
                                            <p class="p-price">
                                                <em>¥</em>
                                                <span>{{ number_format($order_item['price'], 2) }}</span>
                                            </p>
                                        </td>
                                        <td class="col-quty">
                                            <p>{{ $order_item['number'] }}</p>
                                        </td>
                                        <td rowspan="{{ count($order->snapshot) }}" class="col-pay">
                                            <p>
                                                <em>¥</em>
                                                <span>{{ number_format($order->total_amount, 2) }}</span>
                                            </p>
                                        </td>
                                        <td rowspan="{{ count($order->snapshot) }}" class="col-status">
                                            <p>{{ $order->translateStatus($order->status) }}</p>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="col-pro-img">
                                            <a href="">
                                                <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                            </a>
                                        </td>
                                        <td class="col-pro-info">
                                            <p class="p-info">
                                                <a class="commodity_description"
                                                   href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['product']['name_zh'] }}</a>
                                                <br><br>
                                                <a class="commodity_description"
                                                   href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ $order_item['sku']['name_zh'] }}</a>
                                            </p>
                                        </td>
                                        <td class="col-price">
                                            <p class="p-price">
                                                <em>¥</em>
                                                <span>{{ number_format($order_item['price'], 2) }}</span>
                                            </p>
                                        </td>
                                        <td class="col-quty">
                                            <p>{{ $order_item['number'] }}</p>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <div class="order_settlement">
                            <p class="commodity_cost">
                                <span>商品合计：</span>
                                <span>¥ {{ number_format($order->total_amount, 2) }}</span>
                            </p>
                            <p class="freight">
                                <span>运  费：</span>
                                <span>¥ {{ number_format($order->total_shipping_fee, 2) }}</span>
                            </p>
                            <p class="total_cost">
                                <span>应付总额：</span>
                                <span class="cost_of_total">¥ {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
                            </p>
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
                        <span>确定要删除订单信息？</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
    <!--是否撤销售后出层-->
    <div class="dialog_popup order_after_sale">
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
                        <span>确定要撤销售后申请？</span>
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
            $(".my_order").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete").show();
            });
            //待付款订单
             $(".paying_time").each(function (index, element) {
                var val = $(this).attr("mark");
                var start_time = $(this).attr("created_at") * 1000;
                var ending_time = $(this).attr('time_to_close_order');
                timeCount(val, start_time, ending_time, '1');
            });
            //待收货订单
            $(".tobe_received_count").each(function (index, element) {
                var val = $(this).attr("mark");
                var start_time = $(this).attr("shipped_at") * 1000;
                var ending_time = $(this).attr('time_to_complete_order');
                timeCount(val, start_time, ending_time, "2");
            });
             //倒计时方法封装
	        function timeCount(remain_id, start_time, ending_time, type) {
	            function _fresh() {
	                var nowDate = new Date(); //当前时间
	                var id = $('#' + remain_id).attr("order_id"); //当前订单的id
	                var addTime = new Date(parseInt(start_time));               //返回的时间戳转换成时间格式
	                var auto_totalS = ending_time; //订单支付有效时长
	                var ad_totalS = parseInt((addTime.getTime() / 1000) + auto_totalS); ///下单总秒数
	                var totalS = parseInt(ad_totalS - (nowDate.getTime() / 1000)); ///支付时长
	                if (totalS > 0) {
	                    var _day = parseInt((totalS / 3600) % 24 / 24);
	                    var _hour = parseInt((totalS / 3600) % 24);
	                    var _minute = parseInt((totalS / 60) % 60);
	                    var _second = parseInt(totalS % 60);
	                    if (type == '1') {
	                        $('#' + remain_id).html('剩余' + _hour + '时' + _minute + '分' + _second + '秒支付（若超时未支付订单，系统将自动取消订单）');
	                    } else {
	                        $('#' + remain_id).html('剩余' + _day + '天' + _hour + '时' + _minute + '分确认（若超时未确认订单，系统将自动确认订单）');
	                    }
	                }
	            }
	            _fresh();
	            var sh = setInterval(_fresh, 1000);
	        }
	        //删除订单
	        $(".delete_order").on('click',function(){
	        	$(".order_delete .textarea_content").find("span").attr("code", $(this).attr("data-url"));
                $(".order_delete").show();
	        })
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
                        window.location.href = "{{ route('orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                        	layer.open({
							  type: 1, 
							  content: '您无权限执行此操作！' 
							});
                        }
                    }
                });
            });
            //撤销售后
            $('.revocation_after_sale').on("click",function(){
            	$(".order_delete .textarea_content").find("span").attr("code", $(this).attr("data-url"));
            	$('.order_after_sale').show()
            })
            $(".order_after_sale").on("click", ".success", function () {
                var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(".textarea_content span").attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.href = "{{ route('orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                        	layer.open({
							  type: 1, 
							  content: '您无权限执行此操作！' 
							});
                        }
                    }
                });
            });
        });
    </script>
@endsection
