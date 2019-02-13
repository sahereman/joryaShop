<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    {{--<meta name="keywords" Content="关键词1,关键词2,关键词3,关键词4">--}}
    <meta name="keywords" Content="@yield('keywords', \App\Models\Config::config('keywords'))">
    {{--<meta name="description" content="莱瑞美业欢迎您的光临"/>--}}
    <meta name="description" content="@yield('description', \App\Models\Config::config('description'))"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<title>@yield('title', 'Web 标题')</title>--}}
    <title>@yield('title', \App\Models\Config::config('title'))</title>
    <link rel="icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('img/favicon.jpg') }}" type="image/x-icon"/>
    <!-- 样式 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/swiper/css/swiper.css') }}" rel="stylesheet">
    <link href="{{ asset('js/shareJS/css/share.min.css') }}" rel="stylesheet">
    @if(App::isLocale('en'))
        <style>
            body {
                font-family: helvetica !important;
            }
        </style>
    @endif
</head>
<body>
<div id="app" class="{{ route_class() }}-page">
    @include('layouts._header')
    @yield('content')
    @include('layouts._footer')
</div>
<!-- JS 脚本 -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/layer/layer.js') }}"></script>
<script src="{{ asset('js/shareJS/js/social-share.min.js') }}"></script>
@yield('scriptsAfterJs')
<!--美洽客服系统-->
<script type='text/javascript'>
	function changeFunction(servability) {
        if (servability) {
        	if($("html").attr("lang") == "en"){
        		$("#MEIQIA-BTN-TEXT").text("Customer Service");	
        	}else {
        		$("#MEIQIA-BTN-TEXT").text("在线客服");
        	}
        } else {
        	if($("html").attr("lang") == "en"){
        		$("#MEIQIA-BTN-TEXT").text("Customer Service");	
        	}else {
        		$("#MEIQIA-BTN-TEXT").text("在线客服");
        	}
        }
    }
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
	if($("html").attr("lang") == "en"){
		_MEIQIA('language','en')	
	}
    // 初始化成功时的回调
    _MEIQIA('allSet', changeFunction);
    // 在这里开启无按钮模式（常规情况下，需要紧跟在美洽嵌入代码之后）
//  _MEIQIA('withoutBtn');
    _MEIQIA('init');
    $(".CustomerClickBtn").on("click",function(){
    	_MEIQIA('showPanel');
    })
</script>
</body>
</html>
