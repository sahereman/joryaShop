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
                <p>周一至周日 9:00-21:00</p>
                <a href="{{ route('root') }}">联系客服</a>
            </div>
            <div class="footer-top-center">
                <ul>
                    <li>
                        <p>使用帮助</p>
                        <a href="{{ route('root') }}">新手指南</a>
                        <a href="{{ route('root') }}">常见问题</a>
                        <a href="{{ route('root') }}">用户协议</a>
                    </li>
                    <li>
                        <p>支付方式</p>
                        <a href="{{ route('root') }}">支付宝</a>
                        <a href="{{ route('root') }}">微信</a>
                        <a href="{{ route('root') }}">paypal</a>
                    </li>
                    <li>
                        <p>售后服务</p>
                        <a href="{{ route('root') }}">售后咨询</a>
                        <a href="{{ route('root') }}">退货政策</a>
                        <a href="{{ route('root') }}">退货办理</a>
                    </li>
                    <li>
                        <p>关于我们</p>
                        <a href="{{ route('root') }}">公司简介</a>
                        <a href="{{ route('root') }}">产品特色</a>
                        <a href="{{ route('root') }}">联系我们</a>
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
                    <p>友情链接：</p>
                </li>
                @for ($i = 0; $i < 8; $i++)
                    <li>
                        <a href="{{ route('root') }}">尚禾维曼</a>
                    </li>
                @endfor
            </ul>
            <p>Copyright 2018 卓雅美发 版权所有</p>
        </div>
    </div>
</footer>
<!--右侧导航栏-->
<div class="right_navigation">
    <ul>
        <li class="show_customer">
            <a>
                <img src="{{ asset('img/Customer_tip.png') }}">
            </a>
            <div class="customer_info">
                <p>为保证服务质量，请先登录</p>
                <a>联系客服</a>
                <p>客服电话：400-100-5678</p>
                <p>早9:00-晚21:00</p>
            </div>
        </li>
        <li class="show_qr">
            <a>
                <img src="{{ asset('img/qr_tip.png') }}">
            </a>
            <div class="qr_info">
                <img src="{{ asset('img/qr.png') }}">
                <p>关注公众号</p>
            </div>
        </li>
        <li class="backtop" title="点击返回顶部">
            <a>
                <img src="{{ asset('img/top_tip.png') }}">
            </a>
        </li>
    </ul>
</div>
<!--登陆注册弹出层-->
<div class="dialog_iframe dis_n">
    <div class="login_frame">
        <div class="dialog_logo">
            <img src="{{ asset('img/logo.png') }}">
        </div>
        <!--注册-->
        <div class="register_form part_frame">
            <div class="holder">
                <div class="with-line">新用户注册</div>
                <form id="register-form" action="{{ route('register') }}" method="POST">
                    <p id="register_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <input type="text" name="name" id="register_user" placeholder="请输入用户名" required>
                    @if ($errors->has('name'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('name') }}</span>
		                </p>
	                @endif
                    <input type="password" name="password" id="register_psw" placeholder="请输入密码" required>
                    @if ($errors->has('password'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('password') }}</span>
		                </p>
	                @endif
	                <div class="register_phone">
	                	<select class="choose_tel_area" name="country_code" id="register_countryCode">
	                		@foreach($country_codes as $country_code)
	                		    <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
	                		@endforeach
		                </select>
		                <div class="click_areaCode">
		                	<img src="{{ asset('img/tel_phone.png') }}">
		                	<img src="{{ asset('img/sanjiao.png') }}">
		                </div>
		                <span class="areaCode_val"></span>
	                    <input type="text" name="phone" id="register_email" placeholder="请输入手机号" required>
	                </div>
                    @if ($errors->has('phone'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('phone') }}</span>
		                </p>
	                @endif
                    <div class="verification_code">
                        <input type="text" id="register_code" class="code" name="code" placeholder="请输入验证码">
                        <input type="button" class="generate_code" id="getRegister_code" value=" 获取验证码">
                    </div>
                    <p class="register_error error_content">
	                    <i></i>
	                    <span>请输入有效验证码阅读并同意服务协议</span>
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
                        <span>我已阅读并同意</span>
                        <a href="{{ route('root') }}">《用户服务使用协议》</a>
                    </p>
                </div>
                <a class="btn_dialog register_btn" id="register_btn">注册</a>
                <div class="switch-back">
                    <p class="change_title">
                        <span>已有账号？</span>
                        <a code="0" class="login_btn rotary_btn">登录>></a>
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
                            <a>普通登录</a>
                        </li>
                        <li class="mailbox_login">
                            <a>手机动态密码登录</a>
                        </li>
                    </ul>
                </div>
                <form id="login-form" class="active" action="{{ route('login.post') }}" method="POST">
                    <p id="commn_login_token_code" class="dis_n">{{ csrf_field() }}</p>
                    <input type="text" name="username" placeholder="请输入用户名或手机号" required>
                	@if ($errors->has('username'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('username') }}</span>
		                </p>
	                @endif
                    <input type="password" name="password" placeholder="请输入密码" required>
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
		                	@foreach($country_codes as $country_code)
	                		    <option value="{{ $country_code->country_code }}">{{ $country_code->country_name }}</option>
	                		@endforeach
		                </select>
		                <div class="click_areaCode">
		                	<img src="{{ asset('img/tel_phone.png') }}">
		                	<img src="{{ asset('img/sanjiao.png') }}">
		                </div>
		                <span class="areaCode_val login_code"></span>
	                    <input type="text" name="phone" id="login_email" placeholder="请输入手机号" required>
	                </div>
                    @if ($errors->has('phone'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('phone') }}</span>
		                </p>
	                @endif
                    <div class="verification_code">
                        <input type="text" class="code" name="code" id="login_code" placeholder="请输入验证码">
                        <input type="button" class="generate_code" id="getLogin_code" value=" 获取验证码">
                    </div>
                    <p class="mailbox_error error_content">
	                    <i></i>
	                    <span>请输入正确有效验证码</span>
	                </p>
                    @if ($errors->has('code'))
	                    <p class="login_error error_content">
		                    <i></i>
		                    <span>{{ $errors->first('code') }}</span>
		                </p>
	                @endif
                </form>
                <div class="switch-back">
                    <a code="1" class="rotary_btn register_btn pull-left">新用户注册</a>
                    <a class="forget_psw pull-right" href="{{ route('password.request') }}">忘记密码？</a>
                </div>
                <a class="btn_dialog commo_btn active">登录</a>
                <a class="btn_dialog mailbox_btn">登录</a>
            </div>
        </div>
        <div class="close">
            <i></i>
        </div>
    </div>
</div>
