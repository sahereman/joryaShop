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
</body>
</html>
