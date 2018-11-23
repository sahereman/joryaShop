@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Personal Center-my order' : '个人中心-我的订单')
@section('content')
    <div class="User_center my_orders">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <ul class="myorder_classification">
                    <li class="all_orders">
                        <a href="{{ route('orders.index') }}">
                            <span>@lang('basic.orders.All orders')</span>
                        </a>
                    </li>
                    <li class="order_paying">
                        <a href="{{ route('orders.index') . '?status=paying' }}">
                            <span>@lang('basic.orders.Pending payment')</span>
                        </a>
                    </li>
                    <li class="order_receiving">
                        <a href="{{ route('orders.index') . '?status=receiving' }}">
                            <span>@lang('basic.orders.Pending receipt')</span>
                        </a>
                    </li>
                    <li class="order_uncommented">
                        <a href="{{ route('orders.index') . '?status=uncommented' }}">
                            <span>@lang('basic.orders.comment')</span>
                        </a>
                    </li>
                    <li class="order_refunding">
                        <a href="{{ route('orders.index') . '?status=refunding' }}">
                            <span>@lang('basic.orders.After-sale order')</span>
                        </a>
                    </li>
                </ul>
                <ul class="ordertable_title">
                    <li class="order_details">
                        <span>@lang('basic.users.The_order_details')</span>
                    </li>
                    <li class="order_price">
                        <span>@lang('basic.users.The_unit_price')</span>
                    </li>
                    <li class="order_num">
                        <span>@lang('basic.users.quantity')</span>
                    </li>
                    <li class="order_pay">
                        <span>@lang('basic.users.The_final_payment')</span>
                    </li>
                    <li class="order_status">
                        <span>@lang('basic.users.Order_Status')</span>
                    </li>
                    <li class="order_operation">
                        <span>@lang('basic.users.operating')</span>
                    </li>
                </ul>
                <!--订单列表分为两部分，1、暂无订单时展现其他时候隐藏。2、存在订单时显示.需进行判断-->
                <div class="order_list">
                    @if($orders->isEmpty())
                            <!--暂无订单部分-->
                    <div class="no_order">
                        <img src="{{ asset('img/no_order.png') }}">
                        <p>@lang('basic.users.No_orders_yet')</p>
                        <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
                    </div>
                    @else
                            <!--订单部分-->
                    <div class="order-group">
                        @foreach($orders as $order)
                            <div class="order-group-item">
                                <div class="o-info">
                                    <div class="col-info pull-left">
                                     <span class="o-no">
                                         @lang('basic.users.Order_number')：
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
                                        @foreach($order->snapshot as $key => $order_item)
                                        @if($key == 0)
                                                <!--当循环的子订单数量为1时第一个tr整体作为一个单独的模板进行渲染，超过两个时请看第二个tr前的注释-->
                                        <tr>
                                            <td class="col-pro-img">
                                                <p class="p-img">
                                                    <a href="{{ route('products.show', $order_item['sku']['product']['id']) }}">
                                                        <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a code="{{ $order_item['sku']['id'] }}"
                                                       href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    @if($order->currency === 'CNY')
                                                        <em>&#165; </em>
                                                    @else
                                                        <em>&#36; </em>
                                                    @endif
                                                    <span>{{ $order_item['price'] }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">{{ $order_item['number'] }}</td>
                                            <td rowspan="{{ count($order->snapshot) }}" class="col-pay">
                                                <p>
                                                    @if($order->currency === 'CNY')
                                                        <em>&#165; </em>
                                                    @else
                                                        <em>&#36; </em>
                                                    @endif
                                                    <span>{{ $order->total_amount }}</span>
                                                </p>
                                            </td>
                                            <td rowspan="{{ count($order->snapshot) }}" class="col-status">
                                                <p>{{ \App\Models\Order::$orderStatusMap[$order->status] }}</p>
                                            </td>
                                            <td rowspan="{{ count($order->snapshot) }}" class="col-operate">
                                                <p class="p-button">
                                                    <!--以下按钮除再次购买并不同时展示根据订单状态进行调整-->
                                                    @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                                            <!--订单待支付-->
                                                    <!--付款或再次购买隐藏显示取消订单-->
                                                    <!--系统自动关闭订单倒计时-->
                                                    <span id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}"
                                                          class="paying_time count_down"
                                                          created_at="{{ strtotime($order->created_at) }}"
                                                          time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 60 }}"
                                                          seconds_to_close_order="{{ (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) > 0 ? (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) : 0 }}">
                                                        {{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
                                                    </span>
                                                    <a class="payment"
                                                       href="{{ route('orders.payment_method', $order->id) }}">@lang('basic.orders.payment')</a>
                                                    <a class="cancellation"
                                                       code="{{ route('orders.close', $order->id) }}">@lang('basic.orders.cancel order')</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                                            <!--再次购买-->
                                                    <a class="Buy_again"
                                                       data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                                            <!--订单待发货-->
                                                    <a class="reminding_shipments">@lang('basic.orders.Remind shipments')</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                                            <!--订单待收货-->
                                                    <!--确认收货-->
                                                    <!--系统自动确认订单倒计时-->
                                                    <span id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}"
                                                          class="tobe_received_count count_down"
                                                          shipped_at="{{ strtotime($order->shipped_at) }}"
                                                          time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}"
                                                          seconds_to_complete_order="{{ (strtotime($order->shipped_at) + \App\Models\Order::getSecondsToCompleteOrder() - time()) > 0 ? (strtotime($order->shipped_at) + \App\Models\Order::getSecondsToCompleteOrder() - time()) : 0 }}">
                                                        {{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}
                                                    </span>
                                                    <a class="confirmation_receipt"
                                                       code="{{ route('orders.complete', $order->id) }}">@lang('basic.orders.Confirm receipt')</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                                            <!--订单待评价-->
                                                    <a class="evaluate"
                                                       href="{{ route('orders.create_comment', $order->id) }}">@lang('basic.orders.To evaluate')</a>
                                                    <!--再次购买-->
                                                    <a class="buy_more"
                                                       data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                                            <!--订单已评价-->
                                                    <!--查看评价-->
                                                    <a class="View_evaluation"
                                                       href="{{  route('orders.show_comment', $order->id) }}">@lang('basic.orders.View reviews')</a>
                                                    <!--再次购买-->
                                                    <a class="buy_more"
                                                       data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                    @endif
                                                </p>
                                            </td>
                                        </tr>
                                        @else
                                                <!--当循环的数据中超过两个子订单时从第二个子订单开始采用这种布局-->
                                        <tr class="order_top">
                                            <td class="col-pro-img">
                                                <p class="p-img">
                                                    <a href="{{ route('products.show', $order_item['sku']['product']['id']) }}">
                                                        <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a code="{{ $order_item['sku']['id'] }}"
                                                       href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    @if($order->currency === 'CNY')
                                                        <em>&#165; </em>
                                                    @else
                                                        <em>&#36; </em>
                                                    @endif
                                                    <span>{{ $order_item['price'] }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">{{ $order_item['number'] }}</td>
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
                    {{ $orders->appends(['status' => $status])->links() }}
                    {{--<div class="paging_box">
                        <a class="pre_page" href="{{ route('orders.index') }}">@lang('app.Previous page')</a>
                        <a class="next_page" href="{{ route('orders.index') }}">@lang('app.Next page')</a>
                    </div>--}}
                    @endif
                </div>
                <!--猜你喜欢-->
                <div class="guess_like">
                    <div class="ordertable_title">
                        <p>@lang('app.you may also like')</p>
                    </div>
                    <div class="guess_like_content swiper-container">
                        <ul class="swiper-wrapper">
                            @foreach($guesses as $guess)
                                <li class="swiper-slide">
                                    <div class="collection_shop_img">
                                        <img class="lazy" data-src="{{ $guess->thumb_url }}">
                                    </div>
                                    <p class="commodity_title" title="{{ App::isLocale('en') ? $guess->name_en : $guess->name_zh }}">{{ App::isLocale('en') ? $guess->name_en : $guess->name_zh }}</p>
                                    <p class="collection_price">
                                        <span class="new_price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }} {{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>
                                        <span class="old_price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }} {{ App::isLocale('en') ? bcmul($guess->price_in_usd, 1.2, 2) : bcmul($guess->price, 1.2, 2) }}</span>
                                    </p>
                                    <a class="add_to_cart"
                                       href="{{ route('products.show', $guess->id) }}">@lang('app.see details')</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="swiper-pagination"></div>
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
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('basic.users.Make_sure_to_delete')</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
    <!--是否确认取消订单弹出层-->
    <div class="dialog_popup order_cancel">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('basic.users.Make sure to cancel the order')</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
        $(function () {
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
                            layer.open({
                                title: "@lang('app.Prompt')",
                                content: "@lang('app.Unable to complete operation')",
                                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                            });
                        }
                    }
                });
            });
            var action = "";
            var data = new Date();
            //获取url参数
            function getUrlVars() {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars["status"];
            }

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
                            var seconds_to_close_order = $(this).attr("seconds_to_close_order");
                            timeCount(val, seconds_to_close_order, '1');
                        });
                        break;
                    case "receiving":   //待收货
                        $(".myorder_classification li").removeClass('active');
                        $(".order_receiving").addClass("active");
                        $(".tobe_received_count").each(function (index, element) {
                            var val = $(this).attr("mark");
                            var start_time = $(this).attr("shipped_at") * 1000;
                            var ending_time = $(this).attr('time_to_complete_order');
                            var seconds_to_complete_order = $(this).attr("seconds_to_complete_order");
                            timeCount(val, seconds_to_complete_order, "2");
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
        function timeCount(remain_id, totalS, type) {
            function _fresh() {
//              var nowDate = new Date(); //当前时间
                var id = $('#' + remain_id).attr("order_id"); //当前订单的id
//              var addTime = new Date(parseInt(start_time));               //返回的时间戳转换成时间格式
//              var auto_totalS = ending_time; //订单支付有效时长
//              var ad_totalS = parseInt((addTime.getTime() / 1000) + auto_totalS); ///下单总秒数
//              var totalS = parseInt(ad_totalS - (nowDate.getTime() / 1000)); ///支付时长
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
                        $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _hour + ':' + _minute + ':' + _second);
                    } else {
                        $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _day + ':' + _hour + ':' + _minute + ':' + _second);
                    }
                }else {
                    if (type == '1') {
                        $('#' + remain_id).html("@lang('order.Order has timed out')");
                        $("")
                    } else {
                        $('#' + remain_id).html("@lang('order.Order has timed out')");
                    }
                }
            }

            _fresh();
            var sh = setInterval(_fresh, 1000);
        }

        //取消订单
        $(".cancellation").on('click', function () {
            $(".order_cancel .textarea_content").find("span").attr("code", $(this).attr("code"));
            $(".order_cancel").show();
        });
        $(".order_cancel").on('click', '.success', function () {
            var data = {
                _method: "PATCH",
                _token: "{{ csrf_token() }}",
            };
            var url = $(".order_cancel .textarea_content").find("span").attr('code');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    window.location.reload();
                },
                error: function (err) {
                    if (err.status == 403) {
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "@lang('app.Unable to complete operation')",
                            btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                        });
                    }
                }
            });
        });

        //确认收货
        $(".confirmation_receipt").on('click', function () {
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
                    window.location.reload();
                },
                error: function (err) {
                    if (err.status == 403) {
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "@lang('app.Unable to complete operation')",
                            btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                        });
                    }
                }
            });
        });
        //提醒发货
        $(".reminding_shipments").on('click', function () {
            layer.msg("@lang('basic.orders.The seller has been reminded to ship the goods, please wait for good news')");
        });
        var allHadAdd = 0;  //用来判断是否已经订单找那个全部的商品添加至购物车中
        var shops_list;  //单个订单中包含的商品的数量,用于再次购买时判断时候可以进行跳页
        var loading_animation;  //loading动画的全局name
        //再次购买
        $(".buy_more").on("click", function () {
            shops_list = $(this).parents("table").find("tr");
            var sku_id_lists = "";  //用于页面跳转在购物车页面通过判断这个参数的值选中商品
            var sku_id;
            var number;
            var url = $(this).attr("data-url");
            $.each(shops_list, function (i, n) {
                sku_id = $(n).find(".p-info").find("a").attr("code");
                number = $(n).find(".col-quty").html();
                sku_id_lists += $(n).find(".p-info").find("a").attr("code") + ",";
                allHadAdd++;
                add_to_carts(sku_id, number, url, sku_id_lists, allHadAdd);
            });
        });
        $(".Buy_again").on("click", function () {
            shops_list = $(this).parents("table").find("tr");
            var sku_id_lists = "";  //用于页面跳转在购物车页面通过判断这个参数的值选中商品
            var sku_id;
            var number;
            var url = $(this).attr("data-url");
            $.each(shops_list, function (i, n) {
                sku_id = $(n).find(".p-info").find("a").attr("code");
                number = $(n).find(".col-quty").html();
                sku_id_lists += $(n).find(".p-info").find("a").attr("code") + ",";
                allHadAdd++;
                add_to_carts(sku_id, number, url, sku_id_lists, allHadAdd);
            });
        });
        //添加购物车
        function add_to_carts(sku_id, number, url, sku_id_lists, allHadAdd) {
            var data = {
                _token: "{{ csrf_token() }}",
                sku_id: sku_id,
                number: number
            };
            $.ajax({
                type: "post",
                url: url,
                data: data,
                beforeSend: function () {
                    loading_animation = layer.msg("@lang('app.Please wait')", {
                        icon: 16,
                        shade: 0.4,
                        time: false //取消自动关闭
                    });
                },
                success: function (data) {
                    if (allHadAdd == shops_list.length) {
                        window.location.href = url + "?sku_id_lists=" + sku_id_lists;
                    }
                },
                error: function (err) {
                    console.log(err);
                    if (allHadAdd == shops_list.length) {
                        layer.close(loading_animation);
                    }
                }
            });
        }
    </script>
@endsection
