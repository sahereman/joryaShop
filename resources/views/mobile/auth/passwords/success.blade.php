@extends('layouts.mobile')
@section('title', '设置成功')
@section('content')
    <div class="regMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <div class="sucBox">
            <img src="{{ asset('static_m/img/icon_Success.png') }}"/>
            <div>新密码已设置成功</div>
            <button type="submit" class="subBtn">
                <a href="{{route('mobile.login.show')}}">登录</a>
            </button>
        </div>
    </div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection




