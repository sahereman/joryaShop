@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '个人中心 - 我的订单' : 'Personal Center - My Orders') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="orders_details">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('basic.users.The_order_details')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="order_content">
                <div class="order_info">
                    <!--订单状态，根据订单状态不同将显示不同的按钮
                        *注：有判断机制之后将每一个div后的去掉！-->
                    <div class="pull-left order_status_opera">
                        @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                <!--待付款状态-->
                        <div class="pending_payment status_area">
                            <p>
                                <img src="{{ asset('img/exclamation.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('basic.orders.Waiting for the customer to pay')</span>
                            </p>
                            <p id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}" class="cunt_down paying_time"
                               created_at="{{ strtotime($order->created_at) }}"
                               time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 60 }}"
                               seconds_to_close_order="{{ $seconds_to_close_order }}">
                                {{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
                                @lang('order.payment')
                                （@lang('order.If the order is not paid out, the system will automatically cancel the order')
                                ）
                            </p>
                            <p class="operation_area">
                                <a class="main_operation"
                                   href="{{ route('orders.payment_method', ['order' => $order->id]) }}">
                                    @lang('order.Immediate payment')
                                </a>
                                <a data-url="{{ route('orders.close', ['order' => $order->id]) }}">
                                    @lang('app.cancel')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                <!--待发货状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.The customer has paid, waiting for the seller to ship')</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation reminding_shipments">
                                    @lang('basic.orders.Remind shipments')
                                </a>
                                <a href="{{ route('orders.refund', ['order' => $order->id]) }}">
                                    @lang('order.Request a refund')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                <!--待收货状态-->
                        <div class="pending_payment status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.The seller has shipped, waiting for the customer to receive the goods')</span>
                            </p>
                            <p id="{{ $order->order_sn }}" mark="{{ $order->order_sn }}"
                               class="cunt_down tobe_received_count"
                               shipped_at="{{ strtotime($order->shipped_at) }}"
                               time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}"
                               seconds_to_complete_order="{{ $seconds_to_complete_order }}">
                                {{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}
                                @lang('order.for confirmation')（@lang('order.not confirmed after the timeout')）
                            </p>
                            <p class="operation_area">
                                <a class="main_operation"
                                   data-url="{{ route('orders.complete', ['order' => $order->id]) }}">
                                    @lang('order.Confirm reception')
                                </a>
                                <a href="{{ route('orders.refund_with_shipment', ['order' => $order->id]) }}">
                                    @lang('order.Request a refund')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                <!--待评价状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.Transaction complete')</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation"
                                   href="{{ route('orders.create_comment', ['order' => $order->id]) }}">
                                    @lang('order.To comment')
                                </a>
                                <a class="delete_order"
                                   data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                                    @lang('order.Delete order')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                <!--已评价状态-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.Transaction complete')</span>
                            </p>
                            <p class="operation_area">
                                <a class="main_operation"
                                   href="{{ route('orders.show_comment', ['order' => $order->id]) }}">
                                    @lang('order.View comments')
                                </a>
                                <a class="delete_order"
                                   data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                                    @lang('order.Delete order')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                <!--订单关闭-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.Transaction closed')</span>
                            </p>
                            <p class="operation_area">
                                <a class="delete_order"
                                   data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                                    @lang('order.Delete order')
                                </a>
                            </p>
                        </div>
                        @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                                <!--售后订单-->
                        <div class="pending_delivery status_area">
                            <p>
                                <img src="{{ asset('img/pending.png') }}">
                                <span>@lang('basic.users.Order_Status')：</span>
                                <span class="order_status_tips">@lang('order.After sale')</span>
                            </p>
                            <p class="operation_area">
                                @if($order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND)
                                    <a class="main_operation"
                                       href="{{ route('orders.refund', ['order' => $order->id]) }}">
                                        @lang('order.View after sales status')
                                    </a>
                                @elseif($order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                                    <a class="main_operation"
                                       href="{{ route('orders.refund_with_shipment', ['order' => $order->id]) }}">
                                        @lang('order.View after sales status')
                                    </a>
                                @endif
                                @if(! in_array($order->refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED, \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED]))
                                    <a class="revocation_after_sale"
                                       data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                                        @lang('order.Revoke the refund application')
                                    </a>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                    <!--订单信息-->
                    <div class="pull-right number_infor">
                        <p>
                            <span class="title">@lang('order.Order time')：</span>
                            <span>{{ $order->created_at }}</span>
                        </p>
                        <p>
                            <span class="title">@lang('order.Order number')：</span>
                            <span>{{ $order->order_sn }}</span>
                        </p>
                        <p>
                            <span class="title">@lang('order.Receiver')：</span>
                            <span>{{ $order->user_info['name'] }}</span>
                        </p>
                        <p>
                            <span class="title">@lang('order.Shipping address')：</span>
                            <span>{{ $order->user_info['address'] }}</span>
                        </p>
                    </div>
                </div>
                <!--物流信息根据需要判断是否显示，目前显示的订单状态：待收货、未评价、已评价、退款订单-->
                @if(!empty($order_shipment_traces))
                    <div class="logistics_infor">
                        <p class="logistics_title">@lang('order.Logistics information')</p>
                        <ul class="logistics_lists">
                            <li>
                                <span>@lang('order.Shipping method')：</span>
                                <span>@lang('order.express delivery')</span>
                            </li>
                            <li>
                                <span>@lang('order.Logistics company')：</span>
                                <span>{{ $shipment_company }}</span>
                            </li>
                            <li>
                                <span>@lang('order.Waybill number')：</span>
                                <span>{{ $shipment_sn }}</span>
                            </li>
                            <li>
                                <span>@lang('order.Logistics tracking')：</span>
                            </li>
                            @foreach($order_shipment_traces as $order_shipment_trace)
                                <li>
                                    <span>{{ $order_shipment_trace['AcceptTime'] . '   ' . $order_shipment_trace['AcceptStation'] . (isset($order_shipment_trace['Remark']) ? '   ' . $order_shipment_trace['Remark'] : '') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    @if(in_array($order->status, [\App\Models\Order::ORDER_STATUS_RECEIVING, \App\Models\Order::ORDER_STATUS_COMPLETED]))
                        <div class="logistics_infor">
                            <p class="logistics_title">@lang('order.Logistics information')</p>
                            <div class="no_img">
                                <img src="{{ asset('img/no_Logistics.png') }}">
                                <p>@lang('order.No logistics information')</p>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="order_list">
                    <!--订单商品列表-->
                    <!--订单表格与我的订单首页的判断方式一样-->
                    <table>
                        <thead>
                        <th></th>
                        <th>@lang('order.commodity')</th>
                        <th>@lang('order.Unit Price')</th>
                        <th>@lang('order.Quantity')</th>
                        <th>@lang('order.Subtotal')</th>
                        <th>@lang('basic.users.Order_Status')</th>
                        </thead>
                        <tbody>
                        @foreach($order->snapshot as $key => $order_item)
                            @if($key == 0)
                                <tr>
                                    <td class="col-pro-img">
                                        <a href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                        </a>
                                    </td>
                                    <td class="col-pro-info">
                                        <p class="p-info">
                                            <a class="commodity_description"
                                               href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                                {{ App::isLocale('zh-CN') ? $order_item['sku']['product']['name_zh'] : $order_item['sku']['product']['name_en'] }}
                                            </a>
                                            <br><br>
                                            <a class="commodity_description"
                                               href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                                {{--{{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}--}}
                                                {{ $order_item['sku']['attr_value_string'] }}
                                            </a>
                                        </p>
                                    </td>
                                    <td class="col-price">
                                        <p class="p-price">
                                            {{--<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>--}}
                                            <span>{{ get_symbol_by_currency($order->currency) }}</span>
                                            <span>{{ $order_item['price'] }}</span>
                                        </p>
                                    </td>
                                    <td class="col-quty">
                                        <p>{{ $order_item['number'] }}</p>
                                    </td>
                                    <td rowspan="{{ count($order->snapshot) }}" class="col-pay">
                                        <p>
                                            {{--<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>--}}
                                            <span>{{ get_symbol_by_currency($order->currency) }}</span>
                                            <span>{{ $order->total_amount }}</span>
                                        </p>
                                    </td>
                                    <td rowspan="{{ count($order->snapshot) }}" class="col-status">
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
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="col-pro-img">
                                        <a href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                        </a>
                                    </td>
                                    <td class="col-pro-info">
                                        <p class="p-info">
                                            <a class="commodity_description"
                                               href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                                {{ App::isLocale('zh-CN') ? $order_item['sku']['product']['name_zh'] : $order_item['sku']['product']['name_en'] }}
                                            </a>
                                            <br><br>
                                            <a class="commodity_description"
                                               href="{{ route('products.show', ['product' =>  $order_item['sku']['product']['id'],'slug'=> $order_item['sku']['product']['slug']]) }}">
                                                {{--{{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}--}}
                                                {{ $order_item['sku']['attr_value_string'] }}
                                            </a>
                                        </p>
                                    </td>
                                    <td class="col-price">
                                        <p class="p-price">
                                            {{--<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>--}}
                                            <span>{{ get_symbol_by_currency($order->currency) }}</span>
                                            <span>{{ $order_item['price'] }}</span>
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
                            <span class="title">@lang('order.Total product')：</span>
                            {{--<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_amount }}</span>--}}
                            <span>{{ get_symbol_by_currency($order->currency) }} {{ $order->total_amount }}</span>
                        </p>
                        <p class="freight">
                            <span class="title">@lang('order.Shipping fee')：</span>
                            {{--<span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_shipping_fee }}</span>--}}
                            <span>{{ get_symbol_by_currency($order->currency) }} {{ $order->total_shipping_fee }}</span>
                        </p>
                        <p class="total_cost">
                            <span class="title">@lang('order.Total amount payable')：</span>
                            {{--<span class="cost_of_total">{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>--}}
                            <span class="cost_of_total">{{ get_symbol_by_currency($order->currency) }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
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
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('order.Make sure to delete the order information')</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
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
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('order.Make sure to apply after withdrawing sales')</span>
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
                var seconds_to_close_order = $(this).attr('seconds_to_close_order');
                timeCount(val, seconds_to_close_order, '1');
            });
            //待收货订单
            $(".tobe_received_count").each(function (index, element) {
                var val = $(this).attr("mark");
                var start_time = $(this).attr("shipped_at") * 1000;
                var ending_time = $(this).attr('time_to_complete_order');
                var seconds_to_complete_order = $(this).attr('seconds_to_complete_order');
                timeCount(val, seconds_to_complete_order, "2");
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
                            $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _hour + ':' + _minute + ':' + _second + "@lang('order.payment')(@lang('order.If the order is not paid out, the system will automatically cancel the order'))");
                        } else {
                            $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _day + ':' + _hour + ':' + _minute + ':' + _second + "@lang('order.for confirmation')(@lang('order.not confirmed after the timeout'))");
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

            //删除订单
            $(".delete_order").on('click', function () {
                $(".order_delete .textarea_content").find("span").attr("code", $(this).attr("data-url"));
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
                        window.location.href = "{{ route('orders.index') }}";
                    },
                    error: function (err) {
                        console.log(err);
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
            //撤销售后
            $('.revocation_after_sale').on("click", function () {
                $(".order_delete .textarea_content").find("span").attr("code", $(this).attr("data-url"));
                $('.order_after_sale').show()
            });
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
                                title: "@lang('app.Prompt')",
                                content: "@lang('app.Unable to complete operation')",
                                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                            });
                        }
                    }
                });
            });
            $(".reminding_shipments").on("click", function () {
                layer.msg("@lang('basic.orders.The seller has been reminded to ship the goods, please wait for good news')");
            })
        });
    </script>
@endsection
