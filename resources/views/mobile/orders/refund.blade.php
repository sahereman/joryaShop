@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Request a refund' : '申请退款')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
             onclick="javascript:history.back(-1);"/>
        <span>申请退款</span>
    </div>
    <div class="refund">
        <div class="refund_con">
        	<div class="refund_content">
        		<!--售后状态-->
	        	<div class="after_sales_status">
	        		<!--第一步-->
	        		@if(true) 
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_1.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Seller applies for refunds only')</span>
	        			</p>
	        		</div>
	        		<!--第2步-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_2.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Seller handles refund Request')</span>
	        			</p>
	        		</div>
	        		<!--第3步-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_3.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Refunds are complete')</span>
	        			    <span>@lang('order.Refunds were successful')
                                        , 7200.00@lang('order.It has been returned according to the original hit path')</span>
	        			</p>
	        		</div>
	        		<!--第4步-->
	        		@elseif(false)
	        		<div class="aftersales_status_item">
	        			<img src="{{ asset('static_m/img/refund_4.png') }}">
	        			<p>
	        				<span class="status_title">@lang('order.Refund failed')</span>
	        			    <span>@lang('order.You can contact online customer service')</span>
	        			</p>
	        		</div>
	        		@endif
	        	</div>
	        	<!--申请内容-->
	        	<div class="refund_info">
	        		<!--第一步-->
	        		@if(false)
	        		<form method="POST" action="{{ route('orders.store_refund', ['order' => $order->id]) }}"
                                  enctype="multipart/form-data" id="step-1-form">
                                {{ csrf_field() }}
	                    <input type="hidden" name="order_id" value="id值">
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
	                                               value="&#165;7200.00"
	                                               readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
	        		</form>
	        		<!--第二步-->
	        		@elseif(false)
	        		<form method="POST" action="{{ route('orders.update_refund', ['order' => $order->id]) }}"
                                  enctype="multipart/form-data" id="step-2-form">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="id值">
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
	                                               value="&#165;7200.00"
	                                               readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" class="step2_textarea" readonly placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
	        		</form>
	        		<!--第三步第四步都是这个-->
	        		@else
		        		<p>
		        			<span>@lang('order.Refund amount')</span>
		        			<input name="amount" type="text" class="refund_price"
	                                               value="&#165;7200.00"
	                                               readonly>
		        		</p>
		        		<div class="refund_info_item">
		        			<span>@lang('order.Application description')</span>
		        			<textarea name="remark_from_user" readonly placeholder="@lang('order.Please fill in the reason for the refund')" maxlength="200"></textarea>
		        		</div>
	        		@endif
	        	</div>
	        	<!--订单内容-->
	        	<div class="order_products">
	        		@for ($i = 0; $i < 2; $i++)
	        			<div class="ordDetail_item">
		                    <img src="{{ asset('static_m/img/blockImg.png') }}"/>
		                    <div>
		                        <div class="ordDetailName">
		                            <a href="">卓页美业长直假发片可拆卸水洗亚麻</a>
		                        </div>
		                        <div>
		                            <span>
		                                @lang('basic.users.quantity')：2
		                                &nbsp;&nbsp;
		                            </span>
		                            <span>
		                                <a href="">颜色：{{ App::isLocale('en') ? 'yellow' : '黄' }}</a>
		                            </span>
		                        </div>
		                        <div class="ordDetailPri">
		                            <span>&#165;</span>
		                            <span>500.00</span>
		                        </div>
		                    </div>
		                </div>
	        		@endfor
	        		<div class="order_info">
	        			<p>
		        			<span>@lang('order.Order time')：</span>
		        			<span>2010-2-05 10:22:50</span>
		        		</p>
		        		<p>
		        			<span>@lang('order.Order number')：</span>
		        			<span>45748574867585023448</span>
		        		</p>
	        		</div>
	        	</div>
        	</div>
        	<div class="refund_btns">
        		<!--第一步显示-->
        		@if(false)
	        	<a href="" class="doneBtn">@lang('app.submit')</a>
	        	<!--第二步显示-->
	        	@else
	        	<div>
	        		<a class="ordDetailBtnC change_btn" href="javascript:void(0);" data-url="">
	                    @lang('order.Modify')
	                </a>
	                <a class="ordDetailBtnC save_btn dis_ni" href="javascript:void(0);" data-url="">
	                    @lang('order.Save changes')
	                </a>
	                <a class="ordDetailBtnS Revocation_btn" href="javascript:void(0);" code="{{ route('orders.revoke_refund', ['order' => 1]) }}">
	                    @lang('order.Revocation of application')
	                </a>
	        	</div>
	        	@endif
	        </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var set_finish = false;
        $(function () {
        	$(".refund_con").css("min-height",$(window).height()-$(".headerBar ").height());
        	//第一步表单提交
            $(".step-1-submit").on("click", function () {
                $("#step-1-form").submit();
            });
            //点击修改申请
            $(".change_btn").on("click",function(){
            	$(this).addClass("dis_ni");
            	$(".save_btn").removeClass("dis_ni");
            	$(".step2_textarea").prop("readonly",false);
            })
            //保存修改
            $(".save_btn").on("click",function(){
            	$("#step-2-form").submit();
            })
            //撤销退款申请
            $(".Revocation_btn").on("click", function () {
                var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(this).attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.href = "{{ route('mobile.orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 403) {
                            layer.open({
	                        content: "@lang('app.Unable to complete operation')"
	                        , skin: 'msg'
	                        , time: 2 //2秒后自动关闭
	                    });
                        }
                    }
                });
            });
        })
    </script>
@endsection
