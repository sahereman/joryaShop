@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Confirm the Order' : '确认订单')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('app.Confirm the Order')</span>
    </div>
    <div class="pre_payment">
        <div class="pre_paymentCon">
            @if($address)
                <div class="pre_address edit_address" data-url="{{ route('user_addresses.list_all') }}">
                    <div>
                        <p class="address_title">
                            <span class="address_name">{{ $address->name }}</span>
                            <span class="address_phone">{{ $address->phone }}</span>
                        </p>
                        <p class="address_info">
                            @if($address->is_default == 1)
                                <span class="default_btn">@lang('basic.address.Default')</span>
                            @endif
                            <span class="address_info_all">{{ $address->address }}</span>
                        </p>
                    </div>
                    <img src="{{ asset('static_m/img/icon_more.png') }}">
                </div>
            @else
                <div class="pre_address no_address" data-url="{{ route('user_addresses.list_all') }}">
                    <div>
                        <img src="{{ asset('static_m/img/icon_pre_address.png') }}">
                        <span class="no_address">@lang('basic.address.Add a shipping address')</span>
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
                @if(\Illuminate\Support\Facades\App::isLocale('en'))
                    <span class="pre_products_num">
                        {{ count($items) .' '. (count($items) > 1 ? \Illuminate\Support\Str::plural(__('order.Commodity')) : __('order.Commodity')) .' '. __('order.in total') }}
                    </span>
                @else
                    <span class="pre_products_num">
                        {{ __('order.in total') .' '. count($items) .' '. __('order.Commodity') }}
                    </span>
                @endif
                <img src="{{ asset('static_m/img/icon_more.png') }}">
            </div>
            <div class="pre_amount">
                <p>
                    <span>@lang('order.Sum')</span>
                    <span class="RMB_num">&#165; {{ $total_amount }}</span>
                    <span class="dis_ni dollar_num">&#36; {{ $total_amount_en }}</span>
                </p>
                <p>
                    <span>@lang('order.freight')</span>
                    <span class="RMB_num amount_of_money">&#165; <span>{{ $total_shipping_fee }}</span></span>
                    <span class="dis_ni dollar_num amount_of_money">&#36; <span>{{ $total_shipping_fee_en }}</span></span>
                </p>
            </div>
            <div class="pre_currency">
                <p class="main_title">@lang('order.Currency options')</p>
                <p class="currency_selection">
                    <a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>
                    <a href="javascript:void(0);" code="dollar" country="USD">@lang('order.Dollars')</a>
                </p>
            </div>
            <div class="pre_note">
                <p>@lang('order.order note')</p>
                <textarea class="remark" placeholder="@lang('order.Optional message')" maxlength="50"></textarea>
            </div>
        </div>
        <div class="pre_paymentTotal">
            <span class="RMB_num amount_of_money">&#165; <span>{{ $total_fee }}</span></span>
            <span class="dis_ni dollar_num amount_of_money">&#36; <span>{{ $total_fee_en }}</span></span>
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
        <div class="adsBox lay_content">
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
                <div class="addAdsItem" style="border:none;">
                    <label class="must">@lang('basic.address.Detailed address')</label>
                    <input type="text" name="address" id="new_address_info" value=""
                           placeholder="@lang('basic.address.Detailed_address')"/>
                </div>
                <button class="doneBtn save_new_address">@lang('basic.users.Save')</button>
            </div>
            <div class="defaultBox">
                <label>@lang('basic.address.Set as default address')</label>
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
                                {{ App::isLocale('en') ? $item['product']->name_en : $item['product']->name_zh }}
                            </div>
                            <div class="goodsSpec">
                                <span>{{ App::isLocale('en') ? $item['sku']->name_en : $item['sku']->name_zh }}</span>
                            </div>
                            <div class="goodsPri">
                                <div>
                                    <span class="realPri RMB_num">&#165; {{ $item['sku']->price }}</span>
                                    <span class="realPri dis_n dollar_num">&#36; {{ $item['sku']->price_en }}</span>
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
            $(".lay_content").css("min-height", document.body.clientHeight);
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
                $('.address_choose').removeClass("dis_n");
                $('.address_choose').removeClass("fadeOutRightBig");
                $('.address_choose').addClass("fadeInRightBig");
                if ($(this).hasClass("no_address")) {
                    $(".addAdsBox").show();
                    $(".adsBox").hide();
                } else {
                    $(".addAdsBox").hide();
                    $(".adsBox").show();
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
            //点击切换默认地址
            $(".switchBtn").on("click", function () {
                if ($(this).attr("src") == "{{ asset('static_m/img/icon_OFF.png') }}") {
                    $(this).attr("src", "{{ asset('static_m/img/icon_ON.png') }}");
                } else {
                    $(this).attr("src", "{{ asset('static_m/img/icon_OFF.png') }}");
                }
            });
            //选中地址显示在界面上
            $(".adsList").on("click", '.adsItem', function () {
                console.log($(this).find(".defaultAds").length)
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
            });
            //点击地址中的新建地址
            $(".creat_address_btn").on("click", function () {
                $(".addAdsBox").show();
                $(".adsBox").hide();
            });
            //点击保存
            $(".save_new_address").on("click", function () {
                $(".address_name").html($("#new_address_name").val());
                $(".address_phone").html($("#new_address_phone").val());
                $(".address_info_all").html($("#new_address_info").val());
                $('.address_choose').removeClass("fadeInRightBig");
                $('.address_choose').addClass("fadeOutRightBig");
                $('.address_choose').addClass("dis_n");
            });
            //获取地址列表
            function getAddressList(url) {
                // console.log(url);
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
                                    html += "<div class='adsItem'>";
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
                                    html += "<span class='adsD'>" + n.address + "</span>";
                                    html += "</div>";
                                    html += "</div>";
                                });
                                $(".adsBox .adsList").html("");
                                $(".adsBox .adsList").append(html);
                            } else {
                                //显示新建
                                $(".addAdsBox").show();
                                $(".adsBox").hide();
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
                var address_name = $(".address_name").html();
                var address_phone = $(".address_phone").html();
                var address_location = $(".address_info_all").html();
                var url = $(this).attr("data-url");
                var sendWay = getUrlVars("sendWay");
                if (address_name == "" || address_phone == "" || address_location == "") {
                    layer.open({
                        content: "@lang('order.Please fill in the address completely')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
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
                    address: $(".address_info_all").html(),
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
                        // console.log(json);
                        window.location.href = json.data.mobile_request_url;
                    },
                    error: function (err) {
                        console.log(err);
                        layer.open({
                            content: $.parseJSON(err.responseText).errors.currency[0],
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    },
                    complete: function () {
                    },
                });
            }

            //第二类创建订单（购物车下单）
            function payment_two(cart_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    cart_ids: cart_ids,
                    name: $(".address_name").html(),
                    phone: $(".address_phone").html(),
                    address: $(".address_info_all").html(),
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
                        layer.open({
                            content: $.parseJSON(err.responseText).errors.currency[0],
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                    },
                    complete: function () {
                    },
                });
            }
        });
    </script>
@endsection
