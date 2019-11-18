<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Details</title>
</head>
<body style="padding:0;margin:0;background: #f5f5f5;">
    <div class="pre-payment" style="position: relative;background: #f5f5f5;width: 100%;">
        <div class="main-content" style="max-width: 1680px;min-width: 1200px;margin: auto;">
            {{-- 订单内容 --}}
            <div class="orders-content" style="width: 100%;display: -webkit-box;display: -ms-flexbox;display: flex;padding-bottom: 10px;padding-top: 10px;">
                {{-- 左侧信息相关 --}}
                <div class="orders-left" style="margin: 0 auto 0 0;width: 73%;">
                    {{-- 商品列表 --}}
                    <div class="products-part" style="padding: 20px;padding-bottom: 0;margin-bottom: 10px;background-color: #fff;">
                        {{-- 标题 --}}
                        <div class="products-part-title" style="width: 100%;padding: 10px 0; border: 0;border-bottom: 2px solid #444;color: #111;font-weight: bold;">
                            <span class="products-title-item" style="display: inline-block;width: 79%;text-align: left;">Item</span>
                            <span class="products-title-qty" style="display: inline-block;width: 10%;text-align: right;">QTY</span>
                            <span class="products-title-amount" style="display: inline-block;width: 10%;text-align: right;">AMount</span>
                        </div>
                        {{-- 内容 --}}
                        <div class="products-part-items" style="padding: 20px 0;border: 0;border-bottom: 1px solid #eee;">
                            @if($order)
                                @foreach($order->items as $item)
                                    <div class="products-part-item" style="display: flex;overflow: hidden;margin-bottom: 15px;">
                                        {{-- 商品信息 --}}
                                        <div class="product" style="display: flex;width: 79%;">
                                            {{-- 商品图片 --}}
                                            <div class="product-img" style="width: 120px;margin-right: 17px;">
                                                {{-- <a class="cur_p" href="avascript:void(0);"> --}}
                                                    <img src="{{ $item['sku']->product->thumb_url }}" style="width: 100%;">
                                                {{-- </a> --}}
                                            </div>
                                            {{-- 商品介绍 --}}
                                            <div class="product-info" style="width: calc(100% - 130px);color: #666;line-height: 24px;font-size: 14px;">
                                                {{-- <a class="cur_p" href="javascript:void (0)"> --}}
                                                    <span>{{ App::isLocale('zh-CN') ? $item['sku']->product->name_zh : $item['sku']->product->name_en }}</span>
                                                {{-- </a> --}}
                                            </div>
                                        </div>
                                        {{-- 商品数量 --}}
                                        <div class="product-qty" style="width: 10%;text-align: right;color: #666;line-height: 24px;font-size: 14px;">
                                            <span style="width: 100%;text-align: right;" >{{ $item['number'] }}</span>
                                        </div>
                                        {{-- 商品价格 --}}
                                        <div class="product-amount" style="width: 10%;text-align: right;color: #CE1020;line-height: 24px;font-weight: 400;font-size: 14px;">
                                            <div class="amount-price">
                                                <span>{{ get_global_symbol() }}</span>
                                                <span class="single-price">{{ bcmul($item['price'], $item['number'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom" style="width: calc(100% - 130px);margin: auto;margin-right: 0;">
                                            <p class="order-detail-title" 
                                                style="height: 40px;
                                                background-color: #eee;
                                                text-align: center;
                                                line-height: 40px;
                                                font-size: 12px;
                                                color: #444;
                                                font-weight: 400;
                                                cursor: pointer;">
                                                ORDER DETAILS 
                                                {{-- <span class="iconfont">&#xe605;</span> --}}
                                            </p>
                                            <div class="order-details" style="padding: 20px 10px;width: 98%;background-color: #f5f5f5;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->custom_attr_values as $attr_value)
                                                    <div class="order-detail" style="display: flex;color: #111;line-height: 30px;">
                                                        <div class="order-detail-name" style="width: 50%;font-size: 14px;"> 
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value" style="width: 50%;text-align: right;font-size: 14px;">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_DUPLICATE)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom" style="width: calc(100% - 130px);margin: auto;margin-right: 0;">
                                            <p class="order-detail-title" style="height: 40px;
                                                background-color: #eee;
                                                text-align: center;
                                                line-height: 40px;
                                                font-size: 12px;
                                                color: #444;
                                                font-weight: 400;
                                                cursor: pointer;">
                                                ORDER DETAILS 
                                                {{-- <span class="iconfont">&#xe605;</span> --}}
                                            </p>
                                            <div class="order-details" style="padding: 20px 10px;width: 98%;background-color: #f5f5f5;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->duplicate_attr_values as $attr_value)
                                                    <div class="order-detail" style="display: flex;color: #111;line-height: 30px;">
                                                        <div class="order-detail-name" style="width: 50%;font-size: 14px;">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value" style="width: 50%;font-size: 14px;">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($item['sku']->product->type == \App\Models\Product::PRODUCT_TYPE_REPAIR)
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom" style="width: calc(100% - 130px);margin: auto;margin-right: 0;">
                                            <p class="order-detail-title" style="height: 40px;
                                                background-color: #eee;
                                                text-align: center;
                                                line-height: 40px;
                                                font-size: 12px;
                                                color: #444;
                                                font-weight: 400;
                                                cursor: pointer;">
                                                ORDER DETAILS
                                                {{-- <span class="iconfont">&#xe605;</span> --}}
                                            </p>
                                            <div class="order-details" style="padding: 20px 10px;width: 98%;background-color: #f5f5f5;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->repair_attr_values as $attr_value)
                                                    <div class="order-detail" style="display: flex;color: #111;line-height: 30px;">
                                                        <div class="order-detail-name" style="width: 50%;font-size: 14px;">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value" style="width: 50%;font-size: 14px;">
                                                            <span>{{ $attr_value->value }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- 商品详情 --}}
                                        <div class="cart-item-bottom" style="width: calc(100% - 130px);margin: auto;margin-right: 0;">
                                            <p class="order-detail-title" style="height: 40px;
                                                background-color: #eee;
                                                text-align: center;
                                                line-height: 40px;
                                                font-size: 12px;
                                                color: #444;
                                                font-weight: 400;
                                                cursor: pointer;">
                                                ORDER DETAILS
                                                {{-- <span class="iconfont">&#xe605;</span> --}}
                                            </p>
                                            <div class="order-details" style="padding: 20px 10px;width: 98%;background-color: #f5f5f5;">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($item['sku']->attr_values as $attr_value)
                                                    <div class="order-detail" style="display: flex;color: #111;line-height: 30px;">
                                                        <div class="order-detail-name" style="width: 50%;font-size: 14px;">
                                                            <span>{{ $attr_value->name }}</span>
                                                        </div>
                                                        <div class="order-detail-value" style="width: 50%;font-size: 14px;">
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
                    <div class="address-info" style="padding: 20px;margin-bottom: 10px;background-color: #fff;">
                        <div class="address-info-title" 
                             style="padding: 7px 0;margin-bottom: 10px;display: flex;color: #111;font-weight: bold;border: 0;border-bottom: 1px solid #eee;">
                            {{-- <img src="{{ asset('img/peyment-address.png') }}" alt="Lyricalhair" style="margin-right: 6px;"> --}}
                            <p>User Address</p>
                        </div>
                        {{-- 默认地址，正常显示 --}}
                        <div class="address-info-content pre_payment_header add-new-address-box" style="position: relative;padding: 25px 10px;width: 100%;border: 1px solid #eee;">
                            {{-- 有地址 --}}
                            <div class="address-name" style="color: #444;font-weight: bold;line-height: 22px;margin-bottom: 15px;">
                                <span class="address_name">{{ $order->user_info['name'] }}</span>
                            </div>
                            <div class="address-fullAddress" style="color: #444;line-height: 22px;">
                                <span class="address_location">{{ $order->user_info['address'] }}</span>
                            </div>
                            <div class="address-phone" style="color: #444;line-height: 22px;">
                                <span class="address_phone">{{ $order->user_info['phone'] }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- 优惠券相关 --}}
                    <div class="discount-coupon" style="padding: 24px;background: #fff;">
                        <div class="Currency-options" style="margin-bottom: 15px;padding-bottom: 18px;border-bottom: 1px solid #eee;">
                            <p class="currency-options-title" style="margin-bottom: 11px;color: #111;font-weight: bold;">Currency</p>
                            <div>
                                <p class="currency_selection">
                                    <a href="javascript:void(0);" class="active" code="dollar" country="USD" 
                                    style="display: inline-block;width: 120px;height: 40px;color: #CE1020;text-align: center;line-height: 40px;border: 2px solid #CE1020;">
                                        {{-- <span class="iconfont">&#xe607;</span> --}}
                                        @lang('order.Dollars')
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="available_coupons" style="margin-bottom: 15px;padding-bottom: 18px;border-bottom: 1px solid #eee;">
                            <p class="available_coupons-title" style="margin-bottom: 11px;color: #111;font-weight: bold;">Coupons</p>
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
                <div class="oreders-right" 
                     style="margin: 0 0 0 auto;padding: 30px 20px;width: 23%;background-color: #fff;height: -webkit-fit-content;height: -moz-fit-content;height: fit-content;">
                    {{-- 留言 --}}
                    <div class="order-note" style="margin-bottom: 30px;">
                        <p class="order-note-title" style="margin-bottom: 11px;color: #111;font-weight: bold;">Order note</p>
                        <textarea class="remark" placeholder="Optional, leave a message to the seller" 
                                  style="padding: 15px 7px;width: 100%;height: 120px;background-color: #f5f5f5;resize: none;border: none;outline: none;">{{ $order->remark }}</textarea>
                    </div>
                    {{-- 邮件 --}}
                    <div class="order-email" style="margin-bottom: 30px;">
                        <p class="order-email-title" style="margin-bottom: 11px;color: #111;font-weight: bold;">Order email</p>
                        <div class="email-detail">
                            <p class="email-question" style="margin-bottom: 10px;color: #888;">Do you want to send this order information to email?</p>
                            @if($order->email)
                                <p class="inputBox" style="margin-bottom: 10px;color: #888;">
                                    <label style="display: flex;cursor: pointer;">
                                        <input type="hidden" name="emailChoose" value="1">
                                        <span>Yes, I do</span>
                                    </label>
                                </p>
                                <p class="dis_n email-int">
                                    <input type="email" class="order-email-num" value="{{ $order->email }}" style="border:0;">
                                </p>
                            @else
                                <p class="inputBox" style="margin-bottom: 10px;color: #888;">
                                    <label style="display: flex;cursor: pointer;">
                                        <input type="hidden" name="emailChoose" value="0">
                                        <span>No, Thank you</span>
                                    </label>
                                </p>
                            @endif
                        </div>
                    </div>
                    {{-- 费用 --}}
                    <div class="order-fee">
                        <p class="order-fee-title" 
                           style="margin-bottom: 15px;
                            padding-bottom: 10px;
                            border-bottom: 1px solid #eee;
                            color: #111;
                            font-weight: bold;">Order fee</p>
                        <div class="amount-detail" 
                             style="padding-bottom: 15px;
                                margin-bottom: 19px;
                                border-bottom: 2px solid #444;">
                            <p style="display: -webkit-box;
                            display: -ms-flexbox;
                            display: flex;
                            -webkit-box-pack: justify;
                            -ms-flex-pack: justify;
                            justify-content: space-between;
                            line-height: 24px;">
                                <span>@lang('order.Sum')：</span>
                                <span class="dollar_num amount_of_money">
                                    &#36; <span>{{ $order->total_amount }}</span>
                                </span>
                            </p>
                            <p style="display: -webkit-box;
                            display: -ms-flexbox;
                            display: flex;
                            -webkit-box-pack: justify;
                            -ms-flex-pack: justify;
                            justify-content: space-between;
                            line-height: 24px;">
                                <span>@lang('order.freight')：</span>
                                <span class="dollar_num amount_of_money">
                                    &#36; <span class="total_shipping_fee">{{ $order->total_shipping_fee }}</span>
                                </span>
                            </p>
                        </div>
                        <p class="total-price" 
                            style="margin-bottom: 10px;
                            display: -webkit-box;
                            display: -ms-flexbox;
                            display: flex;
                            -webkit-box-pack: justify;
                            -ms-flex-pack: justify;
                            justify-content: space-between;
                            line-height: 24px">
                            <span style="color: #888;">@lang('order.Amount payable')：</span>
                            <span class="red dollar_num amount_of_money" style="color: #C92324;font-size: 24px;">
                                &#36; <span class="total_price">{{ $order->payment_amount }}</span>
                            </span>
                        </p>
                        <p class="saved-fee" 
                           style="margin-bottom: 24px;
                            text-align: right;
                            color: #888;
                            line-height: 24px;">
                            <span>(Saved fee: &#36;  <span class="discounts_num">{{ $order->saved_fee }}</span> )</span>
                        </p>
                        {{-- <p class="address_info">
                            <span class="address_name address_name_bottom">{{ $order->user_info['name'] }}</span>
                        </p>
                        <p>
                            <span class="address_info address_province address_province_bottom">{{ $order->user_info['address'] }}</span>
                        </p>
                        <p>
                            <span class="address_phone address_phone_bottom">{{ $order->user_info['phone'] }}</span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



