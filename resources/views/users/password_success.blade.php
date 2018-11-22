@extends('layouts.app')

@if(App::isLocale('en'))
@section('title', 'Personal Center-Modify password')
@else
@section('title', '个人中心-修改密码')
@endif
@section('content')
    <div class="User_psw_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('users.edit',['user' => $user->id]) }}">@lang('basic.users.Change_Password')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>@lang('basic.users.Change_Password')</p>
                </div>
                <div class="psw_success_content">
                    <div class="success_content">
                        <img src="{{ asset('img/reset_success.png') }}">
                        <p>@lang('basic.users.The new password has been set successfully')</p>
                        <a href="{{ route('root') }}">@lang('basic.users.Return to the home page')</a>
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
