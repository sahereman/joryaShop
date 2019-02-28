<!--服务承诺-->
<!--<div class="commitment">
    <div class="m-wrapper">
        <div class="service_commitment">
            <ul>
                <li>
                    <img src="{{ asset('img/thumb.png') }}">
                    <p>Genuine purchasing</p>
                </li>
                <li>
                    <img src="{{ asset('img/refund.png') }}">
                    <p>7 days no reason to refund</p>
                </li>
                <li>
                    <img src="{{ asset('img/postage.png') }}">
                    <p>Free postage </p>
                </li>
                <li>
                    <img src="{{ asset('img/great_service.png') }}">
                    <p>Great service</p>
                </li>
            </ul>
        </div>
    </div>
</div>-->
<footer class="footer">
    <div class="footer-top">
        <div class="m-wrapper">
            <div class="contact_us">
                <h4>{{ \App\Models\Config::config('service_phone') }}</h4>
                <p>@lang('app.Monday-Sunday') 9:00-21:00</p>
                <a href="{{ route('root') }}">@lang('app.Contact Customer Service')</a>
            </div>
            <div class="footer-top-center">
                <ul>
                    <li>
                        <p>@lang('app.Help')</p>
                        <a href="{{ route('articles.show', ['slug' => 'guide']) }}">@lang('app.Newbie Guide')</a>
                        <a href="{{ route('articles.show', ['slug' => 'problem']) }}">@lang('app.Common Problems')</a>
                        <a href="{{ route('articles.show', ['slug' => 'user_protocol']) }}">@lang('app.User Agreement')</a>
                    </li>
                    <li>
                        <p>@lang('app.Method of Payment')</p>
                        <a href="javascript:void(0);">@lang('app.ALIPAY')</a>
                        <a href="javascript:void(0);">@lang('app.WeChat Pay')</a>
                        <a href="javascript:void(0);">Paypal</a>
                    </li>
                    <li>
                        <p>@lang('app.After-Sale Service')</p>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_consultancy']) }}">@lang('app.After Consulting')</a>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_policy']) }}">@lang('app.Return Policy')</a>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_procedure']) }}">@lang('app.Refunding Procedure')</a>
                    </li>
                    <li>
                        <p>@lang('app.Contact_Us')</p>
                        <a href="{{ route('articles.show', ['slug' => 'company_introduction']) }}">@lang('app.Company Introduction')</a>
                        <a href="{{ route('articles.show', ['slug' => 'products_features']) }}">@lang('app.Products Features')</a>
                        <a href="{{ route('articles.show', ['slug' => 'contact_us']) }}">@lang('app.Contact Us')</a>
                    </li>
                </ul>
            </div>
            <div class="pay_attention">
                {{--<div>
                    <img src="{{ \App\Models\Config::config('wechat_mp_qr_code') ? : config('app.url') . '/defaults/wechat_mp_qr_code.png' }}">
                    <p>@lang('app.Our WeChat public number')</p>
                </div>--}}
                <div>
                    <img src="{{ \App\Models\Config::config('mobile_website_qr_code') ? : config('app.url') . '/defaults/mobile_website_qr_code.png' }}">
                    <p>@lang('app.Our mobile shopping mall')</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="m-wrapper">
            {{--<ul class="web_info">
                <li>
                    <p>
                        营业执照注册号：{{ \App\Models\Config::config('registration_no') }}
                    </p>
                </li>
                <li>
                    <p>
                        增值电信业务经营许可证：{{ \App\Models\Config::config('certificate_no') }}
                    </p>
                </li>
                <li>
                    <p>
                        鲁公网备案 {{ \App\Models\Config::config('icp_no') }} 号
                    </p>
                </li>
            </ul>--}}
            <ul class="friendship_link">
                <li>
                    <img src="{{ asset('img/footer-payment2.png') }}">
                </li>
            </ul>
            <p>Copyright 2018 @lang('app.Lyricalshop All rights reserved')</p>
        </div>
    </div>
</footer>
<!--右侧导航栏-->
<div class="right_navigation">
    <ul>
        <li class="show_fenxaing">
            <a class="fenxiang">
                <img src="{{ asset('img/fenxiang.png') }}">
            </a>
            <div class="fenxiang_info">
                <ul>
                    <li class="row">
                        <!--<div class="share-component " data-disabled="qzone, tencent, douban, diandian, google, linkedin"></div>-->
                        <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                             data-title="Lyrical莱瑞美业">
                            <!--<a href="javascript:void(0);" class="social-share-icon icon-weibo"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-wechat"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-qq"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-qzone"></a>-->
                            <a href="javascript:void(0);" class="social-share-icon icon-facebook"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-linkedin"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-twitter"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-google"></a>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
        <li class="show_customer">
            <a>
                <img src="{{ asset('img/Customer_tip.png') }}">
            </a>
            <div class="customer_info">
                <p>@lang('app.please login first')</p>
                <a class="CustomerClickBtn">@lang('app.Contact Customer Service')</a>
                <p>@lang('app.Customer Service Phone')：{{ \App\Models\Config::config('service_phone') }}</p>
                <p>9:00-21:00</p>
            </div>
        </li>
        <li class="show_qr">
            <a>
                <img src="{{ asset('img/qr_tip.png') }}">
            </a>
            <div class="qr_info">
                <img src="{{ \App\Models\Config::config('mobile_website_qr_code') ? : config('app.url') . '/defaults/mobile_website_qr_code.png' }}">
                <p>@lang('app.Our mobile shopping mall')</p>
            </div>
        </li>
        <li class="backtop" title="@lang('app.Click to return to the top')">
            <a>
                <img src="{{ asset('img/top_tip.png') }}">
            </a>
        </li>
    </ul>
