@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Reset Password' : '重置密码')
@section('content')
    <div class="regMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <form method="POST" action="{{ route('mobile.reset.override.store') }}" class="formBox">
            {{ csrf_field() }}
            <div class="nameBox">
                <img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg"/>
                <input type="password" name="password" placeholder="@lang('app.Please enter a new password')">
                <div class="tipBox">
                    @if ($errors->has('password'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>
            <div class="psdBox">
                <img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg"/>
                <input type="password" name="password_confirmation" placeholder="@lang('app.Confirm Password')">
                <div class="tipBox">
                    @if ($errors->has('password_confirmation'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
            </div>

            <button type="submit" class="subBtn">
                @lang('app.Complete')
            </button>
        </form>
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
