@extends('layouts.app')
@section('title', '选择支付方式')
@section('content')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="methods">
                <p class="trade_number">@lang('order.Order_serial_number')：<span>{{ $order->order_sn }}</span></p>
                <div class="methods_choose">
                    <p>@lang('order.payment method')</p>
                    <ul>
                        @if($order->currency == 'CNY')
                            <li>
                                <label class="cur_p clear">
                                    <input type="radio" name="payMethod" value="1" id="alipay" data-href="{{ route('payments.alipay', ['order' => $order->id]) }}" checked>
                                    <img src="{{ asset('img/alipay.png') }}">
                                </label>
                            </li> 
                            <li>
                                <label class="cur_p clear">
                                    <input type="radio" name="payMethod" value="2" id="wxpay" data-href="{{ route('payments.wechat', ['order' => $order->id]) }}">
                                    <img src="{{ asset('img/wxpay.png') }}">
                                </label>
                            </li>
                        @else
                            <li>
                                <label class="cur_p clear">
                                    <input type="radio" name="payMethod" value="3" id="paypal">
                                    <img src="{{ asset('img/paypal.png') }}">
                                </label>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="methods_footer clear">
                    <div class="left">
                        <p>
                            @lang('order.Actually paid')：
                            <span id="needToPay">{{ ($order->currency == 'USD') ? '&#36;' : '&yen;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
                        </p>
                    </div>
                    <div class="right">
                            <button class="pay_btn">@lang('order.payment')</button>
                        <p class="cunt_down paying_time" id="time_to_pay"
                           created_at="{{ strtotime($order->created_at) }}"
                           time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 3600 }}"
                           seconds_to_close_order="{{ (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) > 0 ? (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) : 0 }}">
                           {{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
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
        	//付款倒计时
    		var start_time = $("#time_to_pay").attr("created_at") * 1000;
            var ending_time = $("#time_to_pay").attr('time_to_close_order');
            var seconds_to_close_order = $("#time_to_pay").attr('seconds_to_close_order');
    		timeCount("time_to_pay",seconds_to_close_order,1);
        	//点击付款
        	$(".pay_btn").on("click",function(){
        		var way_choosed = $(".methods_choose").find("input[name='payMethod']:checked").val();
        		var location_href = $(".methods_choose").find("input[name='payMethod']:checked").attr("data-href");
        		switch (way_choosed) {
		            case "1":          //支付宝
		                window.location.href = location_href;
		                break;
		            case "2":          //微信
		                window.location.href = location_href;
		                break;
		            case "3":          //paypal
		                break;
		            default :
		                layer.alert("lang('order.Please select the payment method')");
		                break;
		        }
        	})
            //倒计时方法封装
            function timeCount(remain_id, totalS, type) {
                function _fresh() {
//                  var nowDate = new Date(); //当前时间
                    var id = $('#' + remain_id).attr("order_id"); //当前订单的id
//                  var addTime = new Date(parseInt(start_time));               //返回的时间戳转换成时间格式
//                  var auto_totalS = ending_time; //订单支付有效时长
//                  var ad_totalS = parseInt((addTime.getTime() / 1000) + auto_totalS); ///下单总秒数
//                  var totalS = parseInt(ad_totalS - (nowDate.getTime() / 1000)); ///支付时长
                    totalS--;
                    if (totalS > 0) {
                        var _day = parseInt((totalS / 3600) % 24 / 24);
                        var _hour = parseInt((totalS / 3600) % 24);
                        var _minute = parseInt((totalS / 60) % 60);
                        var _second = parseInt(totalS % 60);
                        if(_day<10){
                        	_day = "0"+_day;
                        }else {
                        	_day = _day;
                        }
                        if(_hour<10){
                        	_hour = "0"+_hour;
                        }else {
                        	_hour = _hour;
                        }
                        if(_minute<10){
                        	_minute = "0"+_minute;
                        }else {
                        	_minute = _minute;
                        }
                        if(_second<10){
                        	_second = "0"+_second;
                        }else {
                        	_second = _second;
                        }
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
        });
    </script>
@endsection
