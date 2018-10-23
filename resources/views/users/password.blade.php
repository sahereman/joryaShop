@extends('layouts.app')
@section('title', '个人中心-修改密码')
@section('content')
    @include('common.error')
    <div class="User_psw_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('users.edit', $user->id) }}">修改密码</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>修改密码</p>
                </div>
                <div class="psw_edit_content">
                    <form method="POST" action="{{ route('users.update_password', $user->id) }}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <ul>
                            <li>
                                <span>原密码</span>
                                <input type="password" name="password_original" placeholder="输入原密码" value="" required>
                                @if ($errors->has('password_original'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_original') }}</strong>
                                </span>
                                @endif
                            </li>
                            <li>
                                <span>新密码</span>
                                <input type="password" name="password" value="" placeholder="输入新密码" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </li>
                            <li>
                                <span>确认密码</span>
                                <input type="password" name="password_confirmation" value="" placeholder="再次输入新密码" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
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
