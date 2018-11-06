@extends('layouts.app')
@section('title', '选择支付方式')
@section('content')
    @include('common.error')
    <div class="payment_method">
        <div class="m-wrapper">
            <div class="methods">
            	<p class="trade_number">交易号：<span>20181012112935YXMPD</span></p>
            	<div class="methods_choose">
            		<p>支付方式</p>
            		<ul>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="1" id="alipay" checked>
            					<img src="{{ asset('img/alipay.png') }}">
            				</label>
            			</li>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="2" id="wxpay">
            					<img src="{{ asset('img/wxpay.png') }}">
            				</label>
            			</li>
            			<li>
            				<label class="cur_p clear">
            				    <input type="radio" name="payMethod" value="3" id="paypal">
            					<img src="{{ asset('img/paypal.png') }}">
            				</label>
            			</li>
            		</ul>
            	</div>
            	<div class="methods_footer clear">
            		<div class="left">
            			<p>实付：<span id="needToPay">&yen;888.00</span></p>
            		</div>
            		<div class="right">
            			<button class="pay_btn">付款</button>
            			<p class="cunt_down paying_time">剩余付款时间：<span>59分58秒</span></p>
            		</div>
            	</div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
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
        });
    </script>
@endsection
