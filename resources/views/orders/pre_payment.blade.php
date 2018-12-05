@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Confirm the Order' : '确认订单')
@section('content')
    <div class="pre_payment">
        <div class="m-wrapper">
            <div class="pre_payment_content">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('order.Confirm Order')</a>
                </p>
                <div class="pre_payment_header">
                    <div class="address_info clear">
                        <ul class="left">
                            <li class="clear">
                                <img src="{{ asset('img/sure_ad_local.png') }}">
                                <span>@lang('order.default address')</span>
                            </li>
                            @if($address)
                                <li>
                                    <span>@lang('order.Contact')：</span>
                                    <span class="address_name">{{ $address->name }}</span>
                                </li>
                                <li>
                                    <span>@lang('order.Contact information')：</span>
                                    <span class="address_phone">{{ $address->phone }}</span>
                                </li>
                                <li>
                                    <span>@lang('order.contact address')：</span>
                                    <span class="address_location">{{ $address->address }}</span>
                                </li>
                            @else
                                <li>
                                    <span>@lang('order.Contact')：</span>
                                    <span class="address_name"></span>
                                </li>
                                <li>
                                    <span>@lang('order.Contact information')：</span>
                                    <span class="address_phone"></span>
                                </li>
                                <li>
                                    <span>@lang('order.contact address')：</span>
                                    <span class="address_location"></span>
                                </li>
                            @endif
                        </ul>
                        <div class="right">
                            <a class="change_address" data-url="{{ route('user_addresses.list_all') }}"
                               href="javascript:void(0);">@lang('order.Switch address')</a>
                            <a class="add_new_address" href="javascript:void(0);">@lang('order.New address')</a>
                        </div>
                    </div>
                </div>
                <div class="pre_payment_main">
                    <p class="main_title">@lang('order.Product list')</p>
                    <div class="pre_payment_main_header">
                        <div class="left w110"></div>
                        <div class="left w250">@lang('order.Product information')</div>
                        <div class="left w150 center">@lang('product.shopping_cart.Specifications')</div>
                        <div class="left w150 center">@lang('product.shopping_cart.Unit_price')</div>
                        <div class="left w150 center">@lang('product.shopping_cart.Quantity')</div>
                        <div class="left w150 center">@lang('product.shopping_cart.Subtotal')</div>
                    </div>
                    <div class="pre_payment-items">
                        @if($items)
                            @foreach($items as $item)
                                <div class="clear single-item">
                                    <div class="left w110 shop-img">
                                        <a class="cur_p" href="javascript:void(0);">
                                            <img src="{{ $item['product']->thumb_url }}">
                                        </a>
                                    </div>
                                    <div class="left w250 pro-info">
                                        <span>{{ App::isLocale('en') ? $item['product']->name_en : $item['product']->name_zh }}</span>
                                    </div>
                                    <div class="left w150 center">
                                        <span>{{ App::isLocale('en') ? $item['sku']->name_en : $item['sku']->name_zh }}</span>
                                    </div>
                                    <div class="left w150 center RMB_num">&#165; <span>{{ $item['sku']->price }}</span>
                                    </div>
                                    <div class="left w150 dis_n center dollar_num">&#36;
                                        <span>{{ $item['sku']->price_en }}</span></div>
                                    <div class="left w150 center counter"><span>{{ $item['number'] }}</span></div>
                                    <div class="left w150 s_total red center RMB_num">&#165;
                                        <span>{{ $item['amount'] }}</span></div>
                                    <div class="left w150 s_total dis_n red dollar_num center">&#36;
                                        <span>{{ $item['amount_en'] }}</span></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="pre_payment_footer">
                    <p class="main_title">@lang('order.currency option')</p>
                    <p class="currency_selection">
                        <a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>
                        <a href="javascript:void(0);" code="dollar" country="USD">@lang('order.Dollars')</a>
                    </p>
                    <ul>
                        <li class="clear">
                            <span>@lang('order.order note')：</span>
                            <textarea class="remark" maxlength="50"
                                      placeholder="@lang('order.Optional message')"></textarea>
                        </li>
                        <li>
                            <p>
                                <span>@lang('order.A total of')：</span>
                                <span class="RMB_num amount_of_money">&#165; <span>{{ $total_amount }}</span></span>
                                <span class="dis_ni dollar_num amount_of_money">&#36; <span>{{ $total_amount_en }}</span></span>
                            </p>
                            <p>
                                <span>@lang('order.freight')：</span>
                                <span class="RMB_num amount_of_money">&#165; <span>{{ $total_shipping_fee }}</span></span>
                                <span class="dis_ni dollar_num amount_of_money">&#36; <span>{{ $total_shipping_fee_en }}</span></span>
                            </p>
                        </li>
                        <li>
                            <p>
                                <span>@lang('order.Amount payable')：</span>
                                <span class="red RMB_num amount_of_money">&#165; <span>{{ $total_fee }}</span></span>
                                <span class="red dis_ni dollar_num amount_of_money">&#36; <span>{{ $total_fee_en }}</span></span>
                            </p>
                            <p>
                                <a href="javascript:void(0);" class="payment_btn"
                                   data-url="{{ route('orders.store') }}">@lang('order.payment')</a>
                            </p>
                            @if($address)
                                <p class="address_info">
                                    <span class="address_name">{{ $address->name }}</span>
                                    <span class="address_phone">{{ substr_replace($address->phone, '*', 3, 4) }}</span>
                                </p>
                                <p class="address_info address_location">{{ $address->address }}</p>
                            @else
                                <p class="address_info">
                                    <span class="address_name"></span>
                                    <span class="address_phone"></span>
                                </p>
                                <p class="address_info address_location"></p>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--新建收获地址弹出层-->
    <div class="dialog_popup new_receipt_address">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('basic.address.The new address')</span>
                </div>
                <div class="textarea_content">
                    <form id="creat-form">
                        <ul>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                                    <input class="user_name" name="name" type="text"
                                           placeholder="@lang('basic.address.Enter the consignee name')">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
                                    <input class="user_tel" name="phone" type="text"
                                           placeholder="@lang('basic.address.Enter the real and valid mobile phone number')">
                                </p>
                            </li>
                            <li>
                                <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                                <textarea name="address"
                                          placeholder="@lang('basic.address.Detailed_address')"></textarea>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
    <!--切换地址信息-->
    <div class="changeAddress dis_n">
        <ul></ul>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            //货币种类切换
            $(".currency_selection a").on("click", function () {
                $(".currency_selection a").removeClass("active");
                $(this).addClass('active');
                switch ($(this).attr("code")) {
                    case "RMB":
                        $(".RMB_num").removeClass("dis_n");
                        $("span.RMB_num").removeClass("dis_ni");
                        $(".dollar_num").addClass("dis_n");
                        $("span.dollar_num").addClass("dis_ni");
                        break;
                    case "dollar":
                        $(".RMB_num").addClass("dis_n");
                        $("span.RMB_num").addClass("dis_ni");
                        $(".dollar_num").removeClass("dis_n");
                        $("span.dollar_num").removeClass("dis_ni");
                        break;
                    default :
                        break;
                }
            });
            //新建收获地址
            $(".add_new_address").on("click", function () {
                $(".new_receipt_address").show();
            });
            $(".new_receipt_address").on("click", ".success", function () {
                $(".address_name").html($(".new_receipt_address .user_name").val());
                $(".address_phone").html($(".new_receipt_address .user_tel").val());
                $(".address_location").html($(".new_receipt_address textarea").val());
                $(".new_receipt_address").hide();
            });
            //切换地址
            $(".change_address").on("click", function () {
                var url = $(this).attr("data-url");
                var changeAdd;
                $.ajax({
                    type: "get",
                    url: url,
                    beforeSend: function () {
                    },
                    success: function (json) {
                        if (json.code == 200) {
                            var dataObj = json.data.addresses;
                            if (dataObj.length > 0) {
                                var html = "";
                                $.each(dataObj, function (i, n) {
                                    html += "<li class='clear'>" +
                                            "<p class='clear'><span>@lang('order.Contact')：</span><span class='name'>" + n.name + "</span></p>" +
                                            "<p class='clear'><span>@lang('order.Contact information')：</span><span class='phone'>" + n.phone + "</span></p>" +
                                            "<p class='clear'><span>@lang('order.contact address')：</span><span class='address'>" + n.address + "</span></p>" +
                                            "</li>";
                                });
                                $(".changeAddress ul").html("");
                                $(".changeAddress ul").append(html);
                                changeAdd = layer.open({
                                    type: 1,
                                    area: ['600px', '550px'],
                                    shadeClose: false,
                                    title: "@lang('order.Select address')",
                                    content: $(".changeAddress"),
                                    btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                                    btnAlign: 'c',
                                    success: function () {
                                    },
                                    yes: function () {   //确定
                                        if ($(".changeAddress").find("li.active").length <= 0) {
                                            layer.msg("@lang('order.Please choose the harvest address')");
                                        } else {
                                            $(".address_name").html($(".changeAddress").find("li.active").find(".name").html());
                                            $(".address_phone").html($(".changeAddress").find("li.active").find(".phone").html());
                                            $(".address_location").html($(".changeAddress").find("li.active").find(".address").html());
                                            layer.close(changeAdd);
                                        }
                                    },
                                    btn2: function () {     //取消
                                        layer.close(changeAdd);
                                    },
                                    end: function () {
                                        $(".changeAddress ul").html("");
                                    },
                                });
                            } else {
                                layer.close(changeAdd);
                                $(".new_receipt_address").show();
                            }
                        }
                    },
                    error: function () {
                    },
                    complete: function () {
                    },
                });
            });
            //点击选择收货地址
            $(".changeAddress ul").on("click", "li", function () {
                $(".changeAddress ul").find("li").removeClass("active");
                $(this).addClass("active");
            });
            //获取url,通过判断url中参数sendWay的值来确定从哪个页面进入，1、立即购买，2、购物车
            var loading_animation;

            function getUrlVars(url_name) {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars[url_name];
            }

            $(".payment_btn").on("click", function () {
                var address_name = $(".address_name").html();
                var address_phone = $(".address_phone").html();
                var address_location = $(".address_location").html();
                var url = $(this).attr("data-url");
                var sendWay = getUrlVars("sendWay");
                console.log(address_name);
                if (address_name == "" || address_phone == "" || address_location == "") {
                    layer.msg("@lang('order.Please fill in the address completely')");
                } else {
                    switch (sendWay) {
                        case "1":
                            var sku_id = getUrlVars("sku_id");
                            var number = getUrlVars("number");
                            payment_one(sku_id, number, url);
                            break;
                        case "2":
                            var cart_ids = getUrlVars("cart_ids");
                            payment_two(cart_ids, url);
                            break;
                        default :
                            break;
                    }
                }
            });
            //第一类创建订单（直接下单）
            function payment_one(sku_id, number, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_id: sku_id,
                    number: number,
                    name: $(".address_name").html(),
                    phone: $(".address_phone").html(),
                    address: $(".address_location").html(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country")
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false //取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.request_url;
                    },
                    error: function (err) {
                        console.log(err);
                        layer.msg($.parseJSON(err.responseText).errors.currency[0])
                    },
                    complete: function () {
                    }
                });
            }

            //第二类创建订单（购物车下单）
            function payment_two(cart_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    cart_ids: cart_ids,
                    name: $(".address_name").html(),
                    phone: $(".address_phone").html(),
                    address: $(".address_location").html(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country")
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false, //取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.request_url;
                    },
                    error: function (err) {
                        console.log(err);
                        layer.msg($.parseJSON(err.responseText).errors.currency[0])
                    },
                    complete: function () {
                    },
                });
            }
        });
    </script>
@endsection
