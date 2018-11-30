@extends('layouts.mobile')
@section('title', '重置密码')
@section('content')
    {{--@if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            <li> {{ $error }}</li>
        @endforeach
    @endif--}}

    <div class="regMain">
        <div class="logoImgBox">
            <img src="{{ asset('static_m/img/logo.png') }}"/>
        </div>
        <form method="POST" action="{{ route('mobile.reset.override.store') }}" class="formBox">
            {{ csrf_field() }}
            <div class="nameBox">
                <img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg"/>
                <input type="password" name="password" placeholder="请输入新密码">
                <div class="tipBox">
                    @if ($errors->has('password'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>
            <div class="psdBox">
                <img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg"/>
                <input type="password" name="password_confirmation" placeholder="请确认密码">
                <div class="tipBox">
                    @if ($errors->has('password_confirmation'))
                        <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
                        <span> {{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
            </div>

            <button type="submit" class="subBtn">
                完成
            </button>
        </form>
        <div class="downBox">
            ——— 卓雅美业有限公司 ———
        </div>
    </div>

@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
