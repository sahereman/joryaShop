<footer class="footer">
    {{-- footer上部分 客服相关--}}
    {{-- 合作logo --}}
    <div class="partner-logo">
        <div class="logo-img-words">
            <a href="https://www.amazon.com/s?k=LHC+HAIR&ref=bl_dp_s_web_0" target="_blank">
                <img src="{{ asset("img/amazon.png") }}" alt="lyricalhair">
                <span class="part-title">Amazon</span>
            </a>
        </div>
        <div class="logo-img-words">
            <a href="https://www.ebay.com/" target="_blank">
                <img src="{{ asset("img/ebay.png") }}" alt="lyricalhair">
                <span class="part-title">eBay</span>
            </a>
        </div>
        <div class="logo-img-words">
            <a href="https://www.aliexpress.com/" target="_blank">
                <img src="{{ asset("img/aliexpress.png") }}" alt="lyricalhair">
                <span class="part-title">AliExpress</span>
            </a>
        </div>
    </div>
    <div class="footer-top dis_ni">
        <ul class="main-content">
            <li>
                <a href="skype:live:info_1104672?call"></a>
                <img src="{{ asset("img/footer/video.png") }}" alt="lyricalhair">
                <span class="part-title">Video Chat</span>
                <span>Schedule an appointment with a hair</span>
                <span>replacement consultant</span>
            </li>
            <li>
                <a href="skype:live:info_1104672?chat"></a>
                <img src="{{ asset("img/footer/comments.png") }}" alt="lyricalhair">
                <span class="part-title">Chat Now</span>
                <span>Chat online with a hair</span>
                <span>replacement consultant</span>
            </li>
            <li>
                <a href="skype:live:info_1104672?chat"></a>
                <img src="{{ asset("img/footer/service.png") }}" alt="lyricalhair">
                <span class="part-title">Skype Call Us</span>
                <span>Office Phone: +86-532-85878587</span>
                <span>WhatsApp: +86-18661937606</span>
            </li>
            <li class="Start-subscribe">
                <img src="{{ asset("img/footer/email-us.png") }}" alt="lyricalhair">
                <span class="part-title">Email Us</span>
                <span>Tell Us How We can help</span>
                <span>Email: info@lyricalhair.com</span>
            </li>
        </ul>
    </div>
    {{-- footer中间层 邮箱订阅相关--}}
    <div class="footer-center">
        <div class="subscribe-form main-content">
            {{-- <form> --}}
                <img src="{{ asset("img/footer/CanFind.png") }}" alt="lyricalhair">
                {{-- Subscribe to get more product information, maintenance knowledge --}}
                <p>Cant't Find Any Stock Hairpiece For Your Specific Requirements ?</p>
                {{-- special offers and important notices. --}}
                <p class="last-subscribe">Order our custom hair piece today & Receive it in 6 Weeks!</p>
                <a href="javascript:viod(0)" class="Start-subscribe">Make an Inquiry</a>
                {{-- 自定义订阅弹窗 --}}
                <div class="popup-wrap">
                    <div class="the-popup">
                        <a class="close-popup" href="javascript:void(0)"><img src="{{ asset('img/close_btn.png') }}" alt="lyricalhair"></a>
                        <h3>Let's Connect Now ! </h3>
                        <div class="Connect-tip">
                            <img src="{{ asset("img/footer/connect-icon.png") }}" alt="lyricalhair">
                            <div class="Connect-tip-text">
                                <p>Do you have inquiries?</p>
                                <p>Looking for a straightforward quotation?</p>
                                <p>Leave us your email and send in your message below.</p>
                            </div>
                        </div>
                        <div class="wpcf7" role="form" id="wpcf7-f145-o1" lang="en-US" dir="ltr">
                            <div class="screen-reader-response"></div>
                            <form action="">
                                <p id="footer_token_code" class="dis_n">{{ csrf_field() }}</p>
                                <input type="hidden" name="type" id="feedback-type" value="subscription">
                                <ul>
                                    <li>
                                        <p>Name<span>*</span></p>
                                        <input type="text" name="name" id="footname" placeholder="Please enter your name" required autocomplete="off">
                                    </li>
                                    <li>
                                        <p>Phone & Whatsapp</p>
                                        <input type="text" name="phone" id="footphone" placeholder="Your phone or whatsapp" required autocomplete="off">
                                    </li>
                                    <li>
                                        <p>Email<span>*</span></p>
                                        <input type="email" name="email" id="footemail" placeholder="Please enter your email" required autocomplete="off">
                                    </li>
                                    <li class="textarea-li">
                                        <p>Your Message<span>*</span></p>
                                        <textarea name="content" cols="40" rows="10" aria-required="true" aria-invalid="false" placeholder="Your Message"></textarea>
                                    </li>
                                    <li>
                                        <button type="button" title="Subscribe" id="subFootCode" data-url="{{ route('feedbacks.store') }}">Subscribe</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- <input type="hidden" name="content" id="feedback-content" value="Subscribe to get product information, maintenance knowledge, special offers and important notices."> --}}
                {{-- <input type="hidden" name="type" id="feedback-type" value="subscription"> --}}
                {{-- <input type="text" name="captcha" id="footverCode" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="Verification Code" required>
                <img class="thumbnail captcha mt-3 mb-2" src="{{ captcha_src('subscription') }}" onclick="this.src='/captcha/subscription?'+Math.random()" title="点击图片重新获取验证码"> --}}
                {{-- <button type="button" class="dis_n" title="Subscribe" id="subFootCode" data-url="{{ route('feedbacks.store') }}">Subscribe</button> --}}
            {{-- </form> --}}
        </div>
    </div>
    {{-- footer下部分 友情链接相关--}}
    <div class="footer-bottom">
        <div class="main-content">
            <div class="footer-logo">
                <img src="{{ asset("img/footer/logo_footer.png") }}" alt="lyricalhair">
            </div>
            <div class="footer-menu">
                <ul>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Company_Info')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('seo_url', ['slug' => 'about_us']) }}">@lang('app.About_Us')</a>
                            <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">@lang('app.Contact_Us')</a>
                            <a href="{{ route('seo_url', ['slug' => 'distributor_wholesale_cooperation']) }}">@lang('app.Distributor_wholesale_cooperation')</a>
                            <a href="{{ route('seo_url', ['slug' => 'our_donation_for_love']) }}">@lang('app.Our_Donation_for_Love')</a>
                            {{--<a href="{{url('sitemap.html')}}">@lang('app.Sitemap')</a>--}}
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.WARRANTY_SERVICES')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('seo_url', ['slug' => 'drop_shipment']) }}">@lang('app.Drop_Shipment')</a>
                            <a href="{{ route('seo_url', ['slug' => 'production_time_&_delivery']) }}">@lang('app.Production_Time_&_Delivery')</a>
                            <a href="{{ route('seo_url', ['slug' => 'payment_methods']) }}">@lang('app.Payment_Methods')</a>
                            <a href="{{ route('seo_url', ['slug' => 'warranty_and_return']) }}">@lang('app.Warranty_and_Return')</a>
                            <a href="{{ route('seo_url', ['slug' => 'private_policy']) }}">@lang('app.Private_Policy')</a>
                            <a href="{{ route('seo_url', ['slug' => 'terms_and_conditions']) }}">@lang('app.Terms_and_Conditions')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Help_&_Support')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('seo_url', ['slug' => 'FAQs']) }}">@lang('app.FAQs')</a>
                            <a href="{{ route('seo_url', ['slug' => 'track_my_order']) }}">@lang('app.Track_My_Order')</a>
                            <a href="https://www.lyricalhair.com/storage/Base-Order-Form.doc">@lang('app.Download_Order_Form')</a>
                            <a href="{{ route('seo_url', ['slug' => 'download_catalogue']) }}">@lang('app.Download_Catalogue')</a>
                            <a href="{{ route('seo_url', ['slug' => 'currency_rates']) }}">@lang('app.Currency_Rates')</a>
                            <a href="{{ route('seo_url', ['slug' => 'hair_care']) }}">@lang('app.Hair_Care')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.MY_ACCOUNT')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('seo_url', ['slug' => 'my_orders']) }}">@lang('app.My_Orders')</a>
                            <a href="{{ route('seo_url', ['slug' => 'my_coupon']) }}">@lang('app.My_Coupon')</a>
                            <a href="{{ route('seo_url', ['slug' => 'my_message']) }}">@lang('app.My_Message')</a>
                            <a href="{{ route('seo_url', ['slug' => 'shipping_address']) }}">@lang('app.Shipping_Address')</a>
                        </div>
                    </li>

                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.CUSTOM_OPTIONS')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('seo_url', ['slug' => 'ordering_guide']) }}">@lang('app.Ordering_Guide')</a>
                            <a href="{{ route('seo_url', ['slug' => 'how_to_make_template']) }}">@lang('app.How_to_Make_Template')</a>
                            <a href="{{ route('seo_url', ['slug' => 'how_to_send_in_hair_sample']) }}">@lang('app.How_to_Send_in_Hair_Sample')</a>
                            <a href="{{ route('seo_url', ['slug' => 'how_to_match_hair_length']) }}">@lang('app.How_to_Match_Hair_Length')</a>
                        </div>
                    </li>
                </ul>
                <div class="footer-share">
                    <p>Follow us</p>
                    <div class="footer-share-items">
                        <div class="footer-share-item">
                            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                                    data-title="Lyricalhair">
                                <a href="javascript:void(0);" class="social-share-icon icon-facebook"></a>
                            </div>
                        </div>
                        <div class="footer-share-item">
                            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                                    data-title="Lyricalhair">
                                <a href="javascript:void(0);" class="social-share-icon icon-linkedin"></a>
                            </div>
                        </div>
                        <div class="footer-share-item">
                            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                                    data-title="Lyricalhair">
                                <a href="javascript:void(0);" class="social-share-icon icon-twitter"></a>
                            </div>
                        </div>
                        <div class="footer-share-item">
                            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                                    data-title="Lyricalhair">
                                <a href="javascript:void(0);" class="social-share-icon icon-google"></a>
                            </div>
                        </div>
                        {{-- 邮件 --}}
                        <div class="footer-share-item Start-subscribe">
                            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                                    data-title="Lyricalhair">
                                <a href="javascript:void(0);" class="social-share-icon icon-email">
                                    <img src="{{ asset("img/footer/email.png") }}" alt="lyricalhair">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 友链 --}}
            <div class="friendly-link">
                <img src="{{ asset('img/footer/footer-payment.png') }}">
                <p class="friendship_cop">2010-2019 Lyricalhair Co., Ltd. All Rights Reserved.<span> Technical Support : <a target="_blank" href="http://www.sahereman.com/">Sahereman</a></span></p>
            </div>
        </div>
    </div>
