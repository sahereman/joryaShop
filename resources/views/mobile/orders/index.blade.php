@extends('layouts.mobile')
@section('title', '我的订单')
@section('content')
    <div class="orderBox">
        <div class="orderHeadTop">
            <div class="headerBar">
                <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                     onclick="javascript:history.back(-1);"/>
                <span>我的订单</span>
            </div>
            <div class="orderHead">
                <div class="orderActive"
                     data-url="{{ route('mobile.orders.index') }}">@lang('basic.orders.All orders')</div>
                <div data-url="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_PAYING }}">@lang('basic.orders.Pending payment')</div>
                <div data-url="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_RECEIVING }}">@lang('basic.orders.Pending reception')</div>
                <div data-url="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_UNCOMMENTED }}">@lang('basic.orders.Pending comment')</div>
                <div data-url="{{ route('mobile.orders.index') . '?status=' . \App\Models\Order::ORDER_STATUS_REFUNDING }}">@lang('basic.orders.After-sale order')</div>
            </div>
        </div>
        <div class="orderMain">
            <!--暂无订单部分-->
            <div class="no_order">
                <img src="{{ asset('static_m/img/no_order.png') }}">
                <p>@lang('basic.users.No_orders_yet')</p>
                <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
            </div>
            {{--@if($orders->isEmpty())
                    <!--暂无订单部分-->
            <div class="no_order">
                <img src="{{ asset('static_m/img/no_order.png') }}">
                <p>@lang('basic.users.No_orders_yet')</p>
                <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
            </div>
            @else
                @foreach($orders as $order)
                    <div class="orderItem">
                        <div class="orderItemH">
                            <a href="{{ route('orders.show', $order->id) }}">
                                <span>@lang('basic.users.Order_number')： {{ $order->order_sn }}</span>
                            </a>
                            @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                <span class="orderItemState">@lang('basic.orders.Pending payment')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                <span class="orderItemState">@lang('basic.orders.Closed')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                <span class="orderItemState">@lang('basic.orders.Pending shipment')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                <span class="orderItemState">@lang('basic.orders.Pending reception')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                <span class="orderItemState">@lang('basic.orders.Pending comment')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                <span class="orderItemState">@lang('basic.orders.Completed')</span>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING)
                                <span class="orderItemState">@lang('basic.orders.After-sale order')</span>
                            @endif
                        </div>
                        <div class="orderItemDetail">
                            @foreach($order->snapshot as $order_item)
                                <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                    <img src="{{ $order_item['sku']['product']['thumb_url'] }}"/>
                                </a>
                                <div class="orderDal">
                                    <div class="orderIntroduce">
                                        <div class="goodsName">
                                            {{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}
                                        </div>
                                        <div class="goodsSku">
                                            {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                                        </div>
                                    </div>
                                    <div class="orderPrice">
                                        <div>
                                            {{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}
                                            {{ $order_item['price'] }}
                                        </div>
                                        <div class="orderItemNum">&#215; {{ $order_item['number'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="orderItemTotle">
                            @if(\Illuminate\Support\Facades\App::isLocale('en'))
                                <span>共{{ count($order->snapshot) }}件商品</span>
                            @else
                                <span>{{ count($order->snapshot) . (count($order->snapshot)>1 ? ' commodities ' : ' commodity ') . __('basic.orders.in total') }}</span>
                            @endif
                            <span class="orderCen">{{ \Illuminate\Support\Facades\App::isLocale('en') ? 'Sum' : '需付款' }}
                                : </span>
                            <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
                            <br>
                            <span>(@lang('order.Postage included'))</span>
                        </div>
                        <div class="orderBtns">
                            @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                    <!--待付款状态-->
                            <button class="orderBtnC" data-url="{{ route('orders.close', ['order' => $order->id]) }}">
                                @lang('app.cancel')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('mobile.orders.payment_method', ['order' => $order->id]) }}">
                                @lang('order.Immediate payment')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_CLOSED)
                                    <!--交易关闭状态-->
                            <button class="orderBtnC" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                                @lang('order.Delete order')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING)
                                    <!--待发货状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                                @lang('order.Request a refund')
                            </button>
                            <button class="orderBtnC">
                                @lang('basic.orders.Remind shipments')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING)
                                    <!--待收货状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                                @lang('order.Request a refund')
                            </button>
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.show_shipment', ['order' => $order->id]) }}">
                                @lang('order.View shipment details')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('orders.complete', ['order' => $order->id]) }}">
                                @lang('order.Confirm reception')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                    <!--交易完成&未评价状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.show_shipment', ['order' => $order->id]) }}">
                                @lang('order.View shipment details')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('mobile.orders.create_comment', ['order' => $order->id]) }}">
                                @lang('order.To comment')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                    <!--交易完成&已评价状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.show_shipment', ['order' => $order->id]) }}">
                                @lang('order.View shipment details')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('mobile.orders.show_comment', ['order' => $order->id]) }}">
                                @lang('order.View comments')
                            </button>
                            <button class="orderBtnS" data-url="{{ route('orders.destroy', ['order' => $order->id]) }}">
                                @lang('order.Delete order')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING && $order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND)
                                    <!--退款中(仅退款)状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.refund', ['order' => $order->id]) }}">
                                @lang('order.View after sales status')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                                @lang('order.Revoke the refund application')
                            </button>
                            @elseif($order->status == \App\Models\Order::ORDER_STATUS_REFUNDING && $order->refund->type == \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT)
                                    <!--退款中(退货并退款)状态-->
                            <button class="orderBtnC"
                                    data-url="{{ route('mobile.orders.refund_with_shipment', ['order' => $order->id]) }}">
                                @lang('order.View after sales status')
                            </button>
                            <button class="orderBtnS"
                                    data-url="{{ route('orders.revoke_refund', ['order' => $order->id]) }}">
                                @lang('order.Revoke the refund application')
                            </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif--}}
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".orderHead div").on("click", function (e) {
            $(".orderHead div").removeClass("orderActive");
            $(this).addClass("orderActive");
        });
        $(".orderItemDetail").on("click", function () {
            window.location.href = "{{ route('mobile.orders.show',\App\Models\Order::where('user_id',Auth::id())->first()) }}";
        });
    </script>
@endsection
