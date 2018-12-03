@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Choosing a Payment method' : '选择支付方式')
@section('content')
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>确认订单</span>
	</div>
	<div class="pre_payment">
        <div class="pre_paymentCon">
        	 <div class="pre_address edit_address" data-url="{{ route('user_addresses.list_all') }}">
        		<div>
        			<p class="address_title">
        				<span class="address_name">{{ $order->user_info['name'] }}</span>
        				<span class="address_phone">{{ $order->user_info['phone'] }}</span>
        			</p>
        			<p class="address_info">
        				<span class="address_info_all">{{ $order->user_info['address'] }}</span>
        			</p>
        		</div>
    	    </div>	
        	<div class="pre_products">
        		<ul>
        			@foreach($order->snapshot as $key => $order_item)
        				@if($key > 2)
                    		@break
                    	@endif
                    	<li>
	    	   	        	<img src="{{ $order_item['sku']['product']['thumb_url'] }}">
	    	   	            <span>&#215; {{ $order_item['number'] }}</span>
	    	   	        </li>
        			@endforeach
        		</ul>
        		<!--显示商品总数量-->
        		<span class="pre_products_num">共{{ count($order->snapshot) }}件</span>
        	</div>
        	<div class="pre_amount">
        		<p>
        			<span>@lang('order.A total of')</span>
        			<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_amount }}</span>
        		</p>
        		<p>
        			<span>@lang('order.freight')</span>
        			<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_shipping_fee }}</span>
        		</p>
        	</div>
        	<div class="pre_currency">
        		<p class="main_title">@lang('order.currency option')</p>
                <p class="currency_selection">
                	@if($order->currency == 'CNY')
                    <a href="javascript:void(0)" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>
                    @else
                    <a href="javascript:void(0)" code="dollar" country="USD">@lang('order.Dollars')</a>
                    @endif
                </p>
        	</div>
        	<div class="pre_note">
        		<p>@lang('order.order note')</p>
        		<textarea placeholder="@lang('order.Optional message')" maxlength="50" readonly value="{{ $order->remark }}"></textarea>
        	</div>
        </div>
        <div class="pre_paymentTotal">
            <span class="amount_of_money cost_of_total">{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
        	<a href="javascript:void(0)" class="Topayment_btn" data-url="{{ route('orders.store') }}">去支付</a>
        </div>
    </div>
    <!--选择支付方式弹窗-->
    <div class="payment_method_choose animated dis_n">
    	<div class="mask"></div>
    	<div class="pay_choose">
			<p class="pay_choose_title">
				<span class="close_btn_payChoose"></span>
				<span>付款</span>
				<span class="count_down">29.26</span>
			</p>
    		<p class="pay_choose_order">
    			<span>订单编号：</span>
    			<span>{{ $order->order_sn }}</span>
    		</p>
    		<ul>
	            @if($order->currency == 'CNY')
	                @if(true)
		                <li>
	                        <input type="radio" name="payMethod" value="1" id="alipay"
	                               data-href="{{ route('payments.alipay', ['order' => $order->id]) }}" checked>
	                        <span class="bagLbl"></span>
	                        <label class="cur_p clear" for="alipay">
		                        <img src="{{ asset('static_m/img/icon_alipay_small.png') }}">
		                    </label>
		                </li>
	                @endif
	                <li>
	                    <input type="radio" name="payMethod" value="2" id="wxpay"
	                           data-href="{{ route('payments.wechat', ['order' => $order->id]) }}">
	                    <span class="bagLbl"></span>
	                    <label class="cur_p clear" for="wxpay">
	                        <img src="{{ asset('static_m/img/icon_wechat_small.png') }}">
	                    </label>
	                </li>
	            @else
	                <li>
	                    <input type="radio" name="payMethod" value="3" id="paypal"
	                    	   data-href="{{ route('payments.paypal.create', ['order' => $order->id]) }}">
	                    <label class="cur_p clear" for="paypal">
	                        <img src="{{ asset('static_m/img/icon_paypal_small.png') }}">
	                    </label>
	                </li>
	            @endif
	        </ul>
        	<p class="need_to_pay">
        		<span>支付</span>
        		<span class="total_num_toPay">{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
            </p>
            <p class="rel_topayment">
            	<a href="javascript:void(0)">支付</a>
            </p>
    	</div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            //付款倒计时
            var start_time = $("#time_to_pay").attr("created_at") * 1000;
            var ending_time = $("#time_to_pay").attr('time_to_close_order');
            var seconds_to_close_order = $("#time_to_pay").attr('seconds_to_close_order');
            timeCount("time_to_pay", seconds_to_close_order, 1);
            //点击付款
            $(".Topayment_btn").on("click", function () {
            	$(".payment_method_choose").removeClass('dis_n');
            });
            $(".close_btn_payChoose").on("click",function(){
            	$(".payment_method_choose").addClass('dis_n');
            })
            //点击支付
            $(".rel_topayment").on("click",function(){
            	var is_choosed = $(".payment_method_choose").find("input[name='payMethod']:checked");
            	console.log(is_choosed.length)
            	if(is_choosed.length == 1){
  		          	  var way_choosed = $(".payment_method_choose").find("input[name='payMethod']:checked").val();
		              var location_href = $(".payment_method_choose").find("input[name='payMethod']:checked").attr("data-href");
		              switch (way_choosed) {
		                  case "1":          //支付宝
		                      window.location.href = location_href;
		                      break;
		                  case "2":          //微信
		                      window.location.href = location_href;
		                      break;
		                  case "3":          //paypal
		                      var url = location_href;
		                      $.ajax({
		                          type: "get",
		                          url: url,
		                          success: function (json) {
		                              if (json.code == 200) {
		                                  window.location.href = json.data.redirect_url;
		                              } else {
		                                  layer.open({
						                        content: json.message
						                        , skin: 'msg'
						                        , time: 2 //2秒后自动关闭
						                    });
		                              }
		                          }
		                      });
		                      break;
		                  default :
		                      layer.alert("lang('order.Please select the payment method')");
		                      break;
		              }	
            	}else {
            		layer.open({
                        content: "请选择支付方式"
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
            	}
            })
            //倒计时方法封装
            function timeCount(remain_id, totalS, type) {
                function _fresh() {
                    totalS--;
                    if (totalS > 0) {
                        var _day = parseInt((totalS / 3600) % 24 / 24);
                        var _hour = parseInt((totalS / 3600) % 24);
                        var _minute = parseInt((totalS / 60) % 60);
                        var _second = parseInt(totalS % 60);
                        if (_day < 10) {
                            _day = "0" + _day;
                        }
                        if (_hour < 10) {
                            _hour = "0" + _hour;
                        }
                        if (_minute < 10) {
                            _minute = "0" + _minute;
                        }
                        if (_second < 10) {
                            _second = "0" + _second;
                        }
                        if (type == '1') {
                            $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _hour + ':' + _minute + ':' + _second + "@lang('order.payment')(@lang('order.If the order is not paid out, the system will automatically cancel the order'))");
                        } else {
                            $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _day + ':' + _hour + ':' + _minute + ':' + _second + "@lang('order.for confirmation')(@lang('order.not confirmed after the timeout'))");
                        }
                    }
                }

                _fresh();
                var sh = setInterval(_fresh, 1000);
            }
        });
    </script>
@endsection
