@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Modify password' : '修改密码')
@section('content')
    <div class="headerBar">
    	@if(!is_wechat_browser())
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.users.Change_Password')</span>
        @endif
    </div>
    <div class="editPsdBox">
        <form method="POST" action="{{ route('mobile.users.update_password', ['user' => $user->id]) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="editPsdMain">
                <div class="editPsdItem">
                    <label>@lang('basic.users.old_password')</label>
                    <input type="password" name="password_original"
                           placeholder="@lang('basic.users.Enter_original_password')" value="" required>
                    @if ($errors->has('password_original'))
                        <span class="help-block">
                            <img src="{{ asset('img/error_fork.png') }}">
                            <strong>{{ $errors->first('password_original') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="editPsdItem">
                    <label>@lang('basic.users.new_password')</label>
                    <input type="text" name="password" value="" class="changePsd"
                           placeholder="@lang('basic.users.Enter_new_password')" required>
                    <img src="{{ asset('static_m/img/icon_eyesopen.png') }}" class="clickEye"/>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <img src="{{ asset('img/error_fork.png') }}">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="editPsdItem">
                    <label>@lang('basic.users.confirm_password')</label>
                    <input type="password" name="password_confirmation" value=""
                           placeholder="@lang('basic.users.Enter_new_password_again')" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <img src="{{ asset('img/error_fork.png') }}">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <button class="doneBtn" type="submit">@lang('basic.users.submit')</button>
        </form>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".clickEye").on("click", function () {
            if ($(this).attr("src") == "{{ asset('static_m/img/icon_eyesclose.png') }}") {
                $(this).attr("src", "{{ asset('static_m/img/icon_eyesopen.png') }}");
                $(".changePsd").attr("type", "text");
            } else {
                $(this).attr("src", "{{ asset('static_m/img/icon_eyesclose.png') }}");
                $(".changePsd").attr("type", "password");
            }
        });
    </script>
@endsection
