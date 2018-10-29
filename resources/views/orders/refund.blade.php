@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="User_center my_orders">
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
                    <a href="{{ route('orders.index') }}">申请售后</a>
                </p>
            </div>
            <!--申请内容-->
            <div class="refund_content">
            	<p>这是退款申请页面</p>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {

            $(".tobe_received_count").each(function (index, element) {
                var val = $(this).attr("mark");
                var start_time = $(this).attr("shipped_at") * 1000;
                var ending_time = $(this).attr('time_to_complete_order');
                timeCount(val, start_time, ending_time, "2");
            });


            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".myorder_classification li").on('click', function () {
                $(".myorder_classification li").removeClass('active');
                $(this).addClass("active");
            });
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete .textarea_content").find("span").attr("code", $(this).attr("code"));
                $(".order_delete").show();
            });
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 4,
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
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
                        console.log(data);
                        window.location.reload();
                    },
                    error: function (err) {
                        console.log(err.status);
                        if (err.status == 403) {

                        }
                    }
                });
            });
            var action = "";
            var data = new Date();
            window.onload = function () {
                if (getUrlVars() != undefined) {
                    action = getUrlVars();
                }
                switch (action) {
                    case "paying":   //待付款
                        $(".myorder_classification li").removeClass('active');
                        $(".order_paying").addClass("active");
                        //倒计时开始
                        //显示时间，待支付订单
                        $(".paying_time").each(function (index, element) {
                            var val = $(this).attr("mark");
                            var start_time = $(this).attr("created_at") * 1000;
                            var ending_time = $(this).attr('time_to_close_order');
                            timeCount(val, start_time, ending_time, '1');
                        });
                        break;
                    case "receiving":   //待收货
                        $(".myorder_classification li").removeClass('active');
                        $(".order_receiving").addClass("active");
                        $(".tobe_received_count").each(function (index, element) {
                            var val = $(this).attr("mark");
                            var start_time = $(this).attr("shipped_at") * 1000;
                            var ending_time = $(this).attr('time_to_complete_order');
                            timeCount(val, start_time, ending_time, "2");
                        });
                        break;
                    case "uncommented":   //待评价
                        $(".myorder_classification li").removeClass('active');
                        $(".order_uncommented").addClass("active");
                        break;
                    case "refunding":   //售后订单
                        $(".myorder_classification li").removeClass('active');
                        $(".order_refunding").addClass("active");
                        break;
                    default :   //所有订单
                        $(".myorder_classification li").removeClass('active');
                        $(".all_orders").addClass("active");
                        break;
                }
            };
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
                        $('#' + remain_id).html('剩余' + _hour + '时' + _minute + '分' + _second + '秒');
                    } else {
                        $('#' + remain_id).html('剩余' + _day + '天' + _hour + '时' + _minute + '分');
                    }
                }
            }

            _fresh();
            var sh = setInterval(_fresh, 1000);
        }
    </script>
@endsection
