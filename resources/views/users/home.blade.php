@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '个人中心' : 'Personal Center') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="User_center">
        <div class="main-content">
            <div class="Crumbs-box">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('basic.users.Personal_Center')</a>
                </p>
            </div>
            {{-- 内容区域 --}}
            <div class="home-content">
                <!--左侧导航栏-->
                @include('users._left_navigation')
                <!--右侧内容-->
                <div class="UserInfo_content">
                    {{-- 个人信息 --}}
                    <div class="UserInfo-box">
                        @auth
                            <div class="userInfo-info">
                                <div class="user_img">
                                    <img src="{{ $user->avatar_url }}">
                                </div>
                                <div class="user_name">
                                    <span>@lang('basic.users.nickname')：{{ $user->name }}</span>
                                    <a href="{{ route('users.edit', ['user' => $user->id]) }}">
                                        @lang('basic.users.Modify_Personal_Information') >
                                    </a>
                                </div>
                            </div>
                        @endauth
                        <ul class="userInfo_list">
                            <li>
                                <a href="{{ route('user_favourites.index') }}">
                                    <span>@lang('basic.users.My_collection')</span>
                                    <img src="{{ asset('img/collection.png') }}">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user_addresses.index') }}">
                                    <span>@lang('basic.users.Receiving_address')</span>
                                    <img src="{{ asset('img/receive_address.png') }}">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders.index') }}">
                                    <span>@lang('basic.users.My_order')</span>
                                    <img src="{{ asset('img/record.png') }}">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user_histories.index') }}">
                                    <span>@lang('basic.users.Browse_history')</span>
                                    <img src="{{ asset('img/history_record.png') }}">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <ul class="order_classification">
                        <li>
                            <a href="{{ route('orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_PAYING }}">
                                <img src="{{ asset('img/tobe_paid.png') }}">
                                <span>@lang('basic.users.Pending_payment')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_UNCOMMENTED }}">
                                <img src="{{ asset('img/tobe_evaluated.png') }}">
                                <span>@lang('basic.users.Pending_feedback')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_RECEIVING }}">
                                <img src="{{ asset('img/tobe_received.png') }}">
                                <span>@lang('basic.users.On_the_receiving_line')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_REFUNDING }}">
                                <img src="{{ asset('img/after-sale.png') }}">
                                <span>@lang('basic.users.After_sales_order')</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="ordertable_title" style="height: 0;">
                        {{--<li class="order_details">
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
                        </li>--}}
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
                                            <a href="{{ route('orders.show', ['order' => $order->id]) }}">{{ $order->order_sn }}</a>
                                        </span>
                                        </div>
                                        @if(in_array($order->status, [\App\Models\Order::ORDER_STATUS_CLOSED, \App\Models\Order::ORDER_STATUS_COMPLETED]))
                                            <div class="col-delete pull-right"
                                                code="{{ route('orders.destroy', ['order' => $order->id]) }}">
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
                                                        <a href="{{ route('seo_url', $item->sku->product->slug) }}">
                                                            <img src="{{ $item->sku->product->thumb_url }}">
                                                        </a>
                                                    </p>
                                                </td>
                                                <td class="col-pro-info">
                                                    <p class="p-info">
                                                        <a code="{{ $item->sku->id }}"
                                                        href="{{ route('seo_url', $item->sku->product->slug) }}">{{ App::isLocale('zh-CN') ? $item->sku->product->name_zh : $item->sku->product->name_en }}</a>
                                                    </p>
                                                </td>
                                                <td class="col-price">
                                                    <p class="p-price">
                                                        {{--<em>{{ $order->currency == 'USD' ? '&#36;' : '&#165;' }}</em>--}}
                                                        <em>{{ get_symbol_by_currency($order->currency) }} </em>
                                                        <span>{{ $item->price }}</span>
                                                    </p>
                                                </td>
                                                <td class="col-quty">{{ $item->number }}</td>
                                                <td rowspan="{{ $order->items->count() }}" class="col-pay">
                                                    <p>
                                                        {{--<em>{{ $order->currency == 'USD' ? '&#36;' : '&#165;' }}</em>--}}
                                                        <em>{{ get_symbol_by_currency($order->currency) }} </em>
                                                        <span>{{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
                                                        <br>
                                                        <span>(@lang('order.Postage included'))</span>
                                                    </p>
                                                </td>
                                                <td rowspan="{{ $order->items->count() }}" class="col-status">
                                                    @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                                        <p>@lang('basic.orders.Pending payment')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                                        <p>@lang('basic.orders.Closed')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                                        <p>@lang('basic.orders.Pending shipment')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                                        <p>@lang('basic.orders.Pending reception')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                                        <p>@lang('basic.orders.Pending comment')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                                        <p>@lang('basic.orders.Completed')</p>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                                                        <p>@lang('basic.orders.After-sale order')</p>
                                                    @endif
                                                    <p>
                                                        <a href="{{ route('orders.show', ['order' => $order->id]) }}">@lang('app.see details')</a>
                                                    </p>
                                                </td>
                                                <td rowspan="{{ $order->items->count() }}" class="col-operate">
                                                    <p class="p-button">
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
                                                        href="{{ route('payments.method', ['payment' => $order->payment_id]) }}">@lang('basic.orders.payment')</a>
                                                        <a class="cancellation"
                                                        code="{{ route('orders.close', ['order' => $order->id]) }}">@lang('basic.orders.cancel order')</a>
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
                                                        code="{{ route('orders.complete', ['order' => $order->id]) }}">@lang('basic.orders.Confirm reception')</a>
                                                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                                                <!--订单待评价-->
                                                        <a class="evaluate"
                                                        href="{{ route('orders.create_comment', ['order' => $order->id]) }}">@lang('basic.orders.To comment')</a>
                                                        <!--再次购买-->
                                                        <a class="buy_more"
                                                        data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                                                <!--订单已评价-->
                                                        <!--查看评价-->
                                                        <a class="View_evaluation"
                                                        href="{{  route('orders.show_comment', ['order' => $order->id]) }}">@lang('basic.orders.View comments')</a>
                                                        <!--再次购买-->
                                                        <a class="buy_more"
                                                        data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                                                                <!--再次购买-->
                                                        <a class="Buy_again"
                                                        data-url="{{ route('carts.store') }}">@lang('basic.orders.buy again')</a>
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($key > 0)
                                                    <!--当循环的数据中超过两个子订单时从第二个子订单开始采用这种布局-->
                                            <tr class="order_top">
                                                <td class="col-pro-img">
                                                    <p class="p-img">
                                                        <a href="{{ route('seo_url', $item->sku->product->slug) }}">
                                                            <img src="{{ $item->sku->product->thumb_url }}">
                                                        </a>
                                                    </p>
                                                </td>
                                                <td class="col-pro-info">
                                                    <p class="p-info">
                                                        <a code="{{ $item->sku->id }}"
                                                        href="{{ route('seo_url', $item->sku->product->slug) }}">{{ App::isLocale('zh-CN') ? $item->sku->product->name_zh : $item->sku->product->name_en }}</a>
                                                    </p>
                                                </td>
                                                <td class="col-price">
                                                    <p class="p-price">
                                                        {{--<em>{{ $order->currency == 'USD' ? '&#36;' : '&#165;' }}</em>--}}
                                                        <em>{{ get_symbol_by_currency($order->currency) }} </em>
                                                        <span>{{ $item->price }}</span>
                                                    </p>
                                                </td>
                                                <td class="col-quty">{{ $item->number }}</td>
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
                            <a class="pre_page" href="javascript:void(0);">上一页</a>
                            <a class="next_page" href="javascript:void(0);">下一页</a>
                        </div>-->
                        @endif
                    </div>
                    <!--猜你喜欢-->
                    <div class="guess_like">
                        <div class="ordertable_title">
                            <p>@lang('app.you may also like')</p>
                        </div>
                        <div class="guess_like_content">
                            <ul>
                                @foreach($guesses as $guess)
                                    <li>
                                        <div class="collection_shop_img">
                                            <img class="lazy" data-src="{{ $guess->thumb_url }}">
                                        </div>
                                        <p class="commodity_title" title="{{ App::isLocale('zh-CN') ? $guess->name_zh : $guess->name_en }}">
                                            {{ App::isLocale('zh-CN') ? $guess->name_zh : $guess->name_en }}
                                        </p>
                                        <p class="collection_price">
                                            {{--<span class="new_price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }} {{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>--}}
                                            {{--<span class="old_price">{{ App::isLocale('en') ? '&#36;' : '&#165;' }} {{  App::isLocale('en') ? bcmul($guess->price_in_usd, 1.2, 2) : bcmul($guess->price, 1.2, 2) }}</span>--}}
                                            <span class="new_price">{{ get_global_symbol() }} {{ get_current_price($guess->price) }}</span>
                                            <span class="old_price">{{ get_global_symbol() }} {{ bcmul(get_current_price($guess->price), 1.2, 2) }}</span>
                                        </p>
                                        <a class="add_to_cart"
                                        href="{{ route('seo_url', $guess->slug) }}">@lang('app.see details')</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
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
                        <span class="tips_content">@lang('basic.users.Make_sure_to_delete')</span>
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
            //添加购物车
            function add_to_carts(sku_id, number, url, sku_id_lists, allHadAdd) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_id: sku_id,
                    number: number,
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false, //取消自动关闭
                        });
                    },
                    success: function (data) {
                        if (allHadAdd == shops_list.length) {
                            window.location.href = url + "?sku_id_lists=" + sku_id_lists;
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    },
                    complete: function () {
                        if (allHadAdd == shops_list.length) {
                            layer.close(loading_animation);
                        }
                    }
                });
            }
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
                    } else {
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

            window.onload = function () {
                $(".paying_time").each(function (index, element) {
                    var val = $(this).attr("mark");
                    var seconds_to_close_order = $(this).attr("seconds_to_close_order");
                    timeCount(val, seconds_to_close_order, '1');
                });
                $(".tobe_received_count").each(function (index, element) {
                    var val = $(this).attr("mark");
                    var seconds_to_complete_order = $(this).attr("seconds_to_complete_order");
                    timeCount(val, seconds_to_complete_order, "2");
                });
            }
        });
    </script>
@endsection
