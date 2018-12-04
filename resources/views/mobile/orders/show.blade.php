@extends('layouts.mobile')
@section('title', '订单详情')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>订单详情</span>
    </div>
    <div class="orderDetailBox">
        @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                <!--待付款-->
        <div class="orderDHead">
            <div class="odrHeadLeft">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('basic.orders.Waiting for buyers payment')</span>
            </div>
            <div class="odrHeadRight">
                <div created_at="{{ strtotime($order->created_at) }}"
                     time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 60 }}"
                     seconds_to_close_order="{{ $seconds_to_close_order }}">
                    <span>
                        {{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}
                        @lang('order.payment')
                        （@lang('order.If the order is not paid out, the system will automatically cancel the order')）
                    </span>
                    <span id="getting-started"></span>
                </div>
                <div class="odrHeadRightPri">
                    <span>需付款:</span>
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
                <span>@lang('order.The buyer has paid, waiting for the seller to ship')</span>
            </div>
            <div class="odrHeadRight">
                <img src="{{ asset('static_m/img/img_goods.png') }}"/>
            </div>
        </div>
        @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                <!--卖家已发货，等待买家收货-->
        <div class="orderDHead">
            <div class="odrHeadLeft" shipped_at="{{ strtotime($order->shipped_at) }}"
                 time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}"
                 seconds_to_complete_order="{{ $seconds_to_complete_order }}">
                <img src="{{ asset('static_m/img/icon_wait.png') }}"/>
                <span>@lang('order.The seller has shipped, waiting for the buyer to receive the goods')</span>
                <p class="odrLeftS">
                    {{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}
                    @lang('order.for confirmation')（@lang('order.not confirmed after the timeout')）
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
                    <div>
                        地址：{{ $order->user_info['address'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="ordDetail">
            @foreach($order->snapshot as $order_item)
                <img src="{{ $order_item['sku']['product']['thumb_url'] }}"/>
                <div>
                    <div class="ordDetailName">
                        <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                            {{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}
                        </a>
                    </div>
                    <div>
                        <span>
                            数量：{{ $order_item['number'] }}
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
            @endforeach
        </div>
        <div class="ordDetailCode">
            <div>订单编号：{{ $order->sn }}</div>
            <div>下单时间: {{ $order->created_at }}</div>
        </div>
        <div class="ordPriBox">
            <div class="ordPriItem">
                <label>商品总额</label>
                <label>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_amount }}</span>
                </label>
            </div>
            <div class="ordPriItem">
                <label>运费</label>
                <label>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_shipping_fee }}</span>
                </label>
            </div>
        </div>
        <div class="ordDetailRealPri">
            <label>需付款:</label>
            <span>
                {{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}
            </span>
        </div>
        <div class="ordDetailBtn">
            @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                <a class="ordDetailBtnC" data-url="{{ route('orders.close', ['order' => $order->id]) }}">
                    @lang('app.cancel')
                </a>
                <a class="ordDetailBtnS" href="{{ route('mobile.orders.payment_method', ['order' => $order->id]) }}">
                    @lang('order.Immediate payment')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                <a class="" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                <a class="" href="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                    @lang('order.Request a refund')
                </a>
                <a class="">
                    @lang('basic.orders.Remind shipments')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                <a href="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                    @lang('order.Request a refund')
                </a>
                <a class="main_operation" data-url="{{ route('orders.complete', ['order' => $order->id]) }}">
                    @lang('order.Confirm receipt')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                <a class="" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
                <a class="" href="{{ route('mobile.orders.create_comment', ['order' => $order->id]) }}">
                    @lang('order.to evaluate')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                <a class="" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                    @lang('order.Delete order')
                </a>
                <a class="" href="{{ route('mobile.orders.show_comment', ['order' => $order->id]) }}">
                    @lang('order.View reviews')
                </a>
            @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                @if(isset($order_refund_type) && $order_refund_type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND)
                    <a class="main_operation" href="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                        @lang('order.View after sales status')
                    </a>
                    <a class="revocation_after_sale"
                       data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                        @lang('order.After withdrawing sales')
                    </a>
                @elseif(isset($order_refund_type) && $order_refund_type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                    <a class="main_operation"
                       href="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                        @lang('order.View after sales status')
                    </a>
                    <a class="revocation_after_sale"
                       data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                        @lang('order.After withdrawing sales')
                    </a>
                @else
                    <a class="revocation_after_sale"
                       data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                        @lang('order.After withdrawing sales')
                    </a>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $("#getting-started")
                .countdown("2018/11/22 10:00", function (event) {
                    $(this).text(
                            event.strftime('%H:%M:%S')
                    );
                });
    </script>
@endsection


