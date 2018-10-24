@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
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
                    <a href="{{ route('orders.index') }}">订单详情</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
            <!--右侧内容-->
            <div class="order_content">
            	<div class="order_info">
            		<!--订单状态，根据订单状态不同将显示不同的按钮
            			
            			**注：有判断机制之后将每一个div后的 style="display: none;"去掉！！！、
            			* 
            			-->
            		<div class="pull-left order_status_opera">
            			<!--待付款状态-->
            			<div class="pending_payment status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/exclamation.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">等待买家付款</span>
            				</p>
            				<p class="cunt_down">还剩下00:58:20支付（若超时未支付订单将自动取消）</p>
            				<p class="operation_area">
            					<a class="main_operation">立即付款</a>
            					<a>取消</a>
            				</p>
            			</div>
            			<!--代发货状态-->
            			<div class="pending_delivery status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">买家已付款，等待卖家发货</span>
            				</p>
            				<p class="operation_area">
            					<a>提醒发货</a>
            					<a class="main_operation">申请退款</a>
            				</p>
            			</div>
            			<!--待收货状态-->
            			<div class="pending_payment status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">卖家已发货，等待买家收货 </span>
            				</p>
            				<p class="cunt_down">还剩下 3天2小时12分 自动确认</p>
            				<p class="operation_area">
            					<a class="main_operation">确认收货</a>
            					<a>申请退款</a>
            				</p>
            			</div>
            			<!--待评价状态-->
            			<div class="pending_delivery status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">交易完成</span>
            				</p>
            				<p class="operation_area">
            					<a class="main_operation" href="{{ route('orders.create_comment', 58) }}">评价</a>
            					<a>删除订单</a>
            				</p>
            			</div>
            			<!--已评价状态-->
            			<div class="pending_delivery status_area" >
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">交易完成</span>
            				</p>
            				<p class="operation_area">
            					<a class="main_operation" href="{{ route('orders.show_comment', 58) }}">查看评价</a>
            					<a>删除订单</a>
            				</p>
            			</div>
            			<!--订单关闭-->
            			<div class="pending_delivery status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">交易已关闭</span>
            				</p>
            				<p class="operation_area">
            					<a>删除订单</a>
            				</p>
            			</div>
            			<!--售后订单-->
            			<div class="pending_delivery status_area" style="display: none;">
            				<p>
            					<img src="{{ asset('img/pending.png') }}">
            					<span>订单状态：</span>
            					<span class="order_status_tips">售后中</span>
            				</p>
            				<p class="operation_area">
            					<a class="main_operation">撤销售后</a>
            				</p>
            			</div>
            			
            		</div>
            		<!--订单信息-->
            		<div class="pull-left number_infor">
            			<p>
            				<span>订单时间：</span>
            				<span>2018-7-29  19:43:41</span>
            			</p>
            			<p>
            				<span>订单编号：</span>
            				<span>5646565654</span>
            			</p>
            			<p>
            				<span>收货人：</span>
            				<span>谭某某</span>
            			</p>
            			<p>
            				<span>收货地址：</span>
            				<span>山东省青岛市市北区敦化路328号诺德广场b607</span>
            			</p>
            		</div>
            	</div>
            	<!--物流信息根据需要判断是否显示，
            		**目前显示的订单状态：待收货、未评价、已评价、退款订单   
            		*
            		-->
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
	                		<tr>
	                			<td class="col-pro-img">
	                				<a href="">
                                        <img src="{{ asset('img/order-pro.png') }}">
                                    </a>
	                			</td>
                                <td class="col-pro-info">
                                    <p class="p-info">
                                        <a class="commodity_description"  href="">卓页美业长直假发片</a>
                                    </p>
                                </td>
                                <td class="col-price">
                                    <p class="p-price">
                                        <em>¥</em>
                                        <span>50.00</span>
                                    </p>
                                </td>
                                <td class="col-quty">
                                	<p>1</p>
                                </td>
                                <td rowspan="3" class="col-pay">
                                    <p>
                                        <em>¥</em>
                                        <span>120.00</span>
                                    </p>
                                </td>
                                <td rowspan="3" class="col-status">
                                    <p>待付款</p>
                                </td>
	                		</tr>
	                		<tr>
	                			<td class="col-pro-img">
	                				<a href="">
                                        <img src="{{ asset('img/order-pro.png') }}">
                                    </a>
	                			</td>
                                <td class="col-pro-info">
                                    <p class="p-info">
                                        <a class="commodity_description"  href="">卓页美业长直假发片</a>
                                    </p>
                                </td>
                                <td class="col-price">
                                    <p class="p-price">
                                        <em>¥</em>
                                        <span>50.00</span>
                                    </p>
                                </td>
                                <td class="col-quty">
                                	<p>1</p>
                                </td>
	                		</tr>
	                		<tr>
	                			<td class="col-pro-img">
	                				<a href="">
                                        <img src="{{ asset('img/order-pro.png') }}">
                                    </a>
	                			</td>
                                <td class="col-pro-info">
                                    <p class="p-info">
                                        <a class="commodity_description" href="">卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片卓页美业长直假发片</a>
                                    </p>
                                </td>
                                <td class="col-price">
                                    <p class="p-price">
                                        <em>¥</em>
                                        <span>50.00</span>
                                    </p>
                                </td>
                                <td class="col-quty">
                                	<p>1</p>
                                </td>
	                		</tr>
	                	</tbody>
	                </table>
	                <div class="order_settlement">
	                	<p class="commodity_cost">
	                		<span>商品合计：</span>
	                		<span>￥120.00</span>
	                	</p>
	                	<p class="freight">
	                		<span>运  费：</span>
	                		<span>￥10.00</span>
	                	</p>
	                	<p class="total_cost">
	                		<span>应付总额：</span>
	                		<span class="cost_of_total">￥460.00</span>
	                	</p>
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
            $(".my_order").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete").show();
            });
        });
    </script>
@endsection
