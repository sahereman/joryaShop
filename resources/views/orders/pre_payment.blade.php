@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '确认订单' : 'Confirm The Order') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="pre-payment">
        <div class="main-content">
            {{-- 面包屑导航 --}}
            <div class="Crumbs">
                <a href="{{ route('root') }}">@lang('basic.home')</a>
                <span>></span>
                <a href="javascript:void(0);">@lang('order.Confirm Order')</a>
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
                            @if($items)
                                @foreach($items as $item)
                                    <div class="products-part-item">
                                        {{-- 商品信息 --}}
                                        <div class="product">
                                            {{-- 商品图片 --}}
                                            <div class="product-img">
                                                <a class="cur_p" href="avascript:void(0);">
                                                    <img class="lazy" data-src="{{ $item['product']->thumb_url }}">
                                                </a>
                                            </div>
                                            {{-- 商品介绍 --}}
                                            <div class="product-info">
                                                <a class="cur_p" href="javascript:void (0)">
                                                    <span>{{ App::isLocale('zh-CN') ? $item['product']->name_zh : $item['product']->name_en }}</span>
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
                                                <span class="single-price">{{ $item['amount'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($attr_values[$item['sku']->id]))
                                    {{-- 商品详情 --}}
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS <span class="iconfont">&#xe605;</span></p>
                                            <div class="order-details">
                                                {{--循环的时候分奇偶数 --}}
                                                @foreach($attr_values[$item['sku']->id] as $key => $attr_value)
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
                            <p>Confirm the Address</p>
                        </div>
                        {{-- 默认地址，正常显示 --}}
                        <div class="address-info-content pre_payment_header add-new-address-box" code='{{ $address ? $address->id : ''}}'>
                            @if($address)
                            {{-- 有地址 --}}
                                <div class="address-name">
                                    <span class="address_name">{{ $address->name }}</span>
                                </div>
                                <div class="address-fullAddress">
                                    <span class="address_location">{{ $address->full_address }}</span>
                                </div>
                                <div class="address-phone">
                                    <span class="address_phone">{{ $address->phone }}</span>
                                </div>
                                {{-- 修改按钮，切换收货地址 --}}
                                <div class="edit-address">
                                    @guest
                                        <a class="change_address for-login-show" data-url="{{ route('user_addresses.list_all') }}"
                                        href="javascript:void(0);"><span class="iconfont">&#xe616;</span>Edit</a>
                                    @else
                                        <a class="change_address" data-url="{{ route('user_addresses.list_all') }}"
                                        href="javascript:void(0);"><span class="iconfont">&#xe616;</span>Edit</a>
                                    @endguest
                                </div>
                                {{-- 默认地址 --}}
                                <div class="primary">primary</div>
                            @else
                                {{-- 没有地址，新建收货地址 --}}
                                <button class="add_new_address"><span class="iconfont">&#xe602;</span>Add New Address</button>
                            @endif
                        </div>
                        {{-- 更换地址 --}}
                        <div class="changeAddress add-new-address-box dis_n">
                            <ul></ul>
                            {{-- 新增收货地址 --}}
                            <button class="add_new_address"><span class="iconfont">&#xe602;</span>Add New Address</button>
                            <hr>
                            {{-- 操作区 --}}
                            <div class="operating-space">
                                <div class="operating-btns">
                                    <a href="javascript:void(0)" class="cancel-btn change-cancel-btn">cancel</a>
                                    <a href="javascript:void(0)" class="confirm-btn save-choose-address">Save The Address</a>
                                </div>
                            </div>
                        </div>
                        {{-- 新建地址 --}}
                        <div class="add-new-address dis_n">
                            <form id="creat-form" data-url="{{ route('user_addresses.store_for_ajax') }}">
                                <ul class="new_receipt_address" data-url="{{ route('user_addresses.store_for_ajax') }}">
                                    <li>
                                        <p>
                                            <span class="input_name"><i>*</i>Country：</span>
                                            {{--<input class="user_country" name="country" type="text">--}}
                                            <select name="country" id="new_address_country" class="user_country">
                                                <option value=0>Please select a country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country }}">{{ $country }}</option>
                                                @endforeach
                                            </select>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                                            <input class="user_name" name="name" type="text">
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：
                                                <span class="span-tip">@lang('basic.address.Detailed_address')</span></span>
                                            <input name="address" class="user_detailed">
                                        </p>
                                    </li>
                                    <li class="city-state-zip">
                                        <p>
                                            <span class="input_name"><i>*</i>City：</span>
                                            <input class="user_city" name="city" type="text">
                                        </p>
                                        <p>
                                            <span class="input_name"><i>*</i>State/Province/Region：</span>
                                            {{--<input class="user_province" name="province" type="text">--}}
                                            <select name="province" id="new_address_province" class="user_province">
                                                <option value=0>Please select a state/province/region</option>
                                            </select>
                                        </p>
                                        <p>
                                            <span class="input_name"><i>*</i>Zipcode：</span>
                                            <input class="user_zip" name="zip" type="text">
                                        </p>
                                    </li>
                                    <li class="contact-number">
                                        <p>
                                            <span class="input_name"><i>*</i>@lang('basic.address.Contact')：
                                                <span class="span-tip">We only use this number if there's a shipping issue</span></span>
                                            <input class="user_tel" name="phone" type="text">
                                        </p>
                                    </li>
                                    <li class="dis_ni">
                                        <p class="default_address_set">
                                            <label>
                                                <input type="checkbox" name="is_default" class="setas_default" value="1">
                                                <span>@lang('basic.address.Set to the default')</span>
                                            </label>
                                        </p>
                                    </li>
                                </ul>
                            </form>
                            <span class="dis_ni" id="provinces" data-json="{{ $provinces }}"></span>
                            {{-- 按钮区 --}}
                            <div class="operating-space">
                                <label>
                                    <input type="checkbox" name="is_default" class="setas_default" value="1">
                                    <span>@lang('basic.address.Set to the default')</span>
                                </label>
                                <div class="operating-btns">
                                    <a href="javascript:void(0)" class="cancel-btn new-cancel-btn">cancel</a>
                                    <a href="javascript:void(0)" class="confirm-btn save-new-address">Save The Address</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 优惠券相关 --}}
                    <div class="discount-coupon">
                        <div class="Currency-options">
                            <p class="currency-options-title">Currency options</p>
                            <div>
                                <p class="currency_selection">
                                    {{--<a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>--}}
                                    <a href="javascript:void(0);" class="active" code="dollar" country="USD">
                                        <span class="iconfont">&#xe607;</span> @lang('order.Dollars')</a>
                                </p>
                            </div>
                        </div>
                        <div class="available_coupons">
                            <p class="available_coupons-title">Add coupons</p>
                            @if($available_coupons->isNotEmpty())
                                <p class="coupon-select">
                                    <select class="coupon_selection" name="coupon_id">
                                        <option value="0" data-coupon-num="0">Don't use coupons</option>
                                        @foreach($available_coupons as $available_coupon)
                                            <option value="{{$available_coupon->id}}"
                                                    data-coupon-num="{{$available_coupon->saved_fee}}">
                                                {{$available_coupon->coupon_name}} - {{$available_coupon->saved_fee}}
                                            </option>
                                        @endforeach
                                    </select>
                                </p>
                            @else
                                <p class="coupon-select">
                                    <select class="coupon_selection" name="coupon_id">
                                        <option value="0" data-coupon-num="0">There is no coupons～</option>
                                    </select>
                                </p>
                            @endif
                        </div>
                        {{-- 快递 Choose the Type of shipping--}}
                        <div class="shipping-type">
                            <p class="shipping-type-title">Choose the Type of shipping</p>
                            <ul id="shipment-template">
                                {{--<li class="active">Free shipping</li>
                                <li>Fedex</li>
                                <li>UPS</li>--}}
                                @if($shipment_template)
                                    <li class="active">{{ $shipment_template->name }}</li>
                                @else
                                    <li class="active">Free Shipping</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- 右侧金额相关 --}}
                <div class="oreders-right">
                    {{-- 留言 --}}
                    <div class="order-note">
                        <p class="order-note-title">Order note</p>
                        <textarea class="remark" placeholder="Optional, leave a message to the seller"></textarea>
                    </div>
                    {{-- 费用 --}}
                    <div class="order-fee">
                        <p class="order-fee-title">Order Fee</p>
                        <div class="amount-detail">
                            <p>
                                <span>@lang('order.Sum')：</span>
                                <span class="dollar_num amount_of_money">&#36; <span>{{ $total_amount }}</span></span>
                            </p>
                            <p>
                                <span>@lang('order.freight')：</span>
                                <span class="dollar_num amount_of_money">&#36; <span class="total_shipping_fee">{{ $total_shipping_fee }}</span></span>
                            </p>
                            <p>
                                <span>Discount：</span>
                                <span class="dollar_num amount_of_money coupon_of_money">&#36; <span class="coupon_num">0</span></span>
                            </p>
                        </div>
                        <p class="total-price">
                            <span>@lang('order.Amount payable')：</span>
                            <span class="red dollar_num amount_of_money">&#36; <span class="total_price" data-price="{{ $total_fee }}">{{ $total_fee }}</span></span>
                        </p>
                        <p class="saved-fee">
                            <span>(Saved fee: &#36;  <span class="discounts_num" data-discounts="{{ $saved_fee }}">{{ $saved_fee }}</span> )</span>
                        </p>
                        <p>
                            <a href="javascript:void(0);" class="payment_btn"
                                data-url="{{ route('orders.store') }}">@lang('order.payment')</a>
                        </p>
                        @if($address)
                            <p class="address_info">
                                <span class="address_name address_name_bottom">{{ $address->name }}</span>
                            </p>
                            <p>
                                <span class="address_phone address_phone_bottom">{{ $address->phone }}</span>
                            </p>
                            <p class="address_info address_location address_location_bottom">{{ $address->full_address }}</p>
                            <p class="address_info address_province address_province_bottom dis_ni">{{ $address->province }}</p>
                        @else
                            <p class="address_info">
                                <span class="address_name address_name_bottom"></span>
                            </p>
                            <p>
                                <span class="address_phone address_phone_bottom"></span>
                            </p>
                            <p class="address_info address_location address_location_bottom"></p>
                            <p class="address_info address_province address_province_bottom dis_ni"></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            // 点击展开和收起参数详情
            $('.products-part-items').on("click", ".order-detail-title", function () {
                var clickDom = $(this);
                if (clickDom.hasClass("active")) {
                    clickDom.removeClass("active");
                    clickDom.find("span").removeClass("active");
                    clickDom.parent(".cart-item-bottom").find(".order-details").slideUp();
                } else {
                    clickDom.addClass("active");
                    clickDom.find("span").addClass("active");
                    clickDom.parent(".cart-item-bottom").find(".order-details").slideDown();
                }
            });
            // 货币种类切换
            // $(".currency_selection a").on("click", function () {
            //     $(".currency_selection a").removeClass("active");
            //     $(this).addClass('active');
            //     switch ($(this).attr("code")) {
            //         case "RMB":
            //             $(".RMB_num").removeClass("dis_n");
            //             $("span.RMB_num").removeClass("dis_ni");
            //             $(".dollar_num").addClass("dis_n");
            //             $("span.dollar_num").addClass("dis_ni");
            //             break;
            //         case "dollar":
            //             $(".RMB_num").addClass("dis_n");
            //             $("span.RMB_num").addClass("dis_ni");
            //             $(".dollar_num").removeClass("dis_n");
            //             $("span.dollar_num").removeClass("dis_ni");
            //             break;
            //         default :
            //             break;
            //     }
            // });
            // 数据计算方法
            function float_multiply_by_100(float) {
                float = String(float);
                // float = float.toString();
                var index_of_dec_point = float.indexOf('.');
                if (index_of_dec_point == -1) {
                    float += '00';
                } else {
                    var float_splitted = float.split('.');
                    var dec_length = float_splitted[1].length;
                    if (dec_length == 1) {
                        float_splitted[1] += '0';
                    } else if (dec_length > 2) {
                        float_splitted[1] = float_splitted[1].substring(0, 1);
                    }
                    float = float_splitted.join('');
                }
                return Number(float);
            }
            function js_number_format(number) {
                number = String(number);
                var index_of_dec_point = number.indexOf('.');
                if (index_of_dec_point == -1) {
                    number += '.00';
                } else {
                    var number_splitted = number.split('.');
                    var dec_length = number_splitted[1].length;
                    if (dec_length == 1) {
                        number += '0';
                    } else if (dec_length > 2) {
                        number_splitted[1] = number_splitted[1].substring(0, 2);
                        number = number_splitted.join('.');
                    }
                }
                return number;
            }
            // 优惠券切换
            $(".coupon_selection").on("change",function () {
                var couponNum = $(this).find("option:selected").attr("data-coupon-num");
                // 商品最初的总价格
                var oldPrice = $(".total_price").attr("data-price");
                // 商品最初的优惠金额
                var oldDiscounts = $(".discounts_num").attr("data-discounts");
                var newPrice = float_multiply_by_100(oldPrice) - float_multiply_by_100(couponNum);
                var newDiscounts = float_multiply_by_100(oldDiscounts) + float_multiply_by_100(couponNum);
                $(".coupon_num").text(couponNum);
                if(newDiscounts == 0){
                    $(".discounts_num").text("0");
                }else {
                    $(".discounts_num").text(js_number_format(newDiscounts/ 100));
                }
                // 计算选择优惠券后的总价格
                $(".total_price").text(js_number_format(newPrice/ 100));
            });

            $("#creat-form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    province: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "@lang('Please enter the consignee name')"
                    },
                    phone: {
                        required: "@lang('Please enter the consignee contact information')"
                    },
                    address: {
                        required: "@lang('Please enter the detailed shipping address')"
                    },
                    country: {
                        required: "Please select a country"
                    },
                    province: {
                        required: "Please select a state/province/region"
                    }
                },
            });
            // 取消选择地址
            $(".change-cancel-btn").on("click", function(){
                $(".changeAddress").addClass("dis_n");
                $(".address-info-content").removeClass("dis_n");
            });
            // 取消新建地址
            $(".new-cancel-btn").on("click", function(){
                $(".add-new-address").addClass("dis_n");
                $(".address-info-content").removeClass("dis_n");
            });
            // 新建收货地址
            $(".add_new_address").on("click", function () {
                $(this).parents(".add-new-address-box").addClass("dis_n");
                $(".add-new-address").removeClass("dis_n");
            });
            // 保存新增的地址信息
            $(".save-new-address").on("click",function(){
                if ($("#creat-form").valid()) {
                    if($(".new_receipt_address .user_country").val() == 0||$(".new_receipt_address .user_province").val() == 0) {
                        layer.msg("Please select country and state/province/region");
                        return
                    }
                    var data = {
                        _token: "{{ csrf_token() }}",
                        name: $(".new_receipt_address .user_name").val(),
                        phone: $(".new_receipt_address .user_tel").val(),
                        address: $(".new_receipt_address .user_detailed").val(),
                        country: $(".new_receipt_address .user_country").val(),
                        city: $(".new_receipt_address .user_city").val(),
                        province: $(".new_receipt_address .user_province").val(),
                        zip: $(".new_receipt_address .user_zip").val(),
                        is_default: "0"
                    };
                    $.ajax({
                        type: "post",
                        url: $("#creat-form").attr("data-url"),
                        data: data,
                        success: function (json) {
                            $(".address_name").html(json.data.address.name);
                            $(".address_phone").html(json.data.address.phone);
                            $(".address_location").html(json.data.address.full_address);
                            $(".address_province").html(json.data.address.province);
                            $(".pre_payment_header").attr("code", json.data.address.id);
                            document.getElementById("creat-form").reset();
                            $(".add-new-address").addClass("dis_n");
                            $(".address-info-content").removeClass("dis_n");
                        //    新建地址保存成功后调用获取运费的接口
                            var sendWay = getUrlVars("sendWay");
                            var requestData ;
                            switch (sendWay) {
                                case "1":
                                    requestData= {
                                        sku_id: getUrlVars("sku_id"),
                                        number: getUrlVars("number"),
                                        province: json.data.address.province
                                    };
                                    break;
                                case "2":
                                    var sku_ids = getUrlVars("sku_ids");
                                    requestData= {
                                        sku_ids:  getUrlVars("sku_ids"),
                                        province: json.data.address.province
                                    };
                                    break;
                                default :
                                    break;
                            }
                            getTotalShippingFee(requestData);
                        },
                        error: function (err) {
                            var arr = [];
                            var dataobj = err.responseJSON.errors;
                            for (let i in dataobj) {
                                arr.push(dataobj[i]); // 属性
                            }
                            layer.msg(arr[0][0]);
                        }
                    });
                }
            });
            // 切换地址
            $(".change_address").on("click", function () {
                if ($(this).hasClass("for-login-show")) {
                    layer.alert("Please login first");
                    return
                }
                var url = $(this).attr("data-url");
                var changeAdd,userProvince;
                var defaultCode = $(".pre_payment_header").attr("code");
                $.ajax({
                    type: "get",
                    url: url,
                    success: function (json) {
                        if (json.code == 200) {
                            var dataObj = json.data.addresses;
                            console.log(dataObj)
                            if (dataObj.length > 0) {
                                var html = "";
                                $.each(dataObj, function (i, n) {
                                    if(defaultCode != ""){
                                        if(defaultCode == n.id) {
                                            html += "<li class='clear active' code='" + n.id + "'>"
                                        }else{
                                            html += "<li class='clear' code='" + n.id + "'>"
                                        }
                                    }
                                    html += "<div class='address-item'>"
                                    html += "<p class='address-item-name name'>"+  n.name +"</p>"
                                    html += "<p class='address-item-local address'>" + n.full_address + "</p>"
                                    html += "<p class='address-item-phone phone'>" + n.phone + "</p>"
                                    html += "<p class='dis_ni'><span class='user_province'>" + n.province + "</span></p>"
                                    html += "</div>"
                                    if(n.is_default == true){
                                        html += "<div class='primary'>primary</div>"   
                                    }
                                    html += "</li>"
                                    // "<div class='edit-address'>"+
                                    // "<a class='change_address' href='javascript:void(0);''><span class='iconfont'>&#xe616;</span>Edit</a>"+
                                    // "</div>"+
                                });
                                $(".changeAddress ul").html("");
                                $(".changeAddress ul").append(html);
                                $(".address-info-content").addClass("dis_n")
                                $(".changeAddress").removeClass("dis_n");
                            } else {
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
            // 点击选择收货地址
            $(".changeAddress ul").on("click", "li", function () {
                $(".changeAddress ul").find("li").removeClass("active");
                $(this).addClass("active");
            });
            // 保存选中的地址
            $(".save-choose-address").on("click", function(){
                if ($(".changeAddress").find("li.active").length <= 0) {
                    layer.msg("@lang('order.Please choose the harvest address')");
                } else {
                    $(".address_name").html($(".changeAddress").find("li.active").find(".name").html());
                    $(".address_phone").html($(".changeAddress").find("li.active").find(".phone").html());
                    $(".address_location").html($(".changeAddress").find("li.active").find(".address").html());
                    $(".address_province").html($(".changeAddress").find("li.active").find(".user_province").html());
                    $(".pre_payment_header").attr("code", $(".changeAddress").find("li.active").attr("code"));
                    userProvince = $(".changeAddress").find("li.active").find(".user_province").html();
                    $(".changeAddress").addClass("dis_n");
                    $(".address-info-content").removeClass("dis_n");
                    //    新建地址保存成功后调用获取运费的接口
                    var sendWay = getUrlVars("sendWay");
                    var requestData ;
                    switch (sendWay) {
                        case "1":
                            requestData= {
                                sku_id: getUrlVars("sku_id"),
                                number: getUrlVars("number"),
                                province: userProvince
                            };
                            break;
                        case "2":
                            var sku_ids = getUrlVars("sku_ids");
                            requestData= {
                                sku_ids:  getUrlVars("sku_ids"),
                                province: userProvince
                            };
                            break;
                        default :
                            break;
                    }
                    getTotalShippingFee(requestData);
                }
            });
            // 获取url,通过判断url中参数sendWay的值来确定从哪个页面进入，1、立即购买，2、购物车
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
                var address_name = $(".address_name_bottom").html();
                var address_phone = $(".address_phone_bottom").html();
                var address_location = $(".address_location_bottom").html();
                var url = $(this).attr("data-url");
                var sendWay = getUrlVars("sendWay");
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
                            var sku_ids = getUrlVars("sku_ids");
                            payment_two(sku_ids, url);
                            break;
                        default :
                            break;
                    }
                }
            });

            // 第一类创建订单（直接下单）
            function payment_one(sku_id, number, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_id: sku_id,
                    number: number,
                    address_id: $(".pre_payment_header").attr("code"),
                    name: $('.address_name_bottom').text(),
                    phone: $('.address_phone_bottom').text(),
                    address: $('.address_location_bottom').text(),
                    province: $('.address_province_bottom').text(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country"),
                    // coupon_id : $(".coupon_selection option:selected").val()
                };
                var coupon_id = $(".coupon_selection option:selected").val();
                if (coupon_id != 0) {
                    data.coupon_id = coupon_id;
                }
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false // 取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.request_url;
                    },
                    error: function (err) {
                        var arr = []
                        var dataobj = err.responseJSON.errors;
                        for (var i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    },
                    complete: function () {
                    }
                });
            }

            // 第二类创建订单（购物车下单）
            function payment_two(sku_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_ids: sku_ids,
                    address_id: $(".pre_payment_header").attr("code"),
                    name: $('.address_name_bottom').text(),
                    phone: $('.address_phone_bottom').text(),
                    address: $('.address_location_bottom').text(),
                    province: $('.address_province_bottom').text(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country"),
                    // coupon_id : $(".coupon_selection option:selected").val()
                };
                var coupon_id = $(".coupon_selection option:selected").val();
                if (coupon_id != 0) {
                    data.coupon_id = coupon_id;
                }
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false, // 取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.request_url;
                    },
                    error: function (err) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (var i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    },
                    complete: function () {
                    },
                });
            }

            // 省份二级联动
            // 省份假数据
            // 设置二级联动中的选项数组
            var country_provinces = JSON.parse($('#provinces').attr('data-json'));
            var provinces = [];
            for (var index in country_provinces) {
                provinces[index] = country_provinces[index];
            }
            provinces['0'] = ['Please select a state/province/region'];
            // 获取页面中的选项卡
            var country = document.getElementById('new_address_country');
            var province = document.getElementById('new_address_province');

            // 初始化第二个选项卡，默认显示"请选择城市"
            province.options.length = 1;
            // province.options.length = provinces[countries['0']].length;
            province.options[0].text = 'Please select a state/province/region';
            province.options[0].value = 0;

            // 通过onchange监视函数，一旦第一个选项卡发生变化，第二个选项卡中的内容也跟着变化
            country.onchange = function () {
                country_name = this.value;
                if (country_name != 0) {
                    province_set = provinces[country_name];
                    province.options.length = province_set.length;
                    for (var j = 0; j < province.options.length; j++) {
                        //key = j + 1;
                        province.options[j].text = provinces[country_name][j];
                        province.options[j].value = provinces[country_name][j];
                    }
                }
            };
            // 根据地址变化获取运费获取运费
            function getTotalShippingFee(requestData) {
                var requestUrl = "{{ route('orders.get_total_shipping_fee') }}";
                var data = requestData;
                $.ajax({
                    type: "get",
                    url: requestUrl,
                    data: data,
                    success: function (json) {
                        $(".total_shipping_fee").text(json.total_shipping_fee);
                        if (json.shipment_template) {
                            $("ul#shipment-template").find('li.active').text(json.shipment_template.name);
                        } else {
                            $("ul#shipment-template").find('li.active').text('Free Shipping');
                        }
                    },
                    error: function (err) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (var i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                });
            }
        });
    </script>
@endsection
