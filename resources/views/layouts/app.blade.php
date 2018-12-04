<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="卓雅美业欢迎您的光临"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Web 标题')</title>
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
