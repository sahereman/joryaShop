<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Web 标题')</title>


    <!-- 样式 -->
    <link href="{{ asset('static_m/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/swiper/css/swiper_m.min.css') }}" rel="stylesheet">

</head>
<body>
<div id="app" class="{{ route_class() }}-page">
    @yield('content')

</div>
<!-- JS 脚本 -->
<script src="{{ asset('static_m/js/app.js') }}"></script>
<script src="{{ asset('js/swiper/js/swiper_m.min.js') }}"></script>


@yield('scriptsAfterJs')
</body>
</html>
