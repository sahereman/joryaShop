@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Set up successfully' : '设置成功')
@section('content')
    <div class="regMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <div class="sucBox">
            <img src="{{ asset('static_m/img/icon_Success.png') }}"/>
            <div>@lang('app.The new password has been set successfully')</div>
            <button type="submit" class="subBtn">
                <a href="{{route('mobile.login.show')}}">@lang('app.Sign_in')</a>
            </button>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection




