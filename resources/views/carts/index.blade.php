@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '购物车' : 'Cart') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="shopping_cart">
        <div class="container m-wrapper">
            <div class="carts">
                @if(!count($carts) > 0)
                    <div class="empty_shopping_cart">
                        <div class="page-title">
                            <h1>Shopping Cart is Empty</h1>
                        </div>
                        <div class="cart-empty">
                            <p>You have no items in your shopping cart.</p>
                            <p><a href="{{ route('root') }}">Click Here</a>to continue shopping.</p>
                        </div>
                    </div>
                @else
                    <div class="cart">
                        <div class="page-title title-buttons">
                            <h1>SHOPPING CART</h1>
                            <a href="javascript:history.back(-1)">Continue Shopping</a>
                        </div>
                        <div class="cart-header">
                            <div class="cart-header-item cart-item-option"></div>
                            <div class="cart-header-item cart-item-item">
                                <span>ITEM</span>
                            </div>
                            <div class="cart-header-item cart-item-qty">
                                <span>QTY</span>
                            </div>
                            <div class="cart-header-item cart-item-amount">
                                <span>Amount</span>
                            </div>
                        </div>
                        <div class="cart-items">
                            @guest
                                @foreach($carts as $cart)
                                    <div class="cart-item">
                                        <div class="cart-item-top">
                                            <div class="cart-header-item cart-item-option">
                                                <a class="cur_p single_delete" href="javascript:void (0)" data-url="{{ route('carts.destroy') }}" data-sku-id="{{ $cart['product_sku_id'] }}">
                                                    <span class="iconfont">&#xe7b6;</span>
                                                </a>
                                            </div>
                                            <div class="cart-header-item cart-item-item">
                                                <div class="cart-item-item-img">
                                                    <a class="cur_p" href="{{ route('seo_url', $cart['product_sku']->product->slug) }}">
                                                        <img class="lazy" data-src="{{ $cart['product_sku']->product->thumb_url }}">
                                                    </a>
                                                </div>
                                                <div class="cart-item-item-content">
                                                    <div class="cart-item-name">
                                                        <a class="cur_p" href="{{ route('seo_url', $cart['product_sku']->product->slug) }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $cart['product_sku']->product->name_zh : $cart['product_sku']->product->name_en }}</span>
                                                        </a>
                                                    </div>
                                                    <div class="cart-item-btns">
                                                        <a class="cur_p add_favourites not-login">
                                                            Move To Wishlist
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart-header-item cart-item-qty">
                                                <div class="counter">
                                                    <button class="left small-button">-</button>
                                                    <input class="left center count" data-url="{{ route('carts.update') }}"
                                                           type="text" size="4" value="{{ $cart['number'] }}" data-sku-id="{{ $cart['product_sku_id'] }}" title="QTY">
                                                    <button class="left small-button">+</button>
                                                </div>
                                            </div>
                                            <div class="cart-header-item cart-item-amount">
                                                <div class="amount-price">
                                                    <span>{{ get_global_symbol() }}</span>
                                                    <span class="single-price">{{ bcmul(get_current_price($cart['product_sku']->price), $cart['number'], 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS <span class="iconfont">&#xe605;</span></p>
                                            <div class="order-details">
                                                {{-- 循环的时候分奇偶数 --}}
                                                @if($cart['product_sku']->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                                    @foreach($attr_values[$cart['product_sku_id']] as $key => $custom_attr_value)
                                                        @if(($key + 1) % 2 == 1)
                                                            <div class="order-detail odd">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $custom_attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $custom_attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="order-detail even">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $custom_attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $custom_attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach($cart['product_sku']->attr_values as $key => $attr_value)
                                                        @if(($key + 1) % 2 == 1)
                                                            <div class="order-detail odd">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="order-detail even">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($carts as $cart)
                                    <div class="cart-item">
                                        <div class="cart-item-top">
                                            <div class="cart-header-item cart-item-option">
                                                <a class="cur_p single_delete" href="javascript:void (0)" data-url="{{ route('carts.destroy') }}" data-sku-id="{{ $cart->product_sku_id }}">
                                                    <span class="iconfont">&#xe7b6;</span>
                                                </a>
                                            </div>
                                            <div class="cart-header-item cart-item-item">
                                                <div class="cart-item-item-img">
                                                    <a class="cur_p" href="{{ route('seo_url', $cart->sku->product->slug) }}">
                                                        <img class="lazy" data-src="{{ $cart->sku->product->thumb_url }}">
                                                    </a>
                                                </div>
                                                <div class="cart-item-item-content">
                                                    <div class="cart-item-name">
                                                        <a class="cur_p" href="{{ route('seo_url', $cart->sku->product->slug) }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $cart->sku->product->name_zh : $cart->sku->product->name_en }}</span>
                                                        </a>
                                                    </div>
                                                    <div class="cart-item-btns">
                                                        @if(!$cart->favourite)
                                                            <a class="cur_p add_favourites" data-product-id="{{ $cart->sku->product_id }}"
                                                               data-url="{{ route('user_favourites.store') }}">
                                                                Move To Wishlist
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart-header-item cart-item-qty">
                                                <div class="counter">
                                                    <button class="left small-button">-</button>
                                                    <input class="left center count" data-url="{{ route('carts.update') }}"
                                                           type="text" size="4" value="{{ $cart->number }}" data-sku-id="{{ $cart->product_sku_id }}" title="QTY">
                                                    <button class="left small-button">+</button>
                                                </div>
                                            </div>
                                            <div class="cart-header-item cart-item-amount">
                                                <div class="amount-price">
                                                    <span>{{ get_global_symbol() }}</span>
                                                    <span  class="single-price">{{ bcmul(get_current_price($cart->sku->price), $cart->number, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS <span class="iconfont">&#xe605;</span></p>
                                            <div class="order-details">
                                                {{-- 循环的时候分奇偶数 --}}
                                                @if($cart->sku->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                                    @foreach($attr_values[$cart->sku_id] as $key => $custom_attr_value)
                                                        @if(($key + 1) % 2 == 1)
                                                            <div class="order-detail odd">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $custom_attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $custom_attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="order-detail even">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $custom_attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $custom_attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach($cart->sku->attr_values as $key => $attr_value)
                                                        @if(($key + 1) % 2 == 1)
                                                            <div class="order-detail odd">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="order-detail even">
                                                                <div class="order-detail-name">
                                                                    <span>{{ $attr_value->name }}</span>
                                                                </div>
                                                                <div class="order-detail-value">
                                                                    <span>{{ $attr_value->value }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                {{--@if($cart->sku->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)--}}
                                                    {{--<span>{{ $cart->sku->custom_attr_value_string }}</span>--}}
                                                {{--@else--}}
                                                    {{--<span>{{ $cart->sku->attr_value_string }}</span>--}}
                                                {{--@endif--}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endguest
                        </div>
                        <div class="cart-foot">
                            {{-- 购物车积分 --}}
                            {{--<div class="cart-foot-points">
                                <div class="reward-points">
                                    <h2>Reward points</h2>
                                    --}}{{--积分使用说明--}}{{--
                                    <p>This shopping cart is worth <span>1207</span> loyalty point(s).</p>
                                    <p>You are currently using <span>120</span> point(s) of your <span>150</span> loyalty point(s) available.</p>
                                    <div class="buttons-set">
                                        <button type="button">Remove Points</button>
                                    </div>
                                </div>
                                --}}{{--优惠码--}}{{--
                                <div class="coupon-code">
                                    <input type="text" id="coupon_code" name="coupon_code" value="" placeholder="Enter Coupon Code">
                                    <div class="buttons-set">
                                        <button type="button">Apply Coupon</button>
                                    </div>
                                </div>
                            </div>--}}
                            {{-- 购物车支付 --}}
                            <div class="cart-foot-pay totals">
                                <div class="totals-inner">
                                    <table>
                                        <tbody>
                                        {{-- 原总价 --}}
                                        {{--<tr>
                                            <td class="price-name">Subtotal</td>
                                            <td class="price-num"><span class="original-price">US$627.00</span></td>
                                        </tr>
                                        --}}{{-- 折扣价格 --}}{{--
                                        <tr>
                                            <td class="price-name">Discount (Free Shipping for Hair Systems, $20 OFF the 1st Order, 120 points used)</td>
                                            <td class="price-num"><span class="discount-price">-US$23.00</span></td>
                                        </tr>
                                        --}}{{-- 折后总价 --}}{{--
                                        <tr>
                                            <td class="price-name"><strong>Grand Total</strong></td>
                                            <td class="price-num"><strong class="total-price">US$604.00</strong></td>
                                        </tr>--}}
                                        <tr>
                                            <td class="price-name"><strong>Grand Total</strong></td>
                                            <td class="price-num">
                                                <strong class="total-price">
                                                    <span>{{ get_global_symbol() }}</span>
                                                    <span id="totalPriceNum">{{ get_current_price($total_amount) }}</span></strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="buttons-set">
                                        <button type="button" id="Checkout">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            // 点击展开和收起参数详情
            $('.cart-items').on("click",".order-detail-title",function () {
                var clickDom = $(this);
                if(clickDom.hasClass("active")){
                    clickDom.removeClass("active");
                    clickDom.find("span").removeClass("active");
                    clickDom.parent(".cart-item-bottom").find(".order-details").slideUp();
                }else {
                    clickDom.addClass("active");
                    clickDom.find("span").addClass("active");
                    clickDom.parent(".cart-item-bottom").find(".order-details").slideDown();
                }
            });
            // 为减少和添加商品数量的按钮绑定事件回调
            $('.cart-items').on('click',".small-button", function (evt) {
                var single_price = $(this).parents(".cart-item-top").find(".single-price");
                if ($(this).text() == '-') {
                    var count = parseInt($(this).next().val());
                    if (count > 1) {
                        count -= 1;
                        $(this).next().val(count);
                        update_pro_num($(this).next(),single_price);
                    } else {
                        layer.msg("@lang('order.The number of goods is at least 1')");
                    }
                } else {
                    var count = parseInt($(this).prev().val());
                    if (count <= 10000) {
                        count += 1;
                        $(this).prev().val(count);
                        update_pro_num($(this).prev(),single_price);
                    } else {
                        layer.msg("@lang('order.Cannot add more quantities')");
                    }
                }
                var price = parseFloat($(this).parent().prev().find('span.price').text());
            });

            // 为单个商品项删除超链接绑定事件回调
            $('.cart-items').on('click', ".single_delete", function () {
                var clickDom = $(this);
                var index = layer.open({
                    title: "@lang('app.Prompt')",
                    content: "@lang('product.shopping_cart.sure_to_delete_product')",
                    btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                    yes: function () {
                        var data = {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}",
                            sku_id: clickDom.attr('data-sku-id')
                        };
                        var url = clickDom.attr('data-url');
                        $.ajax({
                            type: "post",
                            url: url,
                            data: data,
                            success: function (data) {
                                location.reload();
                                // calcTotal();
                                layer.close(index);
                            },
                            error: function (err) {
                                console.log(err);
                            },
                        });
                    },
                    btn2: function () {
                        layer.close(index);
                    }
                });
            });
            // 加入收藏夹
            $('.cart-items').on('click', ".add_favourites", function () {
                var clickDom = $(this);
                if(clickDom.hasClass("not-login")) {
                    layer.open({
                        title: "@lang('app.Prompt')",
                        content: "Add wishlist successfully",
                        btn: "@lang('app.determine')",
                    });
                    return
                }
                var url_del = clickDom.parents("p").find(".single_delete").attr("data-url");
                var data = {
                    _token: "{{ csrf_token() }}",
                    product_id: clickDom.attr("data-product-id")
                };
                var url = clickDom.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "Add wishlist successfully",
                            btn: "@lang('app.determine')",
                        });
                        clickDom.parents(".cart-item-top").find(".single_delete").trigger("click");
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                            layer.msg($.parseJSON(e.responseText).errors.product_id[0]);
                        }
                    },
                });
            });

            // 为商品数量文本框绑定改变事件回调
            $('.cart-items input[type="text"]').on('change', function () {
                var single_price = $(this).parents(".cart-item-top").find(".single-price");
                var count = parseInt($(this).val());
                if (count != $(this).val() || count < 1 || count > 200) {
                    layer.msg("@lang('product.invalid_commodity_quantity')");
                    count = 1;
                    $(this).val(count);
                }
                update_pro_num($(this),single_price);
                var price = parseFloat($(this).parent().prev().find('span.price').text());
            });
            //更新购物车记录（增减数量）
            function update_pro_num(dom,univalence) {
                var url = dom.attr("data-url");
                var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                    sku_id: dom.attr('data-sku-id'),
                    number: dom.val(),
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        $(univalence).text(data.data.amount);
                        $("#totalPriceNum").text(data.data.total_amount);
                    },
                    error: function (err) {
                        console.log(err);
                        var count = dom.val();
                        count = --count;
                        dom.val(count);
                        var price = parseFloat(dom.parent().prev().find('span.price').text());
                        dom.parent().next().html(global_symbol + js_number_format(Math.imul(float_multiply_by_100(price), count) / 100));
                        var obj = err.responseJSON.errors;
                        layer.msg(Object.values(obj)[0][0]);
                    },
                });
            }
            // 点击结算
            $("#Checkout").on("click", function () {
                var allSku = $(".cart-items").find(".count");
                var allSkuStr = "";
                var subUrl = "{{ route('orders.pre_payment') }}";
                $.each(allSku,function (allSku_index,allSku_n) {
                    allSkuStr+=$(allSku_n).attr("data-sku-id") + ","
                });
                allSkuStr = allSkuStr.substring(0,allSkuStr.length-1);
                window.location.href = subUrl+"?sku_ids="+allSkuStr+"&sendWay=2";
            });
        });
    </script>
@endsection
