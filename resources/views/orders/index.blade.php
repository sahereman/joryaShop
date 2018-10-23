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
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <ul class="myorder_classification">
                    <li class="active">
                        <a href="{{ route('orders.index') . '?status=paying' }}">
                            <span>所有订单</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=paying' }}">
                            <span>待付款</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=receiving' }}">
                            <span>待收货</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=uncommented' }}">
                            <span>待评价</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') . '?status=refunding' }}">
                            <span>售后订单</span>
                        </a>
                    </li>
                </ul>
                <ul class="ordertable_title">
                    <li class="order_details">
                        <span>订单详情</span>
                    </li>
                    <li class="order_price">
                        <span>单价</span>
                    </li>
                    <li class="order_num">
                        <span>数量</span>
                    </li>
                    <li class="order_pay">
                        <span>实付款</span>
                    </li>
                    <li class="order_status">
                        <span>订单状态</span>
                    </li>
                    <li class="order_operation">
                        <span>操作</span>
                    </li>
                </ul>
                <!--订单列表分为两部分，1、暂无订单时展现其他时候隐藏。2、存在订单时显示.需进行判断-->
                <div class="order_list">
                    @if($orders->isEmpty())
                            <!--暂无订单部分-->
                    <div class="no_order">
                        <img src="{{ asset('img/no_order.png') }}">
                        <p>还没有任何订单哦~</p>
                        <a href="{{ route('root') }}">去逛逛</a>
                    </div>
                    @else
                            <!--订单部分-->
                    <div class="order-group">
                        @foreach($orders as $order)
                            <div class="order-group-item">
                                <div class="o-info">
                                    <div class="col-info pull-left">
                                     <span class="o-no">
                                         订单编号：
                                         <a href="{{ route('orders.show', $order->id) }}">{{ $order->order_sn }}</a>
                                     </span>
                                    </div>
                                    <div class="col-delete pull-right">
                                        <a>
                                            <img src="{{ asset('img/delete.png') }}">
                                        </a>
                                    </div>
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
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">
                                                        <img src="{{ $item->sku->product->thumb_url }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">{{ $item->sku->product->name_zh }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    <em>¥</em>
                                                    <span>{{ $item->price }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">1</td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-pay">
                                                <p>
                                                    <em>¥</em>
                                                    <span>{{ $order->total_amount }}</span>
                                                </p>
                                            </td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-status">
                                                <p>{{ $order->translateStatus($order->status) }}</p>
                                            </td>
                                            <td rowspan="{{ $order->items->count() }}" class="col-operate">
                                                <p class="p-button">
                                                    <!--以下按钮除再次购买并不同时展示根据订单状态进行调整-->
                                                    @if($order->status == \App\Models\Order::ORDER_STATUS_PAYING)
                                                            <!--订单待支付-->
                                                    <!--付款或再次购买隐藏显示取消订单-->
                                                    <!--系统自动关闭订单倒计时-->
                                                    <span class="count_down" created_at="{{ strtotime($order->created_at) }}" time_to_close_order="{{ \App\Models\Config::config('time_to_close_order') * 3600 }}">{{ generate_order_ttl_message($order->create_at, \App\Models\Order::ORDER_STATUS_PAYING) }}</span>
                                                    <a class="payment" href="{{ route('users.home') }}">付款</a>
                                                    <a class="cancellation" href="{{ route('root') }}">取消订单</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_SHIPPING && $order->shipped_at == null)
                                                            <!--订单待发货-->
                                                    <a class="reminding_shipments"
                                                       href="{{ route('users.home') }}">提醒发货</a>
                                                    <a class="buy_more" href="{{ route('root') }}">再次购买</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_RECEIVING && $order->received_at == null)
                                                            <!--订单待收货-->
                                                    <!--确认收货-->
                                                    <!--系统自动确认订单倒计时-->
                                                    <span class="count_down" shipped_at="{{ strtotime($order->shipped_at) }}" time_to_complete_order="{{ \App\Models\Config::config('time_to_complete_order') * 3600 * 24 }}">{{ generate_order_ttl_message($order->shipped_at, \App\Models\Order::ORDER_STATUS_RECEIVING) }}</span>
                                                    <a class="confirmation_receipt"
                                                       href="{{ route('users.home') }}">确认收货</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at == null)
                                                            <!--订单待评价-->
                                                    <a class="evaluate" href="{{ route('users.home') }}">评价</a>
                                                    @elseif($order->status == \App\Models\Order::ORDER_STATUS_COMPLETED && $order->commented_at != null)
                                                            <!--订单已评价-->
                                                    <!--查看评价-->
                                                    <a class="View_evaluation"
                                                       href="{{ route('users.home') }}">查看评价</a>
                                                    @elseif(in_array($order->status, [\App\Models\Order::ORDER_STATUS_CLOSED, \App\Models\Order::ORDER_STATUS_COMPLETED]))
                                                            <!--删除订单-->
                                                    <a class="Delete_order"
                                                       href="{{ route('users.home') }}">删除订单</a>
                                                    @endif
                                                            <!--再次购买-->
                                                    <a class="buy_more" href="{{ route('root') }}">再次购买</a>
                                                    <!--再次购买-->
                                                    <a class="Buy_again" href="{{ route('users.home') }}">再次购买</a>
                                                </p>
                                            </td>
                                        </tr>
                                        @else
                                                <!--当循环的数据中超过两个子订单时从第二个子订单开始采用这种布局-->
                                        <tr class="order_top">
                                            <td class="col-pro-img">
                                                <p class="p-img">
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">
                                                        <img src="{{ $item->sku->product->thumb_url }}">
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="col-pro-info">
                                                <p class="p-info">
                                                    <a href="{{ route('products.show', $item->sku->product->id) }}">{{ $item->sku->product->name_zh }}</a>
                                                </p>
                                            </td>
                                            <td class="col-price">
                                                <p class="p-price">
                                                    <em>¥</em>
                                                    <span>{{ $item->price }}</span>
                                                </p>
                                            </td>
                                            <td class="col-quty">1</td>
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
                        <a class="pre_page" href="{{ route('orders.index') }}">上一页</a>
                        <a class="next_page" href="{{ route('orders.index') }}">下一页</a>
                    </div>--}}
                    @endif
                </div>
                <!--猜你喜欢-->
                <div class="guess_like">
                    <div class="ordertable_title">
                        <p>猜你喜欢</p>
                    </div>
                    <div class="guess_like_content swiper-container">
                        <ul class="swiper-wrapper">
                            @foreach($guesses as $guess)
                                <li class="swiper-slide">
                                    <div class="collection_shop_img">
                                        <img src="{{ $guess->thumb_url }}">
                                    </div>
                                    <p class="commodity_title">{{ $guess->name_zh }}</p>
                                    <p class="collection_price">
                                        <span class="new_price">¥ {{ $guess->price }}</span>
                                        <span class="old_price">¥ {{ $guess->price + random_int(300, 500) }}</span>
                                    </p>
                                    <a class="add_to_cart" href="">加入购物车</a>
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
                    <span>提示</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>确定要删除订单信息？</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
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
            $(".order-group").on('click', '.col-delete', function () {
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
        });
    </script>
@endsection
