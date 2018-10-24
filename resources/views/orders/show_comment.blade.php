@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="evaluate_commont">
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
                    <span>></span>
                    <a href="{{ route('orders.index') }}">评价</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
            <!--右侧内容-->
            <div class="comment_content">
            	@for ($i = 0; $i <3; $i++)
            		<div class="evaluation_order">
            			<table>
            				<thead>
            					<th></th>
            					<th>商品</th>
            					<th>规格</th>
            					<th>单价</th> 
            					<th>数量</th>
            					<th>小计</th>
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
	                                <td class="col-pro-speci">
	                                    <p class="p-info">
	                                        <a class="specifications"  href="">蓝色</a>
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
	                                <td class="col-pay">
	                                    <p>
	                                        <em>¥</em>
	                                        <span>120.00</span>
	                                    </p>
	                                </td>
		                		</tr>
	            			</tbody>
            			</table>
            			<div class="evaluation_results">
            				<div class="five_star_evaluation">
            					<div class="five_star_one star_area">
            						<div class="starability-basic">
            							
            							<input type="radio" id="rate5-1_{{ $i }}" name="rating" value="5" />
										<label for="rate5-1_{{ $i }}" title="Amazing"></label>
								
										<input type="radio" id="rate4-1_{{ $i }}" name="rating" value="4" />
										<label for="rate4-1_{{ $i }}" title="Very good"></label>
								
										<input type="radio" id="rate3-1_{{ $i }}" name="rating" value="3" />
										<label for="rate3-1_{{ $i }}" title="Average"></label>
								
										<input type="radio" id="rate2-1_{{ $i }}" name="rating" value="2" />
										<label for="rate2-1_{{ $i }}" title="Not good"></label>
								
										<input type="radio" id="rate1-1_{{ $i }}" name="rating" value="1" />
										<label for="rate1-1_{{ $i }}" title="Terrible"></label>
            						</div>
            					</div>
							</div>
            			</div>
            	    </div>
				@endfor
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