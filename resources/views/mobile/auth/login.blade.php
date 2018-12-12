@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Sign in' : '登录')
@section('content')
    <div class="logMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <form method="POST" action="{{ route('mobile.login.store') }}" class="formBox">
            {{ csrf_field() }}
            <div class="nameBox">
                <img src="{{ asset('static_m/img/icon_name.png') }}" class="fImg"/>
                <input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="@lang('app.please enter user name')">
                <div class="tipBox">
                    @if ($errors->has('name'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="psdBox">
                <img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg"/>
                <input id="password" type="password" name="password" placeholder="@lang('app.Please enter your password')">
                <div class="tipBox">
                    @if ($errors->has('password'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>
            <button type="submit" class="subBtn">@lang('app.Sign_in')</button>
        </form>
        <div class="logJump">
            <a href="{{ route('mobile.register.show') }}">@lang('app.New User Registration')></a>
            <span>|</span>
            <a href="{{ route('mobile.reset.sms.show') }}">@lang('app.forget password')></a>
        </div>
        <!--<div class="downBox">
            ——— @lang('app.Jorya Limited') ———
        </div>-->
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
