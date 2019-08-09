@extends('layouts.app')
@section('content')
    <div class="register-content">
        <div class="container">
            <div class="page-title">
                <h2>CREATE AN ACCOUNT</h2>
            </div>
            <form id="register-form" action="{{ route('register') }}" method="POST">
                <p id="register_token_code" class="dis_n">{{ csrf_field() }}</p>
                <ul>
                    <li>
                        <p>Username<i class="red">*</i></p>
                        <input type="text" name="name" id="register_user" required>
                        @if ($errors->has('name'))
                            <p class="login_error error_content">
                                <i></i>
                                <span>{{ $errors->first('name') }}</span>
                            </p>
                        @endif
                    </li>
                    <li>
                        <p>Password<i class="red">*</i></p>
                        <input type="password" name="password" id="register_psw" required>
                        @if ($errors->has('password'))
                            <p class="login_error error_content">
                                <i></i>
                                <span>{{ $errors->first('password') }}</span>
                            </p>
                        @endif
                    </li>
                    <li>
                        <p>Email<i class="red">*</i></p>
                        <input type="email" name="email" id="register_mail" required>
                        @if ($errors->has('email'))
                            <p class="login_error error_content">
                                <i></i>
                                <span>{{ $errors->first('email') }}</span>
                            </p>
                        @endif
                    </li>
                    <li>
                        <p>Country<i class="red">*</i></p>
                        <select class="choose_tel_area" name="country_code" id="register_countryCode">
                            <option value="000">@lang('app.Please choose the country')</option>
                            @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                            @endforeach
                        </select>
                    </li>
                    <li>
                        <p>Phone<i class="red">*</i></p>
                        <span class="area_codeshow">+000</span>
                        <input type="text" name="phone" id="register_email" required>
                        @if ($errors->has('phone'))
                            <p class="login_error error_content">
                                <i></i>
                                <span>{{ $errors->first('phone') }}</span>
                            </p>
                        @endif
                    </li>
                    <li>
                        <p>Code<i class="red">*</i></p>
                        <input type="text" id="register_code" class="code" name="code">
                        <input type="button" class="generate_code" data-url="{{ route('register.send_sms_code') }}"
                               id="getRegister_code" value=" @lang('app.get verification code')">
                        @if ($errors->has('code'))
                            <p class="login_error error_content">
                                <i></i>
                                <span>{{ $errors->first('code') }}</span>
                            </p>
                        @endif
                    </li>
                </ul>
            </form>
            <div class="switch-back">
                <p class="agreement_content">
                    <input type="checkbox" id="agreement" class="agree_agreement">
                    <span>@lang('app.I have read and agreed')</span>
                    <a href="{{ route('seo_url', ['slug' => 'terms_of_service']) }}">《@lang('app.User Service Use Agreement')》</a>
                </p>
            </div>
            <a class="btn_dialog register_btn" id="register_btn" data-url="{{ route('register') }}">@lang('app.Register')</a>
            <a href="{{ route('login') }}" class="login_btn rotary_btn">@lang('app.Existing account')@lang('app.Log_In')>></a>
        </div>
    </div>
@endsection
