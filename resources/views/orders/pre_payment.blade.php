@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '确认订单' : 'Confirm The Order') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="pre_payment">
        <div class="m-wrapper">
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
                                        <span>{{ App::isLocale('zh-CN') ? $item['product']->name_zh : $item['product']->name_en }}</span>
                                    </div>
                                    <div class="left w150 Specifications_info center">
                                        <span>{{ App::isLocale('zh-CN') ? $item['sku']->parameters_zh : $item['sku']->parameters_en }}</span>
                                    </div>
                                    <div class="left w150 dis_ni center RMB_num">
                                        <span>&#165; {{ exchange_price($item['sku']->price, 'CNY') }}</span>
                                    </div>
                                    <div class="left w150  center dollar_num">
                                        <span>&#36; {{ $item['sku']->price }}</span>
                                    </div>
                                    <div class="left w150 center counter">
                                        <span>{{ $item['number'] }}</span>
                                    </div>
                                    <div class="left w150 s_total dis_ni red center RMB_num">
                                        <span>&#165; {{ exchange_price($item['amount'], 'CNY') }}</span>
                                    </div>
                                    <div class="left w150 s_total red dollar_num center">
                                        <span>&#36; {{ $item['amount'] }}</span>
                                    </div>
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
                            <textarea class="remark" maxlength="150" placeholder="@lang('order.Optional message')"></textarea>
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
                            </p>
                            <p>
                                <a href="javascript:void(0);" class="payment_btn"
                                   data-url="{{ route('orders.store') }}">@lang('order.payment')</a>
                            </p>
                            @if($address)
                                <p class="address_info">
                                    <span class="address_name">{{ $address->name }}</span>
                                    <span class="address_phone">{{ $address->phone }}</span>
                                </p>
                                <p class="address_info address_location">{{ $address->full_address }}</p>
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
    <!--新建收货地址弹出层-->
    {{--<div class="dialog_popup new_receipt_address">
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
                                <p>
                                    <span class="input_name"><i>*</i>Country or region：</span>
                                    <input class="user_country" name="country" type="text"
                                           placeholder="Enter the Country or region">
                                </p>
                            </li>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>City：</span>
                                    <input class="user_city" name="city" type="text"
                                           placeholder="Enter the City">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>State/Province/Region：</span>
                                    <input class="user_province" name="province" type="text"
                                           placeholder="Enter the State/Province/Region">
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
                <a class="success" data-url="{{ route('user_addresses.store_for_ajax') }}">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>--}}

    <!--新增地址新版-->
    <div id="addNewAddress" class="dis_n address-info-form">
        <form id="creat-form" data-url="{{ route('user_addresses.store_for_ajax') }}">
            <ul class="new_receipt_address">
                <li>
                    <p>
                        <span class="input_name"><i>*</i>Country：</span>
                        {{--<input class="user_country" name="country" type="text">--}}
                        <select name="country" id="new_address_country" class="user_country"></select>
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
                        <select name="province" id="new_address_province" class="user_province"></select>
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
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
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
                  title: ["The new address","font-size: 18px;"],
                  type: 1,
                  btn: ['Confirm', 'Cancel'],
                  area: ['900px', '500px'],
                  content: $('#addNewAddress'),
                  yes: function(index, layero){
                    if ($("#creat-form").valid()) {
                        var data = {
                            _token: "{{ csrf_token() }}",
                            name:$(".new_receipt_address .user_name").val(),
                            phone:$(".new_receipt_address .user_tel").val(),
                            address:$(".new_receipt_address .user_detailed").val(),
                            country:$(".new_receipt_address .user_country").val(),
                            city:$(".new_receipt_address .user_city").val(),
                            province:$(".new_receipt_address .user_province").val(),
                            zip:$(".new_receipt_address .user_zip").val(),
                            is_default: "0"
                        };
                        $.ajax({
                            type:"post",
                            url:$("#creat-form").attr("data-url"),
                            data: data,
                            beforeSend: function () {},
                            success: function (json) {
                                $(".address_name").html(json.data.address.name);
                                $(".address_phone").html(json.data.address.phone);
                                $(".address_location").html(json.data.address.full_address);
                                $(".pre_payment_header").attr("code",json.data.address.id);
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
//              $(".new_receipt_address").show();
            });
//          $(".new_receipt_address").on("click", ".success", function () {
//              if($(".new_receipt_address .user_name").val()==""||$(".new_receipt_address .user_tel").val()==""||$(".new_receipt_address textarea").val()==""){
//                  layer.msg("@lang('order.Please complete the information')");
//                  return false
//              }
//              var data = {
//                  _token: "{{ csrf_token() }}",
//                  name:$(".new_receipt_address .user_name").val(),
//                  phone:$(".new_receipt_address .user_tel").val(),
//                  address:$(".new_receipt_address textarea").val(),
//                  country:$(".new_receipt_address .user_country").val(),
//                  city:$(".new_receipt_address .user_city").val(),
//                  province:$(".new_receipt_address .user_province").val(),
//                  is_default: "0"
//              };
//              $.ajax({
//                  type:"post",
//                  url:$(this).attr("data-url"),
//                  data: data,
//                  beforeSend: function () {},
//                  success: function (json) {
//                      $(".address_name").html(json.data.address.name);
//                      $(".address_phone").html(json.data.address.phone);
//                      $(".address_location").html(json.data.address.full_address);
//                      $(".pre_payment_header").attr("code",json.data.address.id);
//                      $(".new_receipt_address").hide();
//                  },
//                  error: function (err) {
//                      console.log(err);
//                      layer.msg($.parseJSON(err.responseText).errors.address[0]||$.parseJSON(err.responseText).errors.name[0]||$.parseJSON(err.responseText).errors.phone[0])
//                  },
//                  complete: function () {
//                  },
//              });
//          });
            // 切换地址
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
                var address_name = $(".address_name").html();
                var address_phone = $(".address_phone").html();
                var address_location = $(".address_location").html();
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
                            var cart_ids = getUrlVars("cart_ids");
                            payment_two(cart_ids, url);
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
                    name: $('.address_name').text(),
                    phone: $('.address_phone').text(),
                    address: $('.address_location').text(),
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
            function payment_two(cart_ids, url) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    cart_ids: cart_ids,
                    address_id: $(".pre_payment_header").attr("code"),
                    name: $('.address_name').text(),
                    phone: $('.address_phone').text(),
                    address: $('.address_location').text(),
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
            var province_array = ['请选择省份', '北京市', '上海市', '天津市', '河北省', '山西省', '内蒙古省', '辽宁省', '吉林省', '黑龙江省'];
            var city_array = [
                ['请选择城市'],
                ["东城区", "西城区", "崇文区", "宣武区", "朝阳区", "丰台区", "石景山区", "海淀区", "门头沟区", "房山区", "通州区", "顺义区", "昌平区", "大兴区", "怀柔区", "平谷区", "密云县", "延庆县"],
                ["黄浦区", "卢湾区", "徐汇区", "长宁区", "静安区", "普陀区", "虹口区", "杨浦区", "闵行区", "宝山区", "嘉定区", "浦东新区", "金山区", "松江区", "青浦区", "南汇区", "奉贤区", "崇明县"],
                ["和平区", "河东区", "河西区", "南开区", "河北区", "红桥区", "塘沽区", "汉沽区", "大港区", "东丽区", "西青区", "津南区", "北辰区", "武清区", "宝坻区", "宁河县", "静海县", "蓟县"],
                ["石家庄市", "张家口市", "承德市", "秦皇岛市", "唐山市", "廊坊市", "保定市", "衡水市", "沧州市", "邢台市", "邯郸市"],
                ["太原市", "朔州市", "大同市", "阳泉市", "长治市", "晋城市", "忻州市", "晋中市", "临汾市", "吕梁市", "运城市"],
                ["呼和浩特市", "包头市", "乌海市", "赤峰市", "通辽市", "呼伦贝尔市", "鄂尔多斯市", "乌兰察布市", "巴彦淖尔市", "兴安盟", "锡林郭勒盟", "阿拉善盟"],
                ["沈阳市", "朝阳市", "阜新市", "铁岭市", "抚顺市", "本溪市", "辽阳市", "鞍山市", "丹东市", "大连市", "营口市", "盘锦市", "锦州市", "葫芦岛市"],
                ["长春市", "白城市", "松原市", "吉林市", "四平市", "辽源市", "通化市", "白山市", "延边州"],
                ["哈尔滨市", "齐齐哈尔市", "七台河市", "黑河市", "大庆市", "鹤岗市", "伊春市", "佳木斯市", "双鸭山市", "鸡西市", "牡丹江市", "绥化市", "大兴安岭地区"]
            ];
            // 获取页面中的选项卡
            var province = document.getElementById('new_address_country');
            var city = document.getElementById('new_address_province');

            // 给第一个选项卡中的option赋值
            province.options.length = province_array.length;
            for (var i = 0; i < province.options.length; i++) {
                province.options[i].text = province_array[i];
                province.options[i].value = province_array[i];
            }

            // 初始化第二个选项卡，默认显示"请选择城市"
            city.options.length = 1;
            city.options[0].text = city_array[0][0];
            city.options[0].value = city_array[0][0];

            // 通过onchange监视函数，一旦第一个选项卡发生变化，第二个选项卡中的内容也跟着变化
            province.onchange = function () {
                city.options.length = city_array[this.selectedIndex].length;
                for (var j = 0; j < city.options.length; j++) {
                    city.options[j].text = city_array[this.selectedIndex][j];
                    city.options[j].value = city_array[this.selectedIndex][j];
                }
            }
        });
    </script>
@endsection
