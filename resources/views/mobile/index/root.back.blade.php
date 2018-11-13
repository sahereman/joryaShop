@extends('layouts.mobile')

@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <h1>首页</h1>



    @guest
        <a href="{{route('mobile.login.show')}}">登录</a>

        <a href="{{ route('mobile.register.show') }}">注册</a>

        <a href="{{ route('mobile.reset.sms.show') }}">忘记密码?</a>


    @else
        <a href="{{ route('mobile.users.home') }}">用户中心</a>


        <h2>用户名: {{Auth::user()->name}}</h2>

        <a href="{{ route('mobile.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
        <form id="logout-form" action="{{ route('mobile.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endguest



    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection