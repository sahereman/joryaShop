<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="keywords" Content="@yield('keywords', \App\Models\Config::config('keywords'))">
    <meta name="description" content="@yield('description', \App\Models\Config::config('description'))"/>
    {{--Share Image--}}
    <meta property="og:image" content="@yield('og:image', '')" />
    <meta property="og:image:width" content="680" />
    <meta property="og:image:height" content="800" />
    <meta name="twitter:image" content="@yield('twitter:image', '')" />
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', \App\Models\Config::config('title'))</title>
    <link rel="icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    {{--样式--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/swiper/css/swiper.css') }}" rel="stylesheet">
    <link href="{{ asset('js/shareJS/css/share.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/slick/slick.css') }}" rel="stylesheet" type="text/css" />
    {{--@if(App::isLocale('en'))--}}
    <link rel="stylesheet" href="{{ asset('js/lord/colorbox.css') }}">
    <link rel="stylesheet" href="{{ asset('js/lord/cloudzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('js/lord/thumbelina.css') }}">
</head>
<body>
<div id="app" class="{{ route_class() }}-page"
     data-global-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
     data-global-currency="{{ get_global_currency() }}" data-global-symbol="{{ get_global_symbol() }}"
     {{--data-currencies="{{ collect(\App\Models\ExchangeRate::$currencyMap)->toJson() }}"--}}
     data-symbols="{{ collect(\App\Models\ExchangeRate::$symbolMap)->toJson() }}"
     data-exchange-rates="{{ \App\Models\ExchangeRate::exchangeRates()->keyBy('currency')->toJson() }}">
    @include('layouts._header')
    @yield('content')
    @include('layouts._footer')
</div>
<!-- JS 脚本 -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/layer/layer.js') }}"></script>
<script src="{{ asset('js/shareJS/js/social-share.min.js') }}"></script>
<!--<script type="text/javascript" src="http://skype.tom.com/script/skypeCheck.js"></script>-->
@yield('scriptsAfterJs')

<script type='text/javascript'>
    /*货币汇率转换相关*/
    var app_node = $('div#app');
    var global_locale = String(app_node.attr('data-global-locale'));
    var global_currency = String(app_node.attr('data-global-currency'));
    var global_symbol = String(app_node.attr('data-global-symbol'));
    // var currencies = JSON.parse(app_node.attr('data-currencies'));
    var symbols = JSON.parse(app_node.attr('data-symbols'));
    var exchange_rates = JSON.parse(app_node.attr('data-exchange-rates'));

    Math.imul = Math.imul || function (a, b) {
        var ah = (a >>> 16) & 0xffff;
        var al = a & 0xffff;
        var bh = (b >>> 16) & 0xffff;
        var bl = b & 0xffff;
        // the shift by 0 fixes the sign on the high part
        // the final |0 converts the unsigned value into a signed value
        return ((al * bl) + (((ah * bl + al * bh) << 16) >>> 0) | 0);
    };

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
        // number = number.toString();
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

    function exchange_price(price, to_currency, from_currency) {
        if (to_currency && to_currency !== 'USD' && exchange_rates[to_currency]) {
            var to_rate = exchange_rates[to_currency].rate;
            price = float_multiply_by_100(price);
            to_rate = float_multiply_by_100(to_rate);
            price = js_number_format(Math.imul(price, to_rate) / 10000);
        }
        if (from_currency && from_currency !== 'USD' && exchange_rates[from_currency]) {
            var from_rate = exchange_rates[from_currency].rate;
            price = float_multiply_by_100(price);
            from_rate = float_multiply_by_100(from_rate);
            price = js_number_format(price / from_rate);
        }
        return price;
        // 以下方法实现js的number_format功能虽然简单，但是存在数字四舍五入不准确的问题，结果不可预知：
        // (Math.ceil(number) / 100).toFixed(2)
        // js_number_format(Math.ceil(number) / 100)
    }

    function get_current_price(price_in_usd) {
        return exchange_price(price_in_usd, global_currency);
    }

    function get_symbol_by_currency(currency) {
        if (currency && currency !== 'USD' && symbols[currency]) {
            return symbols[currency];
        }
        return '&#36;';
    }
</script>

<!--美洽客服系统-->
<script type='text/javascript'>
    function changeFunction(servability) {
        if (servability) {
            if ($("html").attr("lang") == "en") {
                $("#MEIQIA-BTN-TEXT").text("Customer Service");
            } else {
                $("#MEIQIA-BTN-TEXT").text("在线客服");
            }
        } else {
            if ($("html").attr("lang") == "en") {
                $("#MEIQIA-BTN-TEXT").text("Customer Service");
            } else {
                $("#MEIQIA-BTN-TEXT").text("在线客服");
            }
        }
    }
    (function (m, ei, q, i, a, j, s) {
        m[i] = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
        j = ei.createElement(q);
        s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = 'https://static.meiqia.com/dist/meiqia.js?_=t';
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '_MEIQIA');
    _MEIQIA('entId', 140212);

    // 在这里开启手动模式（必须紧跟美洽的嵌入代码）
    _MEIQIA('manualInit');
</script>
<script>
    // 如果需要英文版，可用如下配置
    if ($("html").attr("lang") == "en") {
        _MEIQIA('language', 'en')
    }
    // 初始化成功时的回调
    _MEIQIA('allSet', changeFunction);
    // 在这里开启无按钮模式（常规情况下，需要紧跟在美洽嵌入代码之后）
    // _MEIQIA('withoutBtn');
    _MEIQIA('init');
    $(".CustomerClickBtn").on("click", function () {
        _MEIQIA('showPanel');
    })
</script>
</body>
</html>
