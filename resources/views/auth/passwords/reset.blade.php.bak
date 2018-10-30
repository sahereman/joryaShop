@extends('layouts.app')
@section('title', '找回密码')

@section('content')
<div class="reset_psw">
    <div class="m-wrapper">
        <div class="reset_content">
            <p class="reset_title">
                <img src="{{ asset('img/reset_psw.png') }}">
                找回密码
            </p>
            <div class="status">
                <span class="status_tip first_step">1</span>
                <div>
                    <span class="status_tip second_step">2</span>
                </div>
                <div>
                    <span class="status_tip active">3</span>
                </div>
                <p>
                    <span class="first_step">确认账号</span>
                    <span class="second_step">输入验证码</span>
                    <span class="active">密码重置</span>
                </p>
            </div>
            <div class="panel-body">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <form class="form-horizontal" method="POST" action="{{ route('reset.override_password') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="">
                            <input type="hidden" name="email" value="{{ old('email') }}" required>
                            <input type="hidden" name="code" value="{{ old('code') }}" required>
                            {{--<input type="hidden" name="token" value="{{ $token or old('token') }}">--}}
                            <label class="reset_psw_new">
                                <span>新密码</span>
                                <input id="new_psw" type="password" name="password" value="{{ old('password') }}"
                                       required placeholder="请输入新密码">
                            </label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                	<img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            <label class="reset_psw_sure">
                                <span>确认密码</span>
                                <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                                       placeholder="请输入再次输入密码" required>
                            </label>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                	<img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="step_btn">
                        <button type="submit" class="btn btn-primary">完成</button>
                    </div>
                </form>
            </div>
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