</div>
<!--登陆注册弹出层-->
<div class="dialog_iframe dis_n" id="dialog_iframe">
    <div class="login_frame">
        <div class="dialog_logo">
            <img src="{{ asset('img/logo.png') }}">
        </div>
        <!--注册-->
        <div class="register_form part_frame">
            <div class="holder">
                <div class="with-line">@lang('app.New User Registration')</div>
                <form id="register-form" action="{{ route('register') }}" method="POST">
                    <p id="register_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <ul>
                    	<li>
                    		<span><i>*</i>username:</span>
                    		<input type="text" name="name" id="register_user" placeholder="@lang('app.please enter user name')"required>
                    		@if ($errors->has('name'))
		                        <p class="login_error error_content">
		                            <i></i>
		                            <span>{{ $errors->first('name') }}</span>
		                        </p>
		                    @endif
                    	</li>
                    	<li>
                    		<span><i>*</i>password:</span>
                    		<input type="password" name="password" id="register_psw" placeholder="@lang('app.Please enter your password')" required>
                    		@if ($errors->has('password'))
		                        <p class="login_error error_content">
		                            <i></i>
		                            <span>{{ $errors->first('password') }}</span>
		                        </p>
		                    @endif
                    	</li>
                    	<li>
                    		<span>E-mail:</span>
                    		<input type="email" name="email" id="register_mail" placeholder="@lang('app.Please enter your password')">
                    	</li>
                    	<li>
                    		<span><i>*</i>country:</span>
                    		<select class="choose_tel_area" name="country_code" id="register_countryCode">
                    		    <option value="null">@lang('app.Please choose the country')</option>
	                            @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
	                                <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
	                            @endforeach
	                        </select>
	                        <div class="click_areaCode">
	                            <img src="{{ asset('img/sanjiao.png') }}">
	                        </div>
                    	</li>
                    	<li>
                    		<span><i>*</i>phone:</span>
                    		<input type="text" name="phone" id="register_email"
                               placeholder="@lang('app.Please select a country first')" required>
                            @if ($errors->has('phone'))
		                        <p class="login_error error_content">
		                            <i></i>
		                            <span>{{ $errors->first('phone') }}</span>
		                        </p>
		                    @endif
                    	</li>
                    	<li>
                    	    <span><i>*</i>code:</span>
                    	    <input type="text" id="register_code" class="code" name="code"
                               placeholder="@lang('app.please enter verification code')">
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
                        <a href="{{ route('root') }}">《@lang('app.User Service Use Agreement')》</a>
                    </p>
                </div>
                <a class="btn_dialog register_btn" id="register_btn"
                   data-url="{{ route('register') }}">@lang('app.Register')</a>
                <div class="switch-back">
                    <p class="change_title">
                        <span>@lang('app.Existing account')</span>
                        <a code="0" class="login_btn rotary_btn">@lang('app.Log_In')>></a>
                    </p>
                </div>
            </div>
        </div>
        <!--登录-->
        <div class="login_form part_frame dis_n">
            <div class="holder">
                <div class="login_type">
                    <ul>
                        <li class="common_login active">
                            <a>@lang('app.Normal login')</a>
                        </li>
                        <li class="mailbox_login">
                            <a>@lang('app.Mobile phone dynamic code login')</a>
                        </li>
                    </ul>
                </div>
                <form id="login-form" class="active" action="{{ route('login.post') }}" method="POST">
                    <p id="commn_login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <ul>
                        <li>
                            <span><i>*</i>username:</span>
                            <input type="text" name="username"
                                placeholder="@lang('app.Please enter your username or phone number')" required>
                            @if ($errors->has('username'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('username') }}</span>
                                </p>
                            @endif
                        </li>
                        <li>
                            <span><i>*</i>password:</span>
                            <input type="password" name="password" placeholder="@lang('app.Please enter your password')"
                                   required>
                            @if ($errors->has('password'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('password') }}</span>
                                </p>
                            @endif
                        </li>
                    </ul>  
                </form>
                <form id="mailbox_login" action="{{ route('login.verify_sms_code') }}" method="POST">
                    <p id="login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <ul>
                        <li>
                            <span><i>*</i>country:</span>
                            <select class="choose_tel_area" name="country_code" id="login_countryCode">
                                <option value="null">@lang('app.Please choose the country')</option>
                                @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                    <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                                @endforeach
                            </select>
                            <div class="click_areaCode">
                                <img src="{{ asset('img/sanjiao.png') }}">
                            </div>
                        </li>
                        <li>
                            <span><i>*</i>phone:</span>
                            <input type="text" name="phone" id="login_email"
                               placeholder="@lang('app.Please select a country first')" required>
                            @if ($errors->has('phone'))
                                <p class="login_error error_content">
                                    <i></i>
                                    <span>{{ $errors->first('phone') }}</span>
                                </p>
                            @endif
                        </li>
                        <li>
                            <span><i>*</i>code:</span>
                            <input type="text" class="code" name="code" id="login_code"
                               placeholder="@lang('app.please enter verification code')">
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
                <div class="switch-back">
                    <a code="1" class="rotary_btn register_btn pull-left">@lang('app.New User Registration')</a>
                    <a class="forget_psw pull-right"
                       href="{{ route('password.request') }}">@lang('app.forget password')</a>
                </div>
                <a class="btn_dialog commo_btn active" data-url="{{ route('login') }}">@lang('app.Log_In')</a>
                <a class="btn_dialog mailbox_btn" data-url="{{ route('login.verify_sms_code') }}">@lang('app.Log_In')</a>
            </div>
        </div>
        <div class="close">
            <i></i>
        </div>
    </div>
</div>
