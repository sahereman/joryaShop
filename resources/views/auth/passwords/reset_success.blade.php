@extends('layouts.app')
@section('title', App::isLocale('zh-CN') ? '找回密码' : 'Retrieve password')
@section('content')
    <div class="reset_psw">
        <div class="m-wrapper">
            <div class="reset_content">
                <p class="reset_title">
                    <img src="{{ asset('img/reset_psw.png') }}">
                    @lang('app.Retrieve password')
                </p>
                <div class="success_content">
                    <img src="{{ asset('img/reset_success.png') }}">
                    <p>@lang('app.The new password has been set successfully')</p>
                    <a href="{{ route('root').'?action=login' }}">@lang('app.Sign in now')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
