@extends('layouts.app')
@section('title', '购物车')
@section('content')
    <div class="shopping_cart">
        <div class="m-wrapper">
            <div class="carts">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('product.shopping_cart.Shop_cart')</a>
                </p>
                <!--当购物车内容为空时显示-->
                @if($carts->isEmpty())
                    <div class="empty_shopping_cart">
                        <div></div>
                        <p>@lang('product.shopping_cart.shopping_cart_still_empty')</p>
                        <a href="{{ route('root') }}">@lang('product.shopping_cart.Go_shopping')</a>
                    </div>
                    <!--购物车有商品时显示下方内容包括cart-header，cart-items，cart-footer-->
                @else
                    <div class="cart-header">
                        <div class="left w130">
                            <input id="selectAll" class="selectAll" type="checkbox">
                            <label for="selectAll">@lang('product.shopping_cart.all_selected')</label>
                        </div>
                        <div class="left w250">@lang('product.shopping_cart.Product_information')</div>
                        <div class="left w120 center">@lang('product.shopping_cart.Specifications')</div>
                        <div class="left w100 center">@lang('product.shopping_cart.Unit_price')</div>
                        <div class="left w150 center">@lang('product.shopping_cart.Quantity')</div>
                        <div class="left w100 center">@lang('product.shopping_cart.Subtotal')</div>
                        <div class="left w120 center">@lang('product.shopping_cart.Operating')</div>
                    </div>
                    <div class="cart-items">
                        @foreach($carts as $cart)
                            <div class="clear single-item">
                                <div class="left w20">
                                    <input name="selectOne" type="checkbox" code="{{ $cart->sku->id }}"
                                           value="{{ $cart->id }}">
                                </div>
                                <div class="left w110 shop-img">
                                    <a class="cur_p" href="{{ route('products.show', $cart->sku->product_id) }}">
                                        <img class="lazy" data-src="{{ $cart->sku->product->thumb_url }}">
                                    </a>
                                </div>
                                <div class="left w250 pro-info">
                                    <a class="cur_p" href="{{ route('products.show', $cart->sku->product_id) }}">
                                        <span>{{ App::isLocale('en') ? $cart->sku->product->name_en : $cart->sku->product->name_zh }}</span>
                                    </a>
                                </div>
                                <div class="left w120 center">
                                    <span>{{ App::isLocale('en') ? $cart->sku->name_en : $cart->sku->name_zh }}</span>
                                </div>
                                <div class="left w100 center">
                                    <span>{{ App::isLocale('en') ? '&#36;' : '&#165;' }}</span>
                                    <span class="price">{{ App::isLocale('en') ? $cart->sku->price_in_usd : $cart->sku->price }}</span>
                                </div>
                                <div class="left w150 center counter">
                                    <button class="left small-button">-</button>
                                    <input class="left center count" data-url="{{ route('carts.update', $cart->id) }}"
                                           type="text" size="4" value="{{ $cart->number }}">
                                    <button class="left small-button">+</button>
                                </div>
                                <div class="left w100 s_total center">
                                    <span>{{ App::isLocale('en') ? '&#36;' : '&#165;' }}</span>
                                    <span>{{ App::isLocale('en') ? bcmul($cart->sku->price_in_usd, $cart->number, 2) : bcmul($cart->sku->price, $cart->number, 2) }}</span>
                                </div>
                                <div class="left w120 center">
                                    <p>
                                        @if($cart->favourite)
                                            <a class="cur_p add_favourites" code="{{ $cart->sku->product_id }}"
                                               data-url="{{ route('user_favourites.destroy', ['favourite' => $cart->favourite->id]) }}">
                                                @lang('product.shopping_cart.Remove_from_favourites')
                                            </a>
                                        @else
                                            <a class="cur_p add_favourites" code="{{ $cart->sku->product_id }}"
                                               data-url="{{ route('user_favourites.store') }}">
                                                @lang('product.shopping_cart.Move_to_favourites')
                                            </a>
                                        @endif
                                        <a class="cur_p single_delete"
                                           data-url="{{ route('carts.destroy', $cart->id) }}">
                                            @lang('basic.delete')
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="cart-footer">
                        <div class="clear left left-control">
                            <div class="left w100">
                                <input id="selectAll-2" class="selectAll" type="checkbox">
                                <label for="selectAll-2">@lang('product.shopping_cart.all_selected')</label>
                            </div>
                            <a id="clearSelected" href="javascript:void(0);" data-url="{{ route('carts.flush') }}">
                                @lang('product.shopping_cart.empty_cart')
                            </a>
                            <!--随时解注-->
                            <!--<a id="clearInvalid" href="javascript:void(0);">清空失效商品</a>-->
                        </div>
                        <div class="right">
                            <!--<span>总共选中了<span id="totalCount">0</span>件商品</span>-->
                            <span>
                                @lang('order.Sum'):
                                <span id="totalPrice">
                                    {{ App::isLocale('en') ? '&#36;' : '&#165;' }} 0.00
                                </span>
                            </span>
                            @guest
                            <button class="big-button for_show_login">
                                @lang('product.shopping_cart.Settlement')
                            </button>
                            @else
                                <button class="big-button" data-url="{{ route('orders.pre_payment') }}">
                                    @lang('product.shopping_cart.Settlement')
                                </button>
                                @endguest
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
            var action = "";
            var sku_id = [];
            var COUNTRY = $("#dLabel").find("span").html();
            // $(document).ready(function(){
            if (getUrlVars() != undefined) {
                action = getUrlVars();
                action = action.substring(0, action.length - 1);
                sku_id = action.split(",");
                $.each(sku_id, function (i, n) {
                    $(".cart-items").find("input[code='" + n + "']").attr("checked", true);
                });
                calcTotal();
            }
            // });
            //全选
            $('.selectAll').on('change', function (evt) {
                if ($(this).prop('checked')) {
                    $('.single-item input[type="checkbox"]').prop('checked', true);
                    $('.selectAll').prop('checked', true);
                    $(".big-button").addClass('active');
                    calcTotal();
                } else {
                    $('.single-item input[type="checkbox"]').prop('checked', false);
                    $('.selectAll').prop('checked', false);
                    $('#totalCount').text('0');
                    $('#totalPrice').html("{{ App::isLocale('en') ? '&#36;' : '&#165;' }}" + '0.00');
                    $(".big-button").removeClass('active');
                }
            });
            // 为单个商品项的复选框绑定改变事件
            $('input[name="selectOne"]').on('change', function () {
                calcTotal();
                if (!$(this).prop('checked')) {
                    $('.selectAll').prop('checked', false);
                }
            });
            // 为删除选中商品超链接绑定事件回调(清空购物车)
            $('#clearSelected').on('click', function () {
                var clickDom = $(this);

                /*layer.alert((COUNTRY == "中文") ? '确定要清空购物车吗' : 'Are you sure you want to empty the shopping cart?', function (index) {
                 $('.single-item').each(function () {
                 if ($(this).find('input[name="selectOne"]').prop('checked')) {
                 $(this).remove();
                 }
                 });
                 var data = {
                 _method: "DELETE",
                 _token: "{{ csrf_token() }}",
                 };
                 var url = clickDom.attr('data-url');
                 $.ajax({
                 type: "post",
                 url: url,
                 data: data,
                 success: function (data) {
                 $('.selectAll').prop('checked', false);
                 location.reload();
                 calcTotal();
                 layer.close(index);
                 },
                 error: function (err) {
                 console.log(err);
                 },
                 });
                 });*/

                var index = layer.open({
                    title: "@lang('app.Prompt')",
                    content: "@lang('product.shopping_cart.sure_to_empty_cart')",
                    btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                    yes: function () {
                        var data = {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}",
                        };
                        var url = clickDom.attr('data-url');
                        $.ajax({
                            type: "post",
                            url: url,
                            data: data,
                            success: function (data) {
                                $('.selectAll').prop('checked', false);
                                location.reload();
                                calcTotal();
                                layer.close(index);
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    },
                    btn2: function () {
                        layer.close(index);
                    }
                });
            });
            // 为减少和添加商品数量的按钮绑定事件回调
            $('.single-item button').on('click', function (evt) {
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
                    if (count < 200) {
                        count += 1;
                        $(this).prev().val(count);
                        update_pro_num($(this).prev());
                    } else {
                        layer.msg("@lang('order.Cannot add more quantities')");
                    }
                }
                var price = parseFloat($(this).parent().prev().find('span.price').text());
                $(this).parent().next().html("{{ App::isLocale('en') ? '&#36;' : '&#165;' }}" + (price * count).toFixed(2));
                calcTotal();
            });
            // 为单个商品项删除超链接绑定事件回调
            $('.single-item').on('click', ".single_delete", function () {
                var clickDom = $(this);

                /*layer.alert((COUNTRY == "中文") ? '确定要删除该商品吗' : 'Are you sure you want to delete the product?', function (index) {
                    var data = {
                        _method: "DELETE",
                        _token: "{{ csrf_token() }}",
                    };
                    var url = clickDom.attr('data-url');
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            calcTotal();
                            layer.close(index);
                        },
                        error: function (err) {
                            console.log(err);
                        },
                    });
                });*/

                var index = layer.open({
                    title: "@lang('app.Prompt')",
                    content: "@lang('product.shopping_cart.sure_to_delete_product')",
                    btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                    yes: function () {
                        var data = {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}",
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
                            }
                        });
                    },
                    btn2: function () {
                        layer.close(index);
                    }
                });
            });
            //加入收藏夹
            $('.single-item').on('click', ".add_favourites", function () {
                var clickDom = $(this);
                var data = {
                    _token: "{{ csrf_token() }}",
                    product_id: clickDom.attr("code")
                };
                var url = clickDom.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        calcTotal();
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "@lang('product.shopping_cart.Add_favourites_successfully')",
                            btn: "@lang('app.determine')"
                        });
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                            layer.msg($.parseJSON(e.responseText).errors.product_id[0]);
                        }
                    }
                });
            });
            // 为商品数量文本框绑定改变事件回调
            $('.single-item input[type="text"]').on('change', function () {
                $(this).parent().parent().find('input[name="selectOne"]').prop('checked', true);
                var count = parseInt($(this).val());
                if (count != $(this).val() || count < 1 || count > 200) {
                    layer.msg("@lang('product.invalid_commodity_quantity')");
                    count = 1;
                    $(this).val(count);
                }
                update_pro_num($(this));
                var price = parseFloat($(this).parent().prev().find('span.price').text());
                $(this).parent().next().html("{{ App::isLocale('en') ? '&#36;' : '&#165;' }}" + (price * count).toFixed(2));
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
                        totalPrice += price * count;
                    }
                }
                if (totalPrice > 0) {
                    $(".big-button").addClass('active');
                } else {
                    $(".big-button").removeClass('active');
                }
                $('#totalCount').text(totalCount);
                $('#totalPrice').html("{{ App::isLocale('en') ? '&#36;' : '&#165;' }}" + totalPrice.toFixed(2));
            }

            //更新购物车记录（增减数量）
            function update_pro_num(dom) {
                var url = dom.attr("data-url");
                var data = {
                    _method: "PATCH",
                    _token: "{{ csrf_token() }}",
                    number: dom.val(),
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        calcTotal();
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            }

            //点击结算
            $(".big-button").on("click", function () {
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
                                btn: "@lang('app.determine')"
                            });
                        }
                    }
                }
            });
            //再次购买的特殊处理，如果从再次购买进入购物车则url中存在参数sku_id_lists用来判断哪些商品是通过再次购买添加至购物车中
            //同时对这些对应的商品进行选择进行状态选中
            function getUrlVars() {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars["sku_id_lists"];
            }
        });
    </script>
@endsection
