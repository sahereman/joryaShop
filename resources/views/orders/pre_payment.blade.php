@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '确认订单' : 'Confirm The Order') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="pre_payment">
        <div class="m-wrapper container">
            <div class="pre_payment_content">
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('order.Confirm Order')</a>
                </p>
                <div class="pre_payment_header" code='{{ $address ? $address->id : ''}}'>
                    <div class="address_info clear">
                        <ul class="left">
                            <li class="clear">
                                <img src="{{ asset('img/sure_ad_local.png') }}">
                                <span>@lang('order.default address')</span>
                            </li>
                            @if($address)
                                <li>
                                    <span class="dis_ni">@lang('order.Contact')：</span>
                                    <span class="address_name">{{ $address->name }}</span>
                                </li>
                                <li>
                                    <span class="dis_ni">@lang('order.Contact information')：</span>
                                    <span class="address_phone">{{ $address->phone }}</span>
                                </li>
                                <li>
                                    <span class="dis_ni">@lang('order.contact address')：</span>
                                    <span class="address_location">{{ $address->full_address }}</span>
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
                            @guest
                                <a class="change_address for-login-show" data-url="{{ route('user_addresses.list_all') }}"
                                   href="javascript:void(0);">@lang('order.Switch address')</a>
                            @else
                                <a class="change_address" data-url="{{ route('user_addresses.list_all') }}"
                                   href="javascript:void(0);">@lang('order.Switch address')</a>
                            @endguest
                            <a class="add_new_address" href="javascript:void(0);">@lang('order.New address')</a>
                        </div>
                    </div>
                </div>
                <div class="pre_payment_main">
                    {{--<p class="main_title">@lang('order.Product list')</p>--}}
                    <div class="cart-header">
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
                    <div class="pre_payment-items">
                        @if($items)
                            @foreach($items as $item)
                                <div class="cart-item">
                                    <div class="cart-item-top">
                                        <div class="cart-header-item cart-item-item">
                                            <div class="cart-item-item-img">
                                                <a class="cur_p" href="avascript:void(0);">
                                                    <img class="lazy" data-src="{{ $item['product']->thumb_url }}">
                                                </a>
                                            </div>
                                            <div class="cart-item-item-content">
                                                <div class="cart-item-name">
                                                    <a class="cur_p" href="javascript:void (0)">
                                                        <span>{{ App::isLocale('zh-CN') ? $item['product']->name_zh : $item['product']->name_en }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-header-item cart-item-qty">
                                            <div class="counter">
                                                <input class="left center count" type="text" readonly value="{{ $item['number'] }}" title="QTY">
                                            </div>
                                        </div>
                                        <div class="cart-header-item cart-item-amount">
                                            <div class="amount-price">
                                                <span>{{ get_global_symbol() }}</span>
                                                <span class="single-price">{{ $item['amount'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($attr_values[$item['sku']->id]))
                                        <div class="cart-item-bottom">
                                            <p class="order-detail-title">ORDER DETAILS <span class="iconfont">&#xe605;</span></p>
                                            <div class="order-details">
                                                 {{--循环的时候分奇偶数 --}}
                                                @foreach($attr_values[$item['sku']->id] as $key => $attr_value)
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
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="pre_payment_footer">
                    <p class="main_title">@lang('order.Currency options')</p>
                    <p class="currency_selection">
                        {{--<a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>--}}
                        <a href="javascript:void(0);" class="active" code="dollar" country="USD">@lang('order.Dollars')</a>
                    </p>
                    <ul>
                        <li class="clear">
                            <span>@lang('order.order note')：</span>
                            <textarea class="remark" placeholder="@lang('order.Optional message')"></textarea>
                        </li>
                        <li>
                            <p>
                                <span>@lang('order.Sum')：</span>
                                <span class="dis_ni RMB_num amount_of_money">&#165; <span>{{ exchange_price($total_amount, 'CNY') }}</span></span>
                                <span class="dollar_num amount_of_money">&#36; <span>{{ $total_amount }}</span></span>
                            </p>
                            <p>
                                <span>@lang('order.freight')：</span>
                                <span class="dis_ni RMB_num amount_of_money">&#165; <span>{{ exchange_price($total_shipping_fee, 'CNY') }}</span></span>
                                <span class="dollar_num amount_of_money">&#36; <span>{{ $total_shipping_fee }}</span></span>
                            </p>
                        </li>
                        <li>
                            <p>
                                <span>@lang('order.Amount payable')：</span>
                                <span class="red dis_ni  RMB_num amount_of_money">&#165; <span>{{ exchange_price($total_fee, 'CNY') }}</span></span>
                                <span class="red dollar_num amount_of_money">&#36; <span>{{ $total_fee }}</span></span>
                                <span>(Saved fee: &#36; {{ $saved_fee }} )</span>
                            </p>
                            <p>
                                <a href="javascript:void(0);" class="payment_btn"
                                   data-url="{{ route('orders.store') }}">@lang('order.payment')</a>
                            </p>
                            @if($address)
                                <p class="address_info">
                                    <span class="address_name address_name_bottom">{{ $address->name }}</span>
                                    <span class="address_phone address_phone_bottom">{{ $address->phone }}</span>
                                </p>
                                <p class="address_info address_location address_location_bottom">{{ $address->full_address }}</p>
                            @else
                                <p class="address_info">
                                    <span class="address_name address_name_bottom"></span>
                                    <span class="address_phone address_phone_bottom"></span>
                                </p>
                                <p class="address_info address_location address_location_bottom"></p>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--新增地址新版-->
    <div id="addNewAddress" class="dis_n address-info-form">
        <form id="creat-form" data-url="{{ route('user_addresses.store_for_ajax') }}">
            <ul class="new_receipt_address">
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
                        <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                        <input name="address" class="user_detailed" placeholder="@lang('basic.address.Detailed_address')">
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
                        <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
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
    </div>

    <!--切换地址信息-->
    <div class="changeAddress dis_n">
        <ul></ul>
    </div>
    {{--<span class="dis_ni" id="countries" data-json="{{ $countries }}"></span>--}}
    <span class="dis_ni" id="provinces" data-json="{{ $provinces }}"></span>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            // 点击展开和收起参数详情
            $('.pre_payment-items').on("click",".order-detail-title",function () {
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
            // 货币种类切换
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
                },
            });

            // 新建收货地址
            $(".add_new_address").on("click", function () {
                layer.open({
                    title: ["The new address", "font-size: 18px;"],
                    type: 1,
                    btn: ['Confirm', 'Cancel'],
                    area: ['900px', '500px'],
                    content: $('#addNewAddress'),
                    yes: function (index, layero) {
                        if ($("#creat-form").valid()) {
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
                                beforeSend: function () {
                                },
                                success: function (json) {
                                    $(".address_name").html(json.data.address.name);
                                    $(".address_phone").html(json.data.address.phone);
                                    $(".address_location").html(json.data.address.full_address);
                                    $(".pre_payment_header").attr("code", json.data.address.id);
                                    $(".new_receipt_address").hide();
                                    layer.close(index);
                                },
                                error: function (err) {
                                    var arr = [];
                                    var dataobj = err.responseJSON.errors;
                                    for (let i in dataobj) {
                                        arr.push(dataobj[i]); //属性
                                    }
                                    layer.msg(arr[0][0]);
                                },
                                complete: function () {
                                },
                            });
                        }
                    }
                });
                // $(".new_receipt_address").show();
            });
            /*$(".new_receipt_address").on("click", ".success", function () {
                if ($(".new_receipt_address .user_name").val() == "" || $(".new_receipt_address .user_tel").val() == "" || $(".new_receipt_address textarea").val() == "") {
                    layer.msg("@lang('order.Please complete the information')");
                    return false
                }
                var data = {
                    _token: "{{ csrf_token() }}",
                    name: $(".new_receipt_address .user_name").val(),
                    phone: $(".new_receipt_address .user_tel").val(),
                    address: $(".new_receipt_address textarea").val(),
                    country: $(".new_receipt_address .user_country").val(),
                    city: $(".new_receipt_address .user_city").val(),
                    province: $(".new_receipt_address .user_province").val(),
                    is_default: "0"
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
                        $(".address_location").html(json.data.address.full_address);
                        $(".pre_payment_header").attr("code", json.data.address.id);
                        $(".new_receipt_address").hide();
                    },
                    error: function (err) {
                        console.log(err);
                        layer.msg($.parseJSON(err.responseText).errors.address[0] || $.parseJSON(err.responseText).errors.name[0] || $.parseJSON(err.responseText).errors.phone[0])
                    },
                    complete: function () {
                    },
                });
            });*/
            // 切换地址
            $(".change_address").on("click", function () {
                if($(this).hasClass("for-login-show")) {
                    layer.alert("Please login first");
                    return
                }
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
                                    html += "<li class='clear' code='"+ n.id +"'>" +
                                            "<p class='clear'><span>@lang('order.Contact')：</span><span class='name'>" + n.name + "</span></p>" +
                                            "<p class='clear'><span>@lang('order.Contact information')：</span><span class='phone'>" + n.phone + "</span></p>" +
                                            "<p class='clear'><span>@lang('order.contact address')：</span><span class='address'>" + n.full_address + "</span></p>" +
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
                                    yes: function () { // 确定
                                        if ($(".changeAddress").find("li.active").length <= 0) {
                                            layer.msg("@lang('order.Please choose the harvest address')");
                                        } else {
                                            $(".address_name").html($(".changeAddress").find("li.active").find(".name").html());
                                            $(".address_phone").html($(".changeAddress").find("li.active").find(".phone").html());
                                            $(".address_location").html($(".changeAddress").find("li.active").find(".address").html());
                                            $(".pre_payment_header").attr("code",$(".changeAddress").find("li.active").attr("code"));
                                            layer.close(changeAdd);
                                        }
                                    },
                                    btn2: function () { // 取消
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
            // 点击选择收货地址
            $(".changeAddress ul").on("click", "li", function () {
                $(".changeAddress ul").find("li").removeClass("active");
                $(this).addClass("active");
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
                            time: false // 取消自动关闭
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

            // 第二类创建订单（购物车下单）
            function payment_two(sku_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_ids: sku_ids,
                    address_id: $(".pre_payment_header").attr("code"),
                    name: $('.address_name_bottom').text(),
                    phone: $('.address_phone_bottom').text(),
                    address: $('.address_location_bottom').text(),
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
                            time: false, // 取消自动关闭
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

            // 省份二级联动
            // 省份假数据
            // 设置二级联动中的选项数组
            /*var countries = Array.from(JSON.parse($('#countries').attr('data-json')));
             countries.unshift('Please select a country');*/
            // console.log(countries);
            // var provinces = JSON.parse($('#provinces').attr('data-json'));
            var country_provinces = JSON.parse($('#provinces').attr('data-json'));
            var provinces = [];
            for (var index in country_provinces) {
                provinces[index] = country_provinces[index];
                // provinces[index].unshift('Please select a state/province/region');
            }
            provinces['0'] = ['Please select a state/province/region'];
            // 获取页面中的选项卡
            var country = document.getElementById('new_address_country');
            var province = document.getElementById('new_address_province');

            // 给第一个选项卡中的option赋值
            /*country.options.length = countries.length;
            country.options[0].text = 'Please select a country';
            country.options[0].value = 0;
            for (var i = 0; i < country.options.length; i++) {
                //key = i + 1;
                country.options[i].text = countries[i];
                country.options[i].value = countries[i];
            }*/

            // 初始化第二个选项卡，默认显示"请选择城市"
            province.options.length = 1;
            // province.options.length = provinces[countries['0']].length;
            province.options[0].text = 'Please select a state/province/region';
            province.options[0].value = 0;
            /*for (var i = 0; i < provinces[countries['0']].length; i++) {
                // key = i + 1;
                province.options[i].text = provinces[countries['0']][i];
                province.options[i].value = provinces[countries['0']][i];
            }*/

            // 通过onchange监视函数，一旦第一个选项卡发生变化，第二个选项卡中的内容也跟着变化
            country.onchange = function () {
                country_name = this.value;
                if (country_name != 0) {
                    province_set = provinces[country_name];
                    province.options.length = province_set.length;
                    // province.options[0].text = 'Please select a state/province/region';
                    // province.options[0].value = 0;
                    for (var j = 0; j < province.options.length; j++) {
                        //key = j + 1;
                        province.options[j].text = provinces[country_name][j];
                        province.options[j].value = provinces[country_name][j];
                    }
                }
            };
        });
    </script>
@endsection
