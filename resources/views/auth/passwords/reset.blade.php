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
            <div class="status clear">
                	<div>
                		<p>
	                		<span class="status_tip step_num first_step">1</span>
	                	</p>
	                	<p>
	                		<span class="first_step step_text">@lang('app.Confirm Account Number')</span>
	                	</p>
                	</div>
                    <div class="step_line">
                    	<p>
                    		<span class="status_tip step_num second_step">2</span>
                    	</p>
                        <p>
                        	<span class="second_step step_text">@lang('app.Enter the verification code')</span>
                        </p>
                    </div>
                    <div class="step_line">
                    	<p>
                    		<span class="status_tip step_num active">3</span>
                    	</p>
                    	<p>
                    		<span class="step_text active">@lang('app.Password reset')</span>
                    	</p>
                    </div>
                </div>
            <div class="panel-body">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif
                <form class="form-horizontal" method="POST" action="{{ route('reset.override_password') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <div class="">
                        	<input type="hidden" name="country_code" value="{{ old('country_code') }}" required>
                        	<input type="hidden" name="phone" value="{{ old('phone') }}" required>
                            <input type="hidden" name="code" value="{{ old('code') }}" required>
                            {{--<input type="hidden" name="token" value="{{ $token or old('token') }}">--}}
                            <label class="reset_psw_new">
                                <span>@lang('app.New password')</span>
                                <input id="new_psw" type="password" name="password" value="{{ old('password') }}"
                                       required placeholder="@lang('app.Please enter a new password')">
                            </label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                	<img src="{{ asset('img/error_fork.png') }}">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                            <label class="reset_psw_sure">
                                <span>@lang('app.Confirm Password')</span>
                                <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                                       placeholder="@lang('app.Please enter your password again')" required>
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
                        <button type="submit" class="btn btn-primary">@lang('app.Complete')</button>
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
