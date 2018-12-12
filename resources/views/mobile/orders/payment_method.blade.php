@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Choosing a Payment method' : '选择支付方式')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('app.Confirm the Order')</span>
    </div>
    <div class="pre_payment">
        <div class="pre_paymentCon">
            <div class="pre_address edit_address" data-url="{{ route('user_addresses.list_all') }}">
                <div>
                    <p class="address_title">
                        <span class="address_name">{{ $order->user_info['name'] }}</span>
                        <span class="address_phone">{{ $order->user_info['phone'] }}</span>
                    </p>
                    <p class="address_info">
                        <span class="address_info_all">{{ $order->user_info['address'] }}</span>
                    </p>
                </div>
            </div>
            <div class="pre_products">
                <ul>
                    @foreach($order->snapshot as $key => $order_item)
                        @if($key > 2)
                            @break
                        @endif
                        <li>
                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                            <span>&#215; {{ $order_item['number'] }}</span>
                        </li>
                    @endforeach
                </ul>
                <!--显示商品总数量-->
                @if(\Illuminate\Support\Facades\App::isLocale('en'))
                    <span class="pre_products_num">
                        {{ count($order->snapshot) .' '. (count($order->snapshot) > 1 ? \Illuminate\Support\Str::plural(__('order.Commodity')) : __('order.Commodity')) .' '. __('order.in total') }}
                    </span>
                @else
                    <span class="pre_products_num">
                        {{ __('order.in total') .' '. count($order->snapshot) .' '. __('order.Commodity') }}
                    </span>
                @endif
            </div>
            <div class="pre_amount">
                <p>
                    <span>@lang('order.Sum')</span>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_amount }}</span>
                </p>
                <p>
                    <span>@lang('order.freight')</span>
                    <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ $order->total_shipping_fee }}</span>
                </p>
            </div>
            <div class="pre_currency">
                <p class="main_title">@lang('order.Currency options')</p>
                <p class="currency_selection">
                    @if($order->currency == 'CNY')
                        <a href="javascript:void(0);" class="active" code="RMB" country="CNY">@lang('order.RMB')</a>
                    @else
                        <a href="javascript:void(0);" code="dollar" country="USD">@lang('order.Dollars')</a>
                    @endif
                </p>
            </div>
            <div class="pre_note">
                <p>@lang('order.order note')</p>
                <textarea placeholder="@lang('order.Optional message')" maxlength="50" readonly
                          value="{{ $order->remark }}"></textarea>
            </div>
        </div>
        <div class="pre_paymentTotal">
            <span class="amount_of_money cost_of_total">{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
            <a href="javascript:void(0);" class="Topayment_btn">@lang('basic.orders.To pay')</a>
        </div>
    </div>
    <!--选择支付方式弹窗-->
    <div class="payment_method_choose animated dis_n">
        <div class="mask"></div>
        <div class="pay_choose">
            <p class="pay_choose_title">
                <span class="close_btn_payChoose"></span>
                <span>@lang('basic.orders.payment')</span>
                <span class="count_down"
                      seconds_to_close_order="{{ (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) > 0 ? (strtotime($order->created_at) + \App\Models\Order::getSecondsToCloseOrder() - time()) : 0 }}"></span>
            </p>
            <p class="pay_choose_order">
                <span>@lang('basic.users.Order_number')：</span>
                <span>{{ $order->order_sn }}</span>
            </p>
            <ul>
                @if($order->currency == 'CNY')
                    @if(!is_wechat_browser())
                        <li>
                            <input type="radio" name="payMethod" value="1" id="alipay"
                                   data-href="{{ route('mobile.payments.alipay.wap', ['order' => $order->id]) }}"
                                   checked>
                            <span class="bagLbl"></span>
                            <label class="cur_p clear" for="alipay">
                                <img src="{{ asset('static_m/img/icon_alipay_small.png') }}">
                            </label>
                        </li>
                        <li>
                            <input type="radio" name="payMethod" value="2" id="wechat_wap"
                                   data-href="{{ route('mobile.payments.wechat.wap', ['order' => $order->id]) }}">
                            <span class="bagLbl"></span>
                            <label class="cur_p clear" for="wechat_wap">
                                <img src="{{ asset('static_m/img/icon_wechat_small.png') }}">
                            </label>
                        </li>
                    @else
                        <li>
                            <input type="radio" name="payMethod" value="3" id="wechat_mp"
                                   data-href="{{ route('mobile.payments.wechat.mp', ['order' => $order->id]) }}">
                            <span class="bagLbl"></span>
                            <label class="cur_p clear" for="wechat_mp">
                                <img src="{{ asset('static_m/img/icon_wechat_small.png') }}">
                            </label>
                        </li>
                    @endif
                @else
                    <li>
                        <input type="radio" name="payMethod" value="4" id="paypal"
                               data-href="{{ route('mobile.payments.paypal.create', ['order' => $order->id]) }}">
                        <label class="cur_p clear" for="paypal">
                            <img src="{{ asset('static_m/img/icon_paypal_small.png') }}">
                        </label>
                    </li>
                @endif
            </ul>
            <p class="need_to_pay">
                <span>@lang('basic.orders.Pay')</span>
                <span class="total_num_toPay">{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }} {{ bcadd($order->total_amount, $order->total_shipping_fee, 2) }}</span>
            </p>
            <p class="rel_topayment">
                <a href="javascript:void(0);">@lang('basic.orders.Pay')</a>
            </p>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            //付款倒计时
            var seconds_to_close_order = $(".count_down").attr('seconds_to_close_order');
            timeCount("count_down", seconds_to_close_order, 1);
            //点击付款
            $(".Topayment_btn").on("click", function () {
                $(".payment_method_choose").removeClass('dis_n');
            });
            $(".close_btn_payChoose").on("click", function () {
                $(".payment_method_choose").addClass('dis_n');
            });
            //点击支付
            $(".rel_topayment").on("click", function () {
                var is_choosed = $(".payment_method_choose").find("input[name='payMethod']:checked");
                if (is_choosed.length == 1) {
                    var way_choosed = $(".payment_method_choose").find("input[name='payMethod']:checked").val();
                    var location_href = $(".payment_method_choose").find("input[name='payMethod']:checked").attr("data-href");
                    var url = location_href;
                    switch (way_choosed) {
                        case "1":          //支付宝-wap
                            window.location.href = location_href;
                            break;
                        case "2":          //微信-wap[H5页面支付]
                            window.location.href = location_href;
                            break;
                        case "3":          //微信-mp[公众号支付]
                            /* TODO ... ajax */
                            /*Sample response*/
                            /*{
                             "appId":"wx0b3f800e268b1e85",
                             "timeStamp":"1543801042",
                             "nonceStr":"ZfYKA9xrF7tZw6eX",
                             "package":"prepay_id=wx03093723016488f08695c0f62974033491",
                             "signType":"MD5",
                             "paySign":"3B74D2147CB604894208B3838C09D4EE"
                             }*/
                            url = location_href;
                            $.ajax({
                                type: "GET",
                                url: url,
                                data: {},
                                success: function (data) {
                                    WeixinJSBridge.invoke(
                                            'getBrandWCPayRequest', {
                                                "appId": data.appId, //公众号名称，由商户传入
                                                "timeStamp": data.timeStamp, //时间戳，自1970年以来的秒数
                                                "nonceStr": data.nonceStr, //随机串
                                                "package": data.package,
                                                "signType": data.signType, //微信签名方式：
                                                "paySign": data.paySign, //微信签名
                                            },
                                            function (res) {
                                                if (res.err_msg == "get_brand_wcpay_request:ok") {
                                                    window.location.reload();
                                                }
                                            });
                                },
                                error: function (e) {
                                    alert("@lang('order.Order payment failed')");
                                }
                            });
                            break;
                        case "4":          //paypal
                            url = location_href;
                            $.ajax({
                                type: "get",
                                url: url,
                                success: function (json) {
                                    if (json.code == 200) {
                                        window.location.href = json.data.redirect_url;
                                    } else {
                                        layer.open({
                                            content: json.message,
                                            skin: 'msg',
                                            time: 2, //2秒后自动关闭
                                        });
                                    }
                                }
                            });
                            break;
                        default :
                            layer.open({
                                content: "@lang('order.Please select the payment method')",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                            break;
                    }
                } else {
                    layer.open({
                        content: "@lang('order.Please select the payment method')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                }
            });
            //倒计时方法封装
            function timeCount(remain_id, totalS, type) {
                function _fresh() {
                    totalS--;
                    if (totalS > 0) {
                        var _day = parseInt((totalS / 3600) % 24 / 24);
                        var _hour = parseInt((totalS / 3600) % 24);
                        var _minute = parseInt((totalS / 60) % 60);
                        var _second = parseInt(totalS % 60);
                        if (_day < 10) {
                            _day = "0" + _day;
                        }
                        if (_hour < 10) {
                            _hour = "0" + _hour;
                        }
                        if (_minute < 10) {
                            _minute = "0" + _minute;
                        }
                        if (_second < 10) {
                            _second = "0" + _second;
                        }
                        if (type == '1') {
                            $('.' + remain_id).html(_hour + ':' + _minute + ':' + _second);
                        }
                    } else {
                        $('.' + remain_id).html("@lang('order.payment')");
                    }
                }

                _fresh();
                var sh = setInterval(_fresh, 1000);
            }
        });
    </script>
@endsection
