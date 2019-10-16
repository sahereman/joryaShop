@extends('layouts.app')
@section('content')
<div class="login-content">
    <div class="container">
        <div class="page-title">
            <h2>LOG IN</h2>
        </div>
        {{-- 选项卡登录的两种方式 --}}
        <ul id="myTab" class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#bulletin" role="tab" data-toggle="tab">@lang('app.Normal login')</a></li>
            <li><a href="#rule" role="tab" data-toggle="tab">@lang('app.Mobile phone dynamic code login')</a></li>
        </ul>
        <!-- 选项卡面板不同登录方式的具体内容-->
        <div id="myTabContent" class="tab-content">
            {{--普通登陆--}}
            <div class="tab-pane fade in active" id="bulletin">
                <form id="login-form-page" class="active" action="{{ route('login.post') }}" method="POST">
                    <p id="common_login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <ul>
                        <li>
                            <p>Email<i class="red">*</i></p>
                            <input type="text" name="username" required>
                            @if ($errors->has('username'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('username') }}</span>
                                </p>
                            @endif
                        </li>
                        <li>
                            <p>Password<i class="red">*</i></p>
                            <input type="password" name="password" required>
                            @if ($errors->has('password'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('password') }}</span>
                                </p>
                            @endif
                        </li>
                    </ul>
                </form>
                <div class="switch-back">
                    <a class="forget_psw pull-right" href="{{ route('password.request') }}">@lang('app.forget password')</a>
                    <a class="btn_dialog normal_btn" data-url="{{ route('login') }}">@lang('app.Log_In')</a>
                    <a href="{{ route('register') }}">Create an Account</a>
                </div>
            </div>
            {{--手机号登陆--}}
            <div class="tab-pane fade" id="rule">
                <form id="mailbox_login" action="{{ route('login.verify_sms_code') }}" method="POST">
                    <p id="login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <ul>
                        <li>
                            <p>Country<i class="red">*</i></p>
                            <select class="choose_tel_area" name="country_code" id="login_countryCode">
                                <option value="000">@lang('app.Please choose the country')</option>
                                @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                    <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                                @endforeach
                            </select>
                        </li>
                        <li>
                            <p>Phone<i class="red">*</i></p>
                            <span class="area_codeshow">+000</span>
                            <input type="text" name="phone" id="login_email" required>
                            @if ($errors->has('phone'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('phone') }}</span>
                                </p>
                            @endif
                        </li>
                        <li>
                            <p>Code<i class="red">*</i></p>
                            <input type="text" class="code" name="code" id="login_code">
                            <input type="button" class="generate_code" data-url="{{ route('login.send_sms_code') }}"
                                   id="getLogin_code" value=" @lang('app.get verification code')">
                            @if ($errors->has('code'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('code') }}</span>
                                </p>
                            @endif
                        </li>
                    </ul>
                </form>
                <a class="btn_dialog mailbox_btn" data-url="{{ route('login.verify_sms_code') }}">@lang('app.Log_In')</a>
            </div>
        </div>
        {{-- facebook登陆 --}}
        <div class="fb-login">
            {{--<button class="fb-login-btn">Facebook Login</button>--}}
            {{--<button class="fb_button fb_button_medium" onclick="login(); return false;">--}}
            <button class="fb_button fb_button_medium">
                <span class="border-line">
                    {{--<span class="fb_button_text">Facebook Login</span>--}}
                    <span class="fb_button_text"><a href="{{ get_facebook_login_url() }}">Facebook Login</a></span>
                </span>
            </button>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        {{--登录成功后返回上一页--}}
        //
        // 普通登陆表单验证
        $("#login-form-page").validate({
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: 'Please enter user name',
                },
                password: {
                    required: 'Please enter your password ',
                }
            }
        });
        //普通登录
        $(".normal_btn").on("click", function () {
            var clickDome = $(this),
                $loginForm = $("#login-form-page"),
                isValid = $loginForm.valid();
            if (isValid) {
                var data = {
                    username: $loginForm.find("input[name='username']").val(),
                    password: $loginForm.find("input[name='password']").val(),
                    _token: $("#common_login_token_code").find("input").val(),
                };
                var url = clickDome.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (json) {
                        if (json.code == 200) {
                            window.location.href=document.referrer;
                            // window.history.go(-1);
                        } else {
                            layer.alert(json.message);
                        }
                    },
                    error: function (err) {
                        var errorTips = err.responseJSON.errors;
                        var errorText = [];
                        $.each(errorTips, function (i, n) {
                            errorText.push(n)
                        });
                        layer.msg(errorText[errorText.length - 1][0]);
                    },
                    complete: function () {

                    }
                });
            }else {
                window.location.href=clickDome.attr('data-url');
            }
        });
    </script>
@endsection
