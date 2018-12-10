<!--服务承诺-->
<div class="commitment">
    <div class="m-wrapper">
        <div class="service_commitment">
            <ul>
                <li>
                    <img src="{{ asset('img/thumb.png') }}">
                    <p>365正品承诺</p>
                    <p>Genuine purchasing</p>
                </li>
                <li>
                    <img src="{{ asset('img/refund.png') }}">
                    <p>7天无理由退款</p>
                    <p>7 days no reason to refund</p>
                </li>
                <li>
                    <img src="{{ asset('img/postage.png') }}">
                    <p>满88元免邮费</p>
                    <p>Free postage </p>
                </li>
                <li>
                    <img src="{{ asset('img/great_service.png') }}">
                    <p>金牌服务</p>
                    <p>Great service</p>
                </li>
            </ul>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="footer-top">
        <div class="m-wrapper">
            <div class="contact_us">
                <h4>400-100-5678 </h4>
                <p>@lang('app.Monday-Sunday') 9:00-21:00</p>
                <a href="{{ route('root') }}">@lang('app.Contact Customer Service')</a>
            </div>
            <div class="footer-top-center">
                <ul>
                    <li>
                        <p>@lang('app.Help')</p>
                        <a href="{{ route('articles.show', ['slug' => 'guide']) }}">@lang('app.Newbie Guide')</a>
                        <a href="{{ route('articles.show', ['slug' => 'problem']) }}">@lang('app.Common problems')</a>
                        <a href="{{ route('articles.show', ['slug' => 'user_protocol']) }}">@lang('app.User Agreement')</a>
                    </li>
                    <li>
                        <p>@lang('app.Method of Payment')</p>
                        <a href="javascript:void(0);">@lang('app.ALIPAY')</a>
                        <a href="javascript:void(0);">@lang('app.WeChat Pay')</a>
                        <a href="javascript:void(0);">paypal</a>
                    </li>
                    <li>
                        <p>@lang('app.After-sales Service')</p>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_consultancy']) }}">@lang('app.After Consulting')</a>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_policy']) }}">@lang('app.Return Policy')</a>
                        <a href="{{ route('articles.show', ['slug' => 'refunding_procedure']) }}">@lang('app.Return to deal with')</a>
                    </li>
                    <li>
                        <p>@lang('app.About us')</p>
                        <a href="{{ route('articles.show', ['slug' => 'company_introduction']) }}">@lang('app.Company profile')</a>
                        <a href="{{ route('articles.show', ['slug' => 'products_features']) }}">@lang('app.Products features')</a>
                        <a href="{{ route('articles.show', ['slug' => 'contact_us']) }}">@lang('app.Contact us')</a>
                    </li>
                </ul>
            </div>
            <div class="pay_attention">
                <div>
                    <img src="{{ asset('img/qr.png') }}">
                    <p>关注公众号</p>
                </div>
                <div>
                    <img src="{{ asset('img/qr.png') }}">
                    <p>手机逛XX</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="m-wrapper">
            <ul class="web_info">
                <li>
                    <p>
                        营业执照注册号：330106000000000
                    </p>
                </li>
                <li>
                    <p>
                        增值电信业务经营许可证：鲁B2-20110000
                    </p>
                </li>
                <li>
                    <p>
                        鲁公网备案号 33010600000000
                    </p>
                </li>
            </ul>
            <ul class="friendship_link">
                <li>
                    <p>@lang('app.Friendship link')：</p>
                </li>
                @for ($i = 0; $i < 8; $i++)
                    <li>
                        <a href="http://sahereman.com/" target="view_window">@lang('basic.sahereman')</a>
                    </li>
                @endfor
            </ul>
            <p>Copyright 2018 @lang('app.Joryashop All rights reserved')</p>
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
                             data-title="Jorya卓雅美业">
                            <a href="javascript:void(0);" class="social-share-icon icon-weibo"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-wechat"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-qq"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-qzone"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-linkedin"></a>
                            <a href="javascript:void(0);" class="social-share-icon icon-facebook"></a>
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
                <a>@lang('app.Contact Customer Service')</a>
                <p>@lang('app.Customer Service Phone')：400-100-5678</p>
                <p>早9:00-晚21:00</p>
            </div>
        </li>
        <li class="show_qr">
            <a>
                <img src="{{ asset('img/qr_tip.png') }}">
            </a>
            <div class="qr_info">
                <img src="{{ asset('img/qr.png') }}">
                <p>@lang('app.Follow the public number')</p>
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
                    <input type="text" name="name" id="register_user" placeholder="@lang('app.please enter user name')"
                           required>
                    @if ($errors->has('name'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('name') }}</span>
                        </p>
                    @endif
                    <input type="password" name="password" id="register_psw"
                           placeholder="@lang('app.Please enter your password')" required>
                    @if ($errors->has('password'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('password') }}</span>
                        </p>
                    @endif
                    <div class="register_phone">
                        <select class="choose_tel_area" name="country_code" id="register_countryCode">
                            @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                            @endforeach
                        </select>
                        <div class="click_areaCode">
                            <img src="{{ asset('img/tel_phone.png') }}">
                            <img src="{{ asset('img/sanjiao.png') }}">
                        </div>
                        <span class="areaCode_val"></span>
                        <input type="text" name="phone" id="register_email"
                               placeholder="@lang('app.Please select a country first')" required>
                    </div>
                    @if ($errors->has('phone'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('phone') }}</span>
                        </p>
                    @endif
                    <div class="verification_code">
                        <input type="text" id="register_code" class="code" name="code"
                               placeholder="@lang('app.please enter verification code')">
                        <input type="button" class="generate_code" data-url="{{ route('register.send_sms_code') }}"
                               id="getRegister_code" value=" @lang('app.get verification code')">
                    </div>
                    <p class="register_error error_content">
                        <i></i>
                        <span>@lang('app.Please enter a valid verification code')</span>
                    </p>
                    @if ($errors->has('code'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('code') }}</span>
                        </p>
                    @endif
                </form>
                <div class="switch-back">
                    <p class="agreement_content">
                        <input type="checkbox" id="agreement" class="agree_agreement">
                        <span>@lang('app.I have read and agreed')</span>
                        <a href="{{ route('root') }}">《@lang('app.User Service Use Agreement')》</a>
                    </p>
                </div>
                <a class="btn_dialog register_btn" id="register_btn"
                   data-url="{{ route('register') }}">@lang('app.Registered')</a>
                <div class="switch-back">
                    <p class="change_title">
                        <span>@lang('app.Existing account')</span>
                        <a code="0" class="login_btn rotary_btn">@lang('app.Sign_in')>></a>
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
                    <input type="text" name="username"
                           placeholder="@lang('app.Please enter your username or phone number')" required>
                    @if ($errors->has('username'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('username') }}</span>
                        </p>
                    @endif
                    <input type="password" name="password" placeholder="@lang('app.Please enter your password')"
                           required>
                    @if ($errors->has('password'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('password') }}</span>
                        </p>
                    @endif
                </form>
                <form id="mailbox_login" action="{{ route('login.verify_sms_code') }}" method="POST">
                    <p id="login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <div class="register_phone">
                        <select class="choose_tel_area" name="country_code" id="login_countryCode">
                            @foreach(\App\Models\CountryCode::countryCodes() as $country_code)
                                <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
                            @endforeach
                        </select>
                        <div class="click_areaCode">
                            <img src="{{ asset('img/tel_phone.png') }}">
                            <img src="{{ asset('img/sanjiao.png') }}">
                        </div>
                        <span class="areaCode_val login_code"></span>
                        <input type="text" name="phone" id="login_email"
                               placeholder="@lang('app.Please select a country first')" required>
                    </div>
                    @if ($errors->has('phone'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('phone') }}</span>
                        </p>
                    @endif
                    <div class="verification_code">
                        <input type="text" class="code" name="code" id="login_code"
                               placeholder="@lang('app.please enter verification code')">
                        <input type="button" class="generate_code" data-url="{{ route('login.send_sms_code') }}"
                               id="getLogin_code" value=" @lang('app.get verification code')">
                    </div>
                    <p class="mailbox_error error_content">
                        <i></i>
                        <span>@lang('app.Please enter a valid verification code')</span>
                    </p>
                    @if ($errors->has('code'))
                        <p class="login_error error_content">
                            <i></i>
                            <span>{{ $errors->first('code') }}</span>
                        </p>
                    @endif
                </form>
                <div class="switch-back">
                    <a code="1" class="rotary_btn register_btn pull-left">@lang('app.New User Registration')</a>
                    <a class="forget_psw pull-right"
                       href="{{ route('password.request') }}">@lang('app.forget password')</a>
                </div>
                <a class="btn_dialog commo_btn active" data-url="{{ route('login') }}">@lang('app.Sign_in')</a>
                <a class="btn_dialog mailbox_btn"
                   data-url="{{ route('login.verify_sms_code') }}">@lang('app.Sign_in')</a>
            </div>
        </div>
        <div class="close">
            <i></i>
        </div>
    </div>
</div>
