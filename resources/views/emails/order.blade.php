@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '订单详情' : 'Order Details') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="pre-payment">
        <div class="main-content">
            {{-- 面包屑导航 --}}
            <div class="Crumbs">
                <a class="dis_ni" href="{{ route('root') }}">@lang('basic.home')</a>
                <span class="dis_ni">></span>
                <a class="dis_ni" href="javascript:void(0);">@lang('order.Confirm Order')</a>
            </div>
            {{-- 订单内容 --}}
            <div class="orders-content">
                {{-- 左侧信息相关 --}}
                <div class="orders-left">
                    {{-- 商品列表 --}}
                    <div class="products-part">
                        {{-- 标题 --}}
                        <div class="products-part-title">
                            <span class="products-title-item">Item</span>
                            <span class="products-title-qty">QTY</span>
                            <span class="products-title-amount">AMount</span>
                        </div>
                        {{-- 内容 --}}
                        <div class="products-part-items">
                            @if($order)
                                @foreach($order->items as $item)
                                    <div class="products-part-item">
                                        {{-- 商品信息 --}}
                                        <div class="product">
                                            {{-- 商品图片 --}}
                                            <div class="product-img">
                                                <a class="cur_p" href="avascript:void(0);">
                                                    <img class="lazy" data-src="{{ $item['sku']->product->thumb_url }}">
                                                </a>
                                            </div>
                                            {{-- 商品介绍 --}}
                                            <div class="product-info">
                                                <a class="cur_p" href="javascript:void (0)">
                                                    <span>{{ App::isLocale('zh-CN') ? $item['sku']->product->name_zh : $item['sku']->product->name_en }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        {{-- 商品数量 --}}
                                        <div class="product-qty">
                                            <input class="left center count" type="text" readonly value="{{ $item['number'] }}" title="QTY">
                                        </div>
                                        {{-- 商品价格 --}}
                                        <div class="product-amount">
                                            <div class="amount-price">
                                                <span>{{ get_global_symbol() }}</span>
                                                <span class="single-price">{{ bcmul($item['price'], $item['number'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title active">
                                                ORDER DETAILS <span class="iconfont">&#xe605;</span>
                                            </p>
                                            <div class="order-details" style="display: block;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->custom_attr_values as $attr_value)
                                                    <div class="order-detail">
                                                        <div class="order-detail-name">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_DUPLICATE)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title active">
                                                ORDER DETAILS <span class="iconfont">&#xe605;</span>
                                            </p>
                                            <div class="order-details" style="display: block;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->duplicate_attr_values as $attr_value)
                                                    <div class="order-detail">
                                                        <div class="order-detail-name">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_REPAIR)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title active">
                                                ORDER DETAILS
                                                <span class="iconfont">&#xe605;</span>
                                            </p>
                                            <div class="order-details" style="display: block;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->repair_attr_values as $attr_value)
                                                    <div class="order-detail">
                                                        <div class="order-detail-name">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title active">
                                                ORDER DETAILS
                                                <span class="iconfont">&#xe605;</span>
                                            </p>
                                            <div class="order-details" style="display: block;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->attr_values as $attr_value)
                                                    <div class="order-detail">
                                                        <div class="order-detail-name">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- 用户地址相关 --}}
                    <div class="address-info">
                        <div class="address-info-title">
                            <img src="{{ asset('img/peyment-address.png') }}" alt="Lyricalhair">
                            <p>User Address</p>
                        </div>
                        {{-- 默认地址，正常显示 --}}
                        <div class="address-info-content pre_payment_header add-new-address-box">
                            {{-- 有地址 --}}
                            <div class="address-name">
                                <span class="address_name">{{ $order->user_info['name'] }}</span>
                            </div>
                            <div class="address-fullAddress">
                                <span class="address_location">{{ $order->user_info['address'] }}</span>
                            </div>
                            <div class="address-phone">
                                <span class="address_phone">{{ $order->user_info['phone'] }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- 优惠券相关 --}}
                    <div class="discount-coupon">
                        <div class="Currency-options">
                            <p class="currency-options-title">Currency</p>
                            <div>
                                <p class="currency_selection">
                                    <a href="javascript:void(0);" class="active" code="dollar" country="USD">
                                        <span class="iconfont">&#xe607;</span>
                                        @lang('order.Dollars')
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="available_coupons">
                            <p class="available_coupons-title">Coupons</p>
                            @if($order->coupons->isNotEmpty())
                                <p class="coupon-select">
                                    <span>
                                        {{ $order->coupons->first()->coupon_name }} - {{ $order->coupons->first()->saved_fee }}
                                    </span>
                                </p>
                            @else
                                <p class="coupon-select">
                                    <span>Don't use coupons</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- 右侧金额相关 --}}
                <div class="oreders-right">
                    {{-- 留言 --}}
                    <div class="order-note">
                        <p class="order-note-title">Order note</p>
                        <textarea class="remark" placeholder="Optional, leave a message to the seller">{{ $order->remark }}</textarea>
                    </div>
                    {{-- 邮件 --}}
                    <div class="order-email">
                        <p class="order-email-title">Order email</p>
                        <div class="email-detail">
                            <p class="email-question">Do you want to send this order information to email?</p>
                            @if($order->email)
                                <p class="inputBox">
                                    <label>
                                        <input type="radio" name="emailChoose" value="1">
                                        <span>Yes, I do</span>
                                    </label>
                                </p>
                                <p class="dis_n email-int">
                                    <input type="email" class="order-email-num" value="{{ $order->email }}">
                                </p>
                            @else
                                <p class="inputBox">
                                    <label>
                                        <input type="radio" name="emailChoose" value="0">
                                        <span>No, Thank you</span>
                                    </label>
                                </p>
                            @endif
                        </div>
                    </div>
                    {{-- 费用 --}}
                    <div class="order-fee">
                        <p class="order-fee-title">Order Fee</p>
                        <div class="amount-detail">
                            <p>
                                <span>@lang('order.Sum')：</span>
                                <span class="dollar_num amount_of_money">
                                    &#36; <span>{{ $order->total_amount }}</span>
                                </span>
                            </p>
                            <p>
                                <span>@lang('order.freight')：</span>
                                <span class="dollar_num amount_of_money">
                                    &#36; <span class="total_shipping_fee">{{ $order->total_shipping_fee }}</span>
                                </span>
                            </p>
                        </div>
                        <p class="total-price">
                            <span>@lang('order.Amount payable')：</span>
                            <span class="red dollar_num amount_of_money">
                                &#36; <span class="total_price">{{ $order->payment_amount }}</span>
                            </span>
                        </p>
                        <p class="saved-fee">
                            <span>(Saved fee: &#36;  <span class="discounts_num">{{ $order->saved_fee }}</span> )</span>
                        </p>
                        <p class="address_info">
                            <span class="address_name address_name_bottom">{{ $order->user_info['name'] }}</span>
                        </p>
                        <p>
                            <span class="address_info address_province address_province_bottom">{{ $order->user_info['address'] }}</span>
                        </p>
                        <p>
                            <span class="address_phone address_phone_bottom">{{ $order->user_info['phone'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
<script type="text/javascript">
  $("header").css("display","none");
  $("footer").css("display","none");
</script>
@endsection