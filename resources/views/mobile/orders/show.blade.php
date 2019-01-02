@extends('layouts.mobile')
@section('title', (App::isLocale('en') ? 'Order Details' : '订单详情') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar fixHeader">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('order.Order Details')</span>
        @endif
    </div>
    <div class="orderDetailBox">
        @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                <!--待付款-->
        <div class="orderDHead">
            <div class="odrHeadLeft">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('basic.orders.Waiting for the customer to pay')</span>
            </div>
            <div class="odrHeadRight">
                <div class="paying_time" mark="{{ $order->order_sn }}" created_at="{{ strtotime($order->created_at) }}"
                     time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 60 }}"
                     seconds_to_close_order="{{ $seconds_to_close_order }}">
                    <span id="{{ $order->order_sn }}">
                        {{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
                        @lang('order.payment')
                    </span>
                    <!--<span id="getting-started"></span>-->
                </div>
                <div class="odrHeadRightPri">
                    <span>@lang('order.Payment Required'):</span>
                    <span>
                        {{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
                    </span>
                </div>
            </div>
        </div>
        @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                <!--交易关闭-->
        <div class="orderDHead">
            <div class="odrHeadLeft">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('order.Transaction closed')</span>
            </div>
        </div>
        @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                <!--待发货-->
        <div class="orderDHead">
            <div class="odrHeadLeft">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('order.The customer has paid, waiting for the seller to ship')</span>
            </div>
            <div class="odrHeadRight">
                <img src="{{ asset('static_m/img/img_goods.png') }}"/>
            </div>
        </div>
        @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                <!--卖家已发货，等待买家收货-->
        <div class="orderDHead">
            <div class="odrHeadLeft tobe_received_count" mark="{{ $order->order_sn }}"
                 shipped_at="{{ strtotime($order->shipped_at) }}"
                 time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}"
                 seconds_to_complete_order="{{ $seconds_to_complete_order }}">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('order.The seller has shipped, waiting for the customer to receive the goods')</span>
                <p id="{{ $order->order_sn }}" class="odrLeftS">
                    {{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}
                    @lang('order.for confirmation')
                </p>
            </div>
        </div>
        @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED)
                <!--交易完成-->
        <div class="orderDHead">
            <div class="odrHeadLeft">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('order.Transaction complete')</span>
            </div>
        </div>
        @endif
        <div class="ordUser">
            <!--查看物流-->
            <!--物流信息根据需要判断是否显示，目前显示的订单状态：待收货、未评价、已评价、退款订单-->
            @if(!empty($order_shipment_traces))
                <a href="{{ route('mobile.orders.show_shipment', ['order' => $order->id]) }}">
                    <div class="orderUserLogistics">
                        <img src="{{ asset('static_m/img/icon_Delivery.png') }}" alt=""/>
                        <div class="ordUserInfoRight"
                             data-url="{{ route('mobile.orders.show_shipment', ['order' => $order->id]) }}">
                            <div class="logisticsBox">
                                <span>{{ $order_shipment_traces[0]['AcceptStation'] }}</span>
                                <br>
                                @if(isset($order_shipment_traces[0]['Remark']))
                                    <span>{{ $order_shipment_traces[0]['Remark'] }}</span>
                                    <br>
                                @endif
                            </div>
                            <div class="logisticsDate">
                                {{ $order_shipment_traces[0]['AcceptTime'] }}
                            </div>
                            <img src="{{ asset('static_m/img/icon_more.png') }}"/>
                        </div>
                    </div>
                </a>
            @endif
            <div class="ordUserInfo">
                <img src="{{ asset('static_m/img/icon_address.png') }}" alt=""/>
                <div class="ordUserInfoRight">
                    <div>
                        <span>{{ $order->user_info['name'] }}</span>
                        <label>{{ substr($order->user_info['phone'], 0, 3) . '****' . substr($order->user_info['phone'], -4) }}</label>
                    </div>
                    <p class="address_text">@lang('basic.address.address')：{{ $order->user_info['address'] }}</p>
                </div>
            </div>
        </div>
        <div class="ordDetail">
            @foreach($order->snapshot as $order_item)
                <div class="ordDetail_item">
                    <img src="{{ $order_item['sku']['product']['thumb_url'] }}"/>
                    <div>
                        <div class="ordDetailName">
                            <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                {{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}
                            </a>
                        </div>
                        <div>
                            <span>
                                @lang('basic.users.quantity')：{{ $order_item['number'] }}
                                &nbsp;&nbsp;
                            </span>
                            <span>
                                <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                    {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                                </a>
                            </span>
                        </div>
                        <div class="ordDetailPri">
                            <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>
                            <span>{{ $order_item['price'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="ordDetailCode">
            <div>@lang('order.Order number')：{{ $order->order_sn }}</div>
            <div>@lang('order.Place an order time')：{{ $order->created_at }}</div>
        </div>
        <div class="ordPriBox">
            <div class="ordPriItem">
                <label>@lang('order.Total Merchandise')</label>
                <label>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_amount }}</span>
                </label>
            </div>
            <div class="ordPriItem">
                <label>@lang('order.freight')</label>
                <label>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_shipping_fee }}</span>
                </label>
            </div>
        </div>
        <div class="ordDetailRealPri">
            <label>@lang('order.Payment Required'):</label>
            <span>
                {{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
            </span>
        </div>
        <div class="ordDetailBtn">
            @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                <a class="ordDetailBtnC cancel" data-url="{{ route('orders.close', ['order' => $order->id]) }}">
                    @lang('app.cancel')
                </a>
                <a class="ordDetailBtnS payment"
                   href="{{ route('mobile.orders.payment_method', ['order' => $order->id]) }}">
                    @lang('order.Immediate payment')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                <a class="ordDetailBtnS Delete" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                <a class="ordDetailBtnC" href="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                    @lang('order.Request a refund')
                </a>
                <a class="ordDetailBtnS Remind_shipments">
                    @lang('basic.orders.Remind shipments')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                <a class="ordDetailBtnC"
                   href="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                    @lang('order.Request a refund')
                </a>
                <a class="main_operation Confirm_reception ordDetailBtnS"
                   data-url="{{ route('orders.complete', ['order' => $order->id]) }}">
                    @lang('order.Confirm reception')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                <a class="ordDetailBtnC Delete" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
                <a class="ordDetailBtnS" href="{{ route('mobile.orders.create_comment', ['order' => $order->id]) }}">
                    @lang('order.To comment')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                <a class="ordDetailBtnC Delete" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
                <a class="ordDetailBtnS" href="{{ route('mobile.orders.show_comment', ['order' => $order->id]) }}">
                    @lang('order.View comments')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                @if($order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND)
                    <a class="main_operation ordDetailBtnC"
                       href="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                        @lang('order.View after sales status')
                    </a>
                @elseif($order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                    <a class="main_operation ordDetailBtnC"
                       href="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                        @lang('order.View after sales status')
                    </a>
                @endif
                @if(! in_array($order->refund->status, [\App\Models\OrderRefund::ORDER_REFUND_STATUS_REFUNDED, \App\Models\OrderRefund::ORDER_REFUND_STATUS_DECLINED]))
                    <a class="revocation_after_sale ordDetailBtnS"
                       data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                        @lang('order.Revoke the refund application')
                    </a>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            //待付款订单
            $(".paying_time").each(function () {
                var val = $(this).attr("mark");
                var seconds_to_close_order = $(this).attr('seconds_to_close_order');
                timeCount(val, seconds_to_close_order, '1');
            });
            //待收货订单
            $(".tobe_received_count").each(function (index, element) {
                var val = $(this).attr("mark");
                var seconds_to_complete_order = $(this).attr('seconds_to_complete_order');
                timeCount(val, seconds_to_complete_order, "2");
            });
        });
        //倒计时方法封装
        function timeCount(remain_id, totalS, type) {
            function _fresh() {
                totalS--;
                if (totalS > 0) {
                    var _day = (Array(2).join(0) + parseInt((totalS / 3600) % 24 / 24)).slice(-2);
                    var _hour = (Array(2).join(0) + parseInt((totalS / 3600) % 24)).slice(-2);
                    var _minute = (Array(2).join(0) + parseInt((totalS / 60) % 60)).slice(-2);
                    var _second = (Array(2).join(0) + parseInt(totalS % 60)).slice(-2);
                    if (type == '1') {
                        $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _hour + ':' + _minute + ':' + _second + "@lang('order.payment')");
                    } else {
                        $('#' + remain_id).html("@lang('basic.orders.Remaining')" + _day + ':' + _hour + ':' + _minute + ':' + _second + "@lang('order.for confirmation')");
                    }
                } else {
                    if (type == '1') {
                        $('#' + remain_id).html("@lang('order.Order has timed out')");
                    } else {
                        $('#' + remain_id).html("@lang('order.Order has timed out')");
                    }
                }
            }

            _fresh();
            var sh = setInterval(_fresh, 1000);
        }
        //点击提醒发货
        $(".ordDetailBtn").on("click", ".Remind_shipments", function () {
            layer.open({
                content: "@lang('basic.orders.The seller has been reminded to ship the goods, please wait for good news')",
                skin: 'msg',
                time: 2, //2秒后自动关闭
            });
        });
        //取消订单
        $(".ordDetailBtn").on("click", ".cancel", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('basic.orders.Make sure to cancel the order')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = clickDom.attr("data-url");
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            layer.open({
                                content: "@lang('order.Order cancelled successfully')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                            window.location.href = "{{ route('mobile.orders.index') }}";
                        },
                        error: function (err) {
                            console.log(err.status);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, //2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        //删除订单
        $(".ordDetailBtn").on("click", ".Delete", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('order.Make sure to delete the order information')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "DELETE",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = clickDom.attr("data-url");
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (json) {
                            layer.open({
                                content: "@lang('order.Order deleted successfully')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                            window.location.href = "{{ route('mobile.orders.index') }}";
                        },
                        error: function (err) {
                            console.log(err.status);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, //2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        //撤销售后申请
        $(".ordDetailBtn").on("click", ".revocation_after_sale", function () {
            var clickDom = $(this);
            // window.location.href = "{{ config('app.url') }}" + "/mobile/orders/" + $(this).attr("code") + "/revoke_refund";
            layer.open({
                content: "@lang('order.Make sure to apply after withdrawing sales')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = clickDom.attr("data-url");
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            layer.open({
                                content: "@lang('order.Cancel the application successfully')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                            window.location.href = "{{ route('mobile.orders.index') }}";
                        },
                        error: function (err) {
                            console.log(err);
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, //2秒后自动关闭
                                });
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        //确认收货 
        $(".ordDetailBtn").on("click", ".Confirm_reception", function () {
            var clickDom = $(this);
            layer.open({
                content: "@lang('order.Are you sure you want to confirm the receipt')",
                btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                yes: function (index) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = clickDom.attr("data-url");
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            layer.open({
                                content: "@lang('order.Confirm receipt success')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                            window.location.href = "{{ route('mobile.orders.index') }}";
                        },
                        error: function (err) {
                            if (err.status == 403) {
                                layer.open({
                                    content: "@lang('app.Unable to complete operation')",
                                    skin: 'msg',
                                    time: 2, //2秒后自动关闭
                                });
                            }
                        }
                    });
                    layer.close(index);
                }
            });
        });
    </script>
@endsection