</footer>
<!--右侧导航栏-->
<div class="right_navigation">
    <ul>
        <li class="qr-code show_qr">
            <a>
                <img src="{{ asset('img/qr_tip.png') }}">
            </a>
            <div class="qr_info">
                <img src="{{ \App\Models\Config::config('mobile_website_qr_code') ? : config('app.url') . '/defaults/mobile_website_qr_code.png' }}">
                <p>Mobile quick access</p>
            </div>
        </li>
        <li class="skype">
            <a href="skype:live:info_1104672?call">
                <img src="{{ asset("img/footer/skype-fill.png") }}" alt="lyricalhair">
            </a>
        </li>
        <li class="" title="facetime">
            <a href="facetime://callingallgeeks@gmail.com">
                <img class="facetime" src="{{ asset("img/footer/facetime.png") }}" alt="lyricalhair">
            </a>
        </li>
        <li class="share-right">
            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                    data-title="Lyricalhair">
                <a href="javascript:void(0);" class="social-share-icon icon-facebook"></a>
            </div>
        </li>
        <li class="share-right">
            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                    data-title="Lyricalhair">
                <a href="javascript:void(0);" class="social-share-icon icon-linkedin"></a>
            </div>
        </li>
        <li class="share-right">
            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                    data-title="Lyricalhair">
                <a href="javascript:void(0);" class="social-share-icon icon-twitter"></a>
            </div>
        </li>
        <li class="share-right">
            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                    data-title="Lyricalhair">
                <a href="javascript:void(0);" class="social-share-icon icon-google"></a>
            </div>
        </li>
        <li class="share-right Start-subscribe">
            <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                    data-title="Lyricalhair">
                <a href="javascript:void(0);" class="social-share-icon icon-email">
                    <img src="{{ asset("img/footer/email.png") }}" alt="lyricalhair">
                </a>
            </div>
        </li>
        <li class="qr-code show_qr1">
            <a>
                <img class="wechat" src="{{ asset('img/footer/wechat.png') }}">
            </a>
            <div class="qr_info1">
                <img src="{{ \App\Models\Config::config('mobile_website_qr_code') ? : config('app.url') . '/defaults/mobile_website_qr_code.png' }}">
                <p>Mobile quick access</p>
            </div>
        </li>
        <li class="backtop" title="@lang('app.Click to return to the top')">
            <img src="{{ asset('img/footer/backtop.png') }}" alt="lyricalhair">
        </li>
    </ul>
</div>
