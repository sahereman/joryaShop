@extends('layouts.app')
@if(App::isLocale('en'))
@section('title', 'Personal Center-Modify password')
@else
@section('title', '个人中心-修改密码')
@endif
@section('content')
    @include('common.error')
    <div class="User_psw_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('users.edit', $user->id) }}">@lang('basic.users.Change_Password')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>@lang('basic.users.Change_Password')</p>
                </div>
                <div class="psw_edit_content">
                    <form method="POST" action="{{ route('users.update_password', $user->id) }}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <ul>
                            <li>
                                <span>@lang('basic.users.old_password')</span>
                                <input type="password" name="password_original" placeholder="@lang('basic.users.Enter_original_password')" value="" required>
                                @if ($errors->has('password_original'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_original') }}</strong>
                                </span>
                                @endif
                            </li>
                            <li>
                                <span>@lang('basic.users.new_password')</span>
                                <input type="password" name="password" value="" placeholder="@lang('basic.users.Enter_new_password')" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </li>
                            <li>
                                <span>@lang('basic.users.confirma_password')</span>
                                <input type="password" name="password_confirmation" value="" placeholder="@lang('basic.users.Enter_new_password_again')" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                    <img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </li>
                        </ul>
                        <button type="submit">@lang('basic.users.submit')</button>
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
