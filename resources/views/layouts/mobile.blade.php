<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {{--<meta name="keywords" Content="关键词1,关键词2,关键词3,关键词4">--}}
    <meta name="keywords" Content="@yield('keywords', \App\Models\Config::config('keywords'))">
    {{--<meta name="description" content="莱瑞美业欢迎您的光临"/>--}}
    <meta name="description" content="@yield('description', \App\Models\Config::config('description'))"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--下拉加载更多-->
    <!-- UC强制全屏 -->
    <meta name="full-screen" content="yes">
    <!-- QQ强制全屏 -->
    <meta name="x5-fullscreen" content="true">
    {{--<title>@yield('title', 'Web 标题')</title>--}}
    <title>@yield('title', \App\Models\Config::config('title'))</title>
    <link rel="icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    <!-- 样式 -->
    <link href="{{ asset('static_m/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/swiper/css/swiper_m.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static_m/js/raty/jquery.raty.css') }}" rel="stylesheet">
    <link href="{{ asset('static_m/js/animate/animate.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="{{ route_class() }}-page">
    @yield('content')
</div>
<!-- JS 脚本 -->
<script src="{{ asset('static_m/js/app.js') }}"></script>
<script src="{{ asset('js/swiper/js/swiper_m.min.js') }}"></script>
<script src="{{ asset('static_m/js/layer_mobile/layer.js') }}"></script>
<script src="{{ asset('static_m/js/raty/jquery.raty.js') }}"></script>
<script src="{{ asset('static_m/js/jquery.countdown-2.2.0/jquery.countdown.js') }}"></script>
@yield('scriptsAfterJs')
<!--美洽客服系统-->
<script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        j = ei.createElement(q),
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
	//如果需要英文版，可用如下配置
	if(document.getElementsByTagName("html")[0].getAttribute("lang") == "en"){
		_MEIQIA('language','en')	
	}
    // 在这里开启无按钮模式（常规情况下，需要紧跟在美洽嵌入代码之后）
    _MEIQIA('withoutBtn');
    _MEIQIA('init');
    //点击客服
    $(".gShare").click("click",function(){
    	_MEIQIA('showPanel');
    })
</script>
</body>
</html>
