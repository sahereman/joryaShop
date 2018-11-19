@extends('layouts.app')
@section('title', '个人中心-修改绑定邮箱')
@section('content')
    <div class="User_psw_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('users.edit', $user->id) }}">@lang('basic.users.Modify the bound mailbox')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>@lang('basic.users.Modify the bound mailbox')</p>
                </div>
                <div class="psw_edit_content">
                    <form method="POST" action="{{ route('users.update_password', $user->id) }}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <ul>
                            <li>
                                <span>@lang('basic.users.email_address')</span>
                                <input type="email" name="password_original" placeholder="输入新邮箱" value="" required>
                                @if ($errors->has('password_original'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_original') }}</strong>
                                </span>
                                @endif
                            </li>
                            <li>
                                <span>新邮箱账号</span>
                                <input type="email" name="password" value="" placeholder="输入新邮箱账号" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </li>
                        </ul>
                        <button type="submit">完成</button>
                    </form>
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
