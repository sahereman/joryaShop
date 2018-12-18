@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Modify password' : '修改密码')
@section('content')
    <div class="headerBar">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
            <span>@lang('basic.users.Change_Password')</span>
        @endif
    </div>
    <!--右侧内容-->
    <div class="psdedit_success">
        <div class="psw_success_content">
            <div class="success_content">
                <img src="{{ asset('static_m/img/reset_success.png') }}">
                <p>@lang('basic.users.The new password has been set successfully')</p>
                <a href="{{ route('mobile.root') }}">@lang('basic.users.Return to the home page')</a>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        });
    </script>
@endsection
