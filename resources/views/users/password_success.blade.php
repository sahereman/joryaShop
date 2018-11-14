@extends('layouts.app')
@section('title', '个人中心-修改密码')
@section('content')
    <div class="User_psw_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('users.edit',['user' => $user->id]) }}">修改密码</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>修改密码</p>
                </div>
                <div class="psw_success_content">
                    <div class="success_content">
                        <img src="{{ asset('img/reset_success.png') }}">
                        <p>新密码已设置成功</p>
                        <a href="{{ route('root') }}">返回首页</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".change_psw").addClass("active");
            $('.user_Avatar img').on('click', function () {
                $('#upload_head').click();
            });
            $(".photograph").on('click', function () {
                $('#upload_head').click();
            });
        });
    </script>
@endsection
