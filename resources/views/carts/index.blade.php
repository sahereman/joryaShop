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
                            <a href="{{ route('root') }}">Continue Shopping</a>
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
                                                    {{--<input name="selectOne" type="hidden" checked="checked" data-sku-id="{{ $cart['product_sku_id'] }}"--}}
                                                           {{--value="{{ $cart['product_sku_id'] }}">--}}
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
                                                        {{--<a class="cur_p" href="{{ route('seo_url', $cart->sku->product->slug) }}">--}}
                                                            {{--<span>{{ App::isLocale('zh-CN') ? $cart->sku->product->name_zh : $cart->sku->product->name_en }}</span>--}}
                                                        {{--</a>--}}
                                                        <a class="cur_p" href="{{ route('seo_url', $cart['product_sku']->product->slug) }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $cart['product_sku']->product->name_zh : $cart['product_sku']->product->name_en }}</span>
                                                        </a>
                                                    </div>
                                                    <div class="cart-item-btns">
                                                        @if(!$cart->favourite)
                                                            <a class="cur_p add_favourites" data-product-id="{{ $cart['product_sku']->product_id }}"
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
                                                           type="text" size="4" value="{{ $cart['number'] }}" data-sku-id="{{ $cart['product_sku_id'] }}" title="QTY">
                                                    <button class="left small-button">+</button>
                                                </div>
                                            </div>
                                            <div class="cart-header-item cart-item-amount">
                                                <div class="amount-price">
                                                    <span>{{ get_global_symbol() }}</span>
                                                    <span>{{ bcmul(get_current_price($cart['product_sku']->price), $cart['number'], 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS</p>
                                            <div class="order-details">
                                                {{-- 循环的时候分奇偶数 --}}
                                                @if($cart['product_sku']->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                                    @foreach($cart['product_sku']->custom_attr_values as $key => $custom_attr_value)
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
                                                    {{--<input name="selectOne" type="hidden" checked="checked" data-sku-id="{{ $cart['product_sku_id'] }}"--}}
                                                    {{--value="{{ $cart['product_sku_id'] }}">--}}
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
                                                        {{--<a class="cur_p" href="{{ route('seo_url', $cart->sku->product->slug) }}">--}}
                                                        {{--<span>{{ App::isLocale('zh-CN') ? $cart->sku->product->name_zh : $cart->sku->product->name_en }}</span>--}}
                                                        {{--</a>--}}
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
                                                    <span>{{ bcmul(get_current_price($cart->sku->price), $cart->number, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS</p>
                                            <div class="order-details">
                                                {{-- 循环的时候分奇偶数 --}}
                                                @if($cart->sku->product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                                    @foreach($cart->sku->custom_attr_values as $key => $custom_attr_value)
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
                                            <td class="price-num"><strong class="total-price">{{ get_global_symbol() . ' ' . get_current_price($total_amount) }}</strong></td>
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

                <!--当购物车内容为空时显示-->
                {{--@if(!count($carts) > 0)--}}
                {{--@else--}}
                    {{--<div class="cart-footer">--}}
                        {{--<div class="clear left left-control">--}}
                            {{--<div class="left w100">--}}
                                {{--<input id="selectAll-2" class="selectAll" type="checkbox">--}}
                                {{--<label for="selectAll-2">@lang('product.shopping_cart.all_selected')</label>--}}
                            {{--</div>--}}
                            {{--<a id="clearSelected" href="javascript:void(0);" data-url="{{ route('carts.flush') }}">--}}
                                {{--@lang('product.shopping_cart.empty_cart')--}}
                            {{--</a>--}}
                            {{--<!--随时解注-->--}}
                            {{--<!--<a id="clearInvalid" href="javascript:void(0);">清空失效商品</a>-->--}}
                        {{--</div>--}}
                        {{--<div class="right">--}}
                            {{--<!--<span>总共选中了<span id="totalCount">0</span>件商品</span>-->--}}
                            {{--<span>--}}
                                {{--@lang('order.Sum'):--}}
                                {{--<span id="totalPrice">--}}
                                    {{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }} 0.00--}}
                                    {{--{{ get_global_symbol() }} 0.00--}}
                                {{--</span>--}}
                            {{--</span>--}}
                            {{--@guest--}}
                                {{--<button class="big-button for_show_login">--}}
                                    {{--@lang('product.shopping_cart.Settlement')--}}
                                {{--</button>--}}
                            {{--@else--}}
                                {{--<button class="big-button" data-url="{{ route('orders.pre_payment') }}">--}}
                                    {{--@lang('product.shopping_cart.Settlement')--}}
                                {{--</button>--}}
                            {{--@endguest--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            var query_data = "";
            var sku_ids = [];
            var COUNTRY = $("#dLabel").find("span").html();
            // $(document).ready(function(){
            /*if (getUrlVars() != undefined) {
                query_data = getUrlVars();
                query_data = query_data.substring(0, query_data.length - 1);
                sku_ids = query_data.split(",");
                $.each(sku_ids, function (i, sku_id) {
                    $(".cart-items").find("input[data-sku-id='" + sku_id + "']").attr("checked", true);
                });
                calcTotal();
            }*/
            // });
            // calcTotal();
            // 全选
            {{--$('.selectAll').on('change', function (evt) {--}}
                {{--if ($(this).prop('checked')) {--}}
                    {{--$('.single-item input[type="checkbox"]').prop('checked', true);--}}
                    {{--$('.selectAll').prop('checked', true);--}}
                    {{--$(".big-button").addClass('active');--}}
                    {{--calcTotal();--}}
                {{--} else {--}}
                    {{--$('.single-item input[type="checkbox"]').prop('checked', false);--}}
                    {{--$('.selectAll').prop('checked', false);--}}
                    {{--$('#totalCount').text('0');--}}
                    {{--// $('#totalPrice').html("--}}{{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }}--}}{{--" + '0.00');--}}
                    {{--$('#totalPrice').html(global_symbol + '0.00');--}}
                    {{--$(".big-button").removeClass('active');--}}
                {{--}--}}
            {{--});--}}
            // 为单个商品项的复选框绑定改变事件
            // $('input[name="selectOne"]').on('change', function () {
            //     calcTotal();
            //     if (!$(this).prop('checked')) {
            //         $('.selectAll').prop('checked', false);
            //     }
            // });
            // 为删除选中商品超链接绑定事件回调(清空购物车)
            {{--$('#clearSelected').on('click', function () {--}}
                {{--var clickDom = $(this);--}}
                {{--var index = layer.open({--}}
                    {{--title: "@lang('app.Prompt')",--}}
                    {{--content: "@lang('product.shopping_cart.sure_to_empty_cart')",--}}
                    {{--btn: ["@lang('app.determine')", "@lang('app.cancel')"],--}}
                    {{--yes: function () {--}}
                        {{--var data = {--}}
                            {{--_method: "DELETE",--}}
                            {{--_token: "{{ csrf_token() }}",--}}
                        {{--};--}}
                        {{--var url = clickDom.attr('data-url');--}}
                        {{--$.ajax({--}}
                            {{--type: "post",--}}
                            {{--url: url,--}}
                            {{--data: data,--}}
                            {{--success: function (data) {--}}
                                {{--$('.selectAll').prop('checked', false);--}}
                                {{--location.reload();--}}
                                {{--calcTotal();--}}
                                {{--layer.close(index);--}}
                            {{--},--}}
                            {{--error: function (err) {--}}
                                {{--console.log(err);--}}
                            {{--}--}}
                        {{--});--}}
                    {{--},--}}
                    {{--btn2: function () {--}}
                        {{--layer.close(index);--}}
                    {{--},--}}
                {{--});--}}
            {{--});--}}


            // ********************* 待修改，更改数量，同时更改价格（包含折扣等）  *********************

            // 为减少和添加商品数量的按钮绑定事件回调
            $('.cart-items').on('click',".small-button", function (evt) {
                $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
                if ($(this).text() == '-') {
                    var count = parseInt($(this).next().val());
                    if (count > 1) {
                        count -= 1;
                        $(this).next().val(count);
                        update_pro_num($(this).next());
                    } else {
                        layer.msg("@lang('order.The number of goods is at least 1')");
                    }
                } else {
                    var count = parseInt($(this).prev().val());
                    if (count <= 10000) {
                        count += 1;
                        $(this).prev().val(count);
                        update_pro_num($(this).prev());
                    } else {
                        layer.msg("@lang('order.Cannot add more quantities')");
                    }
                }
                var price = parseFloat($(this).parent().prev().find('span.price').text());
                // $(this).parent().next().html("{{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }}--}}" + (price * count).toFixed(2));
                $(this).parent().next().html(global_symbol + js_number_format(Math.imul(float_multiply_by_100(price), count) / 100));
                calcTotal();
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
                                calcTotal();
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
            //删除商品函数
            function del_forfavourty(url){
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                };
                var url = url;
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        location.reload();
                        // calcTotal();
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            }

            // 加入收藏夹
            $('.cart-items').on('click', ".add_favourites", function () {
                var clickDom = $(this);
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
                        del_forfavourty(url_del);
                        // calcTotal();
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "Add wishlist successfully",
                            btn: "@lang('app.determine')",
                        });
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                            layer.msg($.parseJSON(e.responseText).errors.product_id[0]);
                        }
                    },
                });
            });

            //  **************************************** 待修改，商品数量变化的价格与之前不同 ***************************************
            // 为商品数量文本框绑定改变事件回调
            $('.cart-items input[type="text"]').on('change', function () {
                $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
                var count = parseInt($(this).val());
                if (count != $(this).val() || count < 1 || count > 200) {
                    layer.msg("@lang('product.invalid_commodity_quantity')");
                    count = 1;
                    $(this).val(count);
                }
                update_pro_num($(this));
                var price = parseFloat($(this).parent().prev().find('span.price').text());
                // $(this).parent().next().html("{{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }}--}}" + (price * count).toFixed(2));
                $(this).parent().next().html(global_symbol + js_number_format(Math.imul(float_multiply_by_100(price), count) / 100));
                calcTotal();
            });

            // 计算总计
            function calcTotal() {
                var checkBoxes = $('input[name="selectOne"]');
                var priceSpans = $('.single-item .price');
                var countInputs = $('.single-item .count');
                var totalCount = 0;
                var totalPrice = 0;
                for (var i = 0; i < priceSpans.length; i += 1) {
                    // 复选框被勾中的购物车项才进行计算
                    if ($(checkBoxes[i]).prop('checked')) {
                        // 强调: jQuery对象使用下标运算或get方法会还原成原生的JavaScript对象
                        var price = parseFloat($(priceSpans[i]).text());
                        var count = parseInt($(countInputs[i]).val());
                        totalCount += count;
                        // totalPrice += price * count;
                        totalPrice += parseFloat(js_number_format(Math.imul(float_multiply_by_100(price), count) / 100));
                    }
                }
                if (totalPrice > 0) {
                    $(".big-button").addClass('active');
                } else {
                    $(".big-button").removeClass('active');
                }
                $('#totalCount').text(totalCount);
                // $('#totalPrice').html("{{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }}--}}" + totalPrice.toFixed(2));
                $('#totalPrice').html(global_symbol + js_number_format(totalPrice));
            }

            //更新购物车记录（增减数量）
            function update_pro_num(dom) {
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
                        // calcTotal();
                    },
                    error: function (err) {
                        console.log(err);
                        var count = dom.val();
                        count = --count;
                        dom.val(count);
                        var price = parseFloat(dom.parent().prev().find('span.price').text());
                        // dom.parent().next().html("{{--{{ App::isLocale('en') ? '&#36;' : '&#165;' }}--}}" + (price * count).toFixed(2));
                        dom.parent().next().html(global_symbol + js_number_format(Math.imul(float_multiply_by_100(price), count) / 100));
                        // calcTotal();
                        var obj = err.responseJSON.errors;
                        layer.msg(Object.values(obj)[0][0]);
                    },
                });
            }

            // 点击结算
            $("#Checkout").on("click", function () {
                var clickDom = $(this);
                if (clickDom.hasClass('for_show_login') == true) {
                    $(".login").click();
                } else {
                    if (clickDom.hasClass("active") != true) {
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "@lang('product.choose_settlement')",
                            btn: "@lang('app.determine')",
                        });
                    } else {
                        var cart_ids = "";
                        var cartIds = $(".cart-items").find("input[name='selectOne']:checked");
                        if (cartIds.length > 0) {
                            $.each(cartIds, function (i, n) {
                                cart_ids += $(n).val() + ","
                            });
                            cart_ids = cart_ids.substring(0, cart_ids.length - 1);
                            var url = clickDom.attr('data-url');
                            window.location.href = url + "?cart_ids=" + cart_ids + "&sendWay=2";
                        } else {
                            layer.open({
                                title: "@lang('app.Prompt')",
                                content: "@lang('product.choose_settlement')",
                                btn: "@lang('app.determine')",
                            });
                        }
                    }
                }
            });
            // 再次购买的特殊处理，如果从再次购买进入购物车则url中存在参数 sku_ids 用来判断哪些商品是通过再次购买添加至购物车中
            // 同时对这些对应的商品进行选择进行状态选中
            function getUrlVars() {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars["sku_ids"];
            }
        });
    </script>
@endsection
