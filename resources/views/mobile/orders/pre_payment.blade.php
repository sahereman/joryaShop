@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '确认订单' : 'Confirm The Order') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar fixHeader {{ is_wechat_browser() ? 'height_no' : '' }}">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('app.Confirm the Order')</span>
    </div>
    <div class="pre_payment {{ is_wechat_browser() ? 'margin-top_no' : '' }}">
        <div class="pre_paymentCon">
            @if($address)
                <div class="pre_address edit_address" data-url="{{ route('user_addresses.list_all') }}">
                    <div>
                        <p class="address_title" code='{{ $address->id }}'>
                            <span class="address_name">{{ $address->name }}</span>
                            <span class="address_phone">{{ $address->phone }}</span>
                        </p>
                        <p class="address_info">
                            {{--@if($address->is_default == 1)--}}
                                {{--<span class="default_btn">@lang('basic.address.Default')</span>--}}
                            {{--@endif--}}
                            <span class="address_info_all">{{ $address->full_address }}</span>
                        </p>
                    </div>
                    <img src="{{ asset('static_m/img/icon_more.png') }}">
                </div>
            @else
                <div class="pre_address add_address no_address" data-url="{{ route('user_addresses.list_all') }}">
                    <div>
                        <img src="{{ asset('static_m/img/icon_pre_address.png') }}">
                        <span class="no_address">@lang('basic.address.Add a shipping address')</span>
                    </div>
                    <img src="{{ asset('static_m/img/icon_more.png') }}">
                </div>
                <div class="pre_address no_address edit_address dis_ni"
                     data-url="{{ route('user_addresses.list_all') }}">
                    <div>
                        <p class="address_title" code=''>
                            <span class="address_name"></span>
                            <span class="address_phone"></span>
                        </p>
                        <p class="address_info">
                            <span class="default_btn">@lang('basic.address.Default')</span>
                            <span class="address_info_all"></span>
                        </p>
                    </div>
                    <img src="{{ asset('static_m/img/icon_more.png') }}">
                </div>
            @endif
            <div class="pre_products">
                <ul>
                    @if($items)
                        @foreach($items as $key => $item)
                            @if($key > 2)
                                @break
                            @endif
                            <li>
                                <img src="{{ $item['product']->thumb_url }}">
                                <span>&#215; {{ $item['number'] }}</span>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <!--显示商品总数量-->
                {{--@if(\Illuminate\Support\Facades\App::isLocale('en'))
                    <span class="pre_products_num">
                        {{ count($items) .' '. (count($items) > 1 ? \Illuminate\Support\Str::plural(__('order.Commodity')) : __('order.Commodity')) .' '. __('order.in total') }}
                    </span>
                @else--}}
                <span class="pre_products_num">
                        {{ count($items) }}
                    </span>
                {{--@endif--}}
                <img src="{{ asset('static_m/img/icon_more.png') }}">
            </div>
            <div class="pre_amount">
                <p>
                    <span>@lang('order.Sum')</span>
                    {{--<span class="dis_ni RMB_num">&#165; {{ exchange_price($total_amount, 'CNY') }}</span>--}}
                    <span class="dollar_num">&#36; {{ $total_amount }}</span>
                </p>
                <p>
                    <span>@lang('order.freight')</span>
                    {{--<span class="dis_ni RMB_num amount_of_money">&#165; <span>{{ exchange_price($total_shipping_fee, 'CNY') }}</span></span>--}}
                    <span class="dollar_num amount_of_money">&#36; <span>{{ $total_shipping_fee }}</span></span>
                </p>
            </div>
            <div class="pre_currency">
                <p class="main_title">@lang('order.Currency options')</p>
                <p class="currency_selection">
                    {{--<a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>--}}
                    <a href="javascript:void(0);" code="dollar" country="USD">@lang('order.Dollars')</a>
                </p>
            </div>
            <div class="pre_note">
                <p>@lang('order.order note')</p>
                <textarea name="remark" class="remark" placeholder="@lang('order.Optional message')"
                          maxlength="50"></textarea>
            </div>
        </div>
        <div class="pre_paymentTotal">
            {{--<span class="dis_ni RMB_num amount_of_money">&#165; <span>{{ exchange_price($total_fee, 'CNY') }}</span></span>--}}
            <span class="dollar_num amount_of_money">&#36; <span>{{ $total_fee }}</span></span>
            <a href="javascript:void(0);" class="payment_btn"
               data-url="{{ route('orders.store') }}">@lang('basic.orders.Submit an Order')</a>
        </div>
    </div>
    <!--新增地址与选择地址的弹窗-->
    <div class="address_choose animated dis_n">
        <div class="headerBar fixHeader">
            <a href="javascript:void(0);" class="close_layer_img">
                <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg">
            </a>
            <span>@lang('basic.users.Receiving_address')</span>
        </div>
        <!--如果有地址显示地址列表与新增地址按钮-->
        <div class="ads1Box lay_content">
            <!--有收货地址数据时-->
            <div class="adsList"></div>
            <div class="btnBox">
                <a href="javascript:void(0);"
                   class="doneBtn creat_address_btn">@lang('basic.address.The new address')</a>
            </div>
        </div>
        <!--如果没有地址显示新建地址与保存按钮-->
        <div class="addAdsBox lay_content">
            <div class="addAdsForm">
                <div class="addAdsItem">
                    <label class="must">@lang('basic.address.The consignee')</label>
                    <input type="text" name="name" id="new_address_name" value=""
                           placeholder="@lang('basic.address.Please fill in the consignee')"/>
                </div>
                <div class="addAdsItem">
                    <label class="must">@lang('basic.address.Cellphone number')</label>
                    <input type="text" name="phone" id="new_address_phone" value=""
                           placeholder="@lang('basic.address.Please fill in your mobile phone number')"/>
                </div>
                <div class="addAdsItem">
                    <label class="must">Country or region</label>
                    <input type="text" name="country" id="new_address_country" value=""
                           placeholder="Please fill in your Country or region"/>
                </div>
                <div class="addAdsItem">
                    <label class="must">City</label>
                    <input type="text" name="city" id="new_address_city" value=""
                           placeholder="Please fill in your City"/>
                </div>
                <div class="addAdsItem">
                    <label class="must">State/Province/Region</label>
                    <input type="text" name="province" id="new_address_province" value=""
                           placeholder="Please fill in your State/Province/Region"/>
                </div>
                <div class="addAdsItem">
                    <label class="must">Zipcode</label>
                    <input type="text" name="zip" id="new_address_zip" value=""
                           placeholder="Please fill in Zipcode"/>
                </div>
                <div class="addAdsItem" style="border:none;">
                    <label class="must">@lang('basic.address.Detailed address')</label>
                    {{--<input type="text" name="address" id="new_address_info" value=""
                           placeholder="@lang('basic.address.Detailed_address')"/>--}}
                    <input name="address" id="new_address_info"
                           placeholder="@lang('basic.address.Detailed_address')">
                </div>
                <button class="doneBtn save_new_address"
                        data-url="{{ route('user_addresses.store_for_ajax') }}">@lang('basic.users.Save')</button>
            </div>
            <div class="defaultBox">
                <label style="padding-left: 1rem;">@lang('basic.address.Set as default address')</label>
                <input type="hidden" name="is_default" class="setas_default">
                <img src="{{ asset('static_m/img/icon_OFF.png') }}" class="switchBtn"/>
            </div>
        </div>
    </div>
    <!--商品明细弹窗-->
    <div class="pro_lists animated dis_n">
        <div class="headerBar fixHeader">
            <a href="javascript:void(0);" class="close_pro_lists_img">
                <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg">
            </a>
            <span>@lang('product.Product Details')</span>
        </div>
        <div class="pro_listsCon lay_content">
            @if($items)
                @foreach($items as $item)
                    <div class="pro_listsItem">
                        <img src="{{ $item['product']->thumb_url }}"/>
                        <div class="pro_listsDetail">
                            <div class="goodsName">
                                {{ App::isLocale('zh-CN') ? $item['product']->name_zh : $item['product']->name_en }}
                            </div>
                            <div class="goodsSpec">
                                <span>{{ $item['sku']->attr_value_string }}</span>
                            </div>
                            <div class="goodsPri">
                                <div>
                                    {{--<span class="realPri RMB_num">&#165; {{ $item['sku']->price }}</span>
                                    <span class="realPri dis_n dollar_num">&#36; {{ $item['sku']->price_in_usd }}</span>--}}
                                    <span class="realPri RMB_num">&#165; {{ exchange_price($item['sku']->price, 'CNY') }}</span>
                                    <span class="realPri dis_n dollar_num">&#36; {{ $item['sku']->price }}</span>
                                </div>
                                <div class="goodsNum">
                                    <span class="gNum">&#215; {{ $item['number'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            var address_layer;   //地址弹窗
            var pro_lists_layer;   //商品明细弹窗
            var minHeight = $(window).height();
            $(".lay_content").css("min-height", minHeight);
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
            $(".pre_address").on("click", function () {
                var clickDom = $(this);
                $('.address_choose').removeClass("dis_n");
                $('.address_choose').removeClass("fadeOutRightBig");
                $('.address_choose').addClass("fadeInRightBig");
                if ($(this).hasClass("no_address")) {
                    if (clickDom.hasClass("edit_address")) {
                        $(".addAdsBox").hide();
                        $(".ads1Box").show();
                        getAddressList($(this).attr("data-url"));
                    } else {
                        $(".addAdsBox").show();
                        $(".ads1Box").hide();
                    }
                } else {
                    $(".addAdsBox").hide();
                    $(".ads1Box").show();
                    getAddressList($(this).attr("data-url"));
                }
            });
            //关闭地址弹窗
            $(".close_layer_img").on('click', function () {
                $('.address_choose').removeClass("fadeInRightBig");
                $('.address_choose').addClass("fadeOutRightBig");
                $('.address_choose').addClass("dis_n");
            });
            //查看商品明细
            $(".pre_products").on("click", function () {
                $('.pro_lists').removeClass("dis_n");
                $('.pro_lists').removeClass("fadeOutRightBig");
                $('.pro_lists').addClass("fadeInRightBig");
            });
            //关闭商品明细弹窗
            $(".close_pro_lists_img").on('click', function () {
                $('.pro_lists').removeClass("fadeInRightBig");
                $('.pro_lists').addClass("fadeOutRightBig");
                $('.pro_lists').addClass("dis_n");
            });
            //切换币种
            $(".currency_selection").on("click", 'a', function () {
                $(".currency_selection").find("a").removeClass("active");
                $(this).addClass("active");
            });
            //选中地址显示在界面上
            $(".adsList").on("click", '.adsItem', function () {
                if ($(this).find(".defaultAds").length == 1) {
                    $(".default_btn").css("display", 'block');
                } else {
                    $(".default_btn").css("display", 'none');
                }
                $(".address_name").html($(this).find(".ads_Name").html());
                $(".address_phone").html($(this).find(".adsP").html());
                $(".address_info_all").html($(this).find(".adsD").html());
                $('.address_choose').removeClass("fadeInRightBig");
                $('.address_choose').addClass("fadeOutRightBig");
                $('.address_choose').addClass("dis_n");
                $(".address_title").attr("code", $(this).attr("code"));
            });
            //点击地址中的新建地址
            $(".creat_address_btn").on("click", function () {
                $(".addAdsBox").show();
                $(".ads1Box").hide();
            });
            $(".switchBtn").on("click", function () {
                if ($(this).attr("src") == "{{ asset('static_m/img/icon_OFF.png') }}") {
                    $(this).attr("src", "{{ asset('static_m/img/icon_ON.png') }}");
                    $(".setas_default").val("1");
                } else {
                    $(this).attr("src", "{{ asset('static_m/img/icon_OFF.png') }}");
                    $(".setas_default").val("0");
                }
            });
            //点击保存
            $(".save_new_address").on("click", function () {
                if ($("#new_address_name").val() == "" || $("#new_address_phone").val() == "" || $("#new_address_info").val() == "") {
                    layer.open({
                        content: "@lang('order.Please complete the information')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return false
                }
                var data = {
                    _token: "{{ csrf_token() }}",
                    name: $("#new_address_name").val(),
                    phone: $("#new_address_phone").val(),
                    address: $("#new_address_info").val(),
                    country: $("#new_address_country").val(),
                    city: $("#new_address_city").val(),
                    province: $("#new_address_province").val(),
                    zip: $("#new_address_zip").val(),
                    is_default: $(".setas_default").val()
                };
                $.ajax({
                    type: "post",
                    url: $(this).attr("data-url"),
                    data: data,
                    beforeSend: function () {
                    },
                    success: function (json) {
                        $(".address_name").html(json.data.address.name);
                        $(".address_phone").html(json.data.address.phone);
                        $(".address_info_all").html(json.data.address.full_address);
                        $(".address_title").attr("code", json.data.address.id)
                        $('.address_choose').removeClass("fadeInRightBig");
                        $('.address_choose').addClass("fadeOutRightBig");
                        $('.address_choose').addClass("dis_n");
                        if ($(".setas_default").val() == 0) {
                            $(".default_btn").css("display", "none");
                        } else {
                            $(".default_btn").css("display", "block");
                        }
                        if ($(".pre_address").hasClass("no_address") == true) {
                            $(".add_address").addClass("dis_ni");
                            $(".edit_address").removeClass("dis_ni");
                        }
                    },
                    error: function (err) {
                        var arr = []
                        var dataobj = err.responseJSON.errors;
                        for (let i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.open({
                            content: arr[0][0],
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    },
                    complete: function () {
                    },
                });
            });
            //获取地址列表
            function getAddressList(url) {
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
                                    html += "<div class='adsItem' code='" + n.id + "'>";
                                    html += "<div class='adsName'>";
                                    html += "<span class='ads_Name'>" + n.name + "</span>";
                                    if (n.is_default == true) {
                                        html += "<span class='defaultAds'>@lang('basic.address.Default')</span>";
                                    } else {
                                        html += "";
                                    }
                                    html += "</div>";
                                    html += "<div class='adsDetail'>";
                                    html += "<span class='adsP'>" + n.phone + "</span>";
                                    html += "<span class='adsD'>" + n.full_address + "</span>";
                                    html += "</div>";
                                    html += "</div>";
                                });
                                $(".ads1Box .adsList").html("");
                                $(".ads1Box .adsList").append(html);
                            } else {
                                //显示新建
                                $(".addAdsBox").show();
                                $(".ads1Box").hide();
                            }
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    },
                    complete: function () {
                    }
                });
            }

            //提交订单
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
                var address_name = $(".address_name").text();
                var address_phone = $(".address_phone").text();
                var address_location = $(".address_info_all").text();
                var url = $(this).attr("data-url");
                var sendWay = getUrlVars("sendWay");

                /*if ($(".pre_address").hasClass("no_address") && $(".pre_address").hasClass("add_address") && $(".pre_address").hasClass("dis_ni") == false) {
                    layer.open({
                        content: "@lang('order.Please fill in the address completely')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return false;
                }*/

                if (address_name == "" || address_phone == "" || address_location == "") {
                    layer.open({
                        content: "@lang('order.Please fill in the address completely')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return false;
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
                    address_id: $(".address_title").attr("code"),
                    name: $('.address_name').text(),
                    phone: $('.address_phone').text(),
                    address: $('.address_info_all').text(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country")
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.open({
                            type: 2,
                            content: '@lang('app.Please wait')',
                            time: false, //取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.mobile_request_url;
                    },
                    error: function (err) {
                        console.log(err);
                        layer.close(loading_animation);
                        layer.open({
                            content: $.parseJSON(err.responseText).errors.currency[0],
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    },
                    complete: function () {
                        layer.close(loading_animation);
                    },
                });
            }

            //第二类创建订单（购物车下单）
            function payment_two(cart_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    cart_ids: cart_ids,
                    address_id: $(".address_title").attr("code"),
                    name: $('.address_name').text(),
                    phone: $('.address_phone').text(),
                    address: $('.address_info_all').text(),
                    remark: $(".remark").val(),
                    currency: $(".currency_selection").find("a.active").attr("country"),
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend: function () {
                        loading_animation = layer.open({
                            type: 2,
                            content: '@lang('app.Please wait')',
                            time: false, //取消自动关闭
                        });
                    },
                    success: function (json) {
                        window.location.href = json.data.mobile_request_url;
                    },
                    error: function (err) {
                        console.log(err);
                        layer.close(loading_animation);
                        layer.open({
                            content: $.parseJSON(err.responseText).errors.currency[0],
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    },
                    complete: function () {
                        layer.close(loading_animation);
                    },
                });
            }
        });
    </script>
@endsection
