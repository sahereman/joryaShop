<footer class="footer">
    <div class="inner-container">
        <div class="m-wrapper">
            <div class="footer-subscribe">
                <p>Subscribe to get more product information, maintenance knowledge, special offers and important notices.</p>
                <div class="subscribe-form">
                    <form>
                        <p id="footer_token_code" class="dis_n">{{ csrf_field() }}</p>
                        <input type="email" name="email" id="footemail" placeholder="Your Email Address" required>
                        <input type="hidden" name="content" id="feedback-content" value="Subscribe to get product information, maintenance knowledge, special offers and important notices.">
                        <input type="hidden" name="type" id="feedback-type" value="subscription">
                        <input type="text" name="captcha" id="footverCode" class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="Verification Code" required>
                        <img class="thumbnail captcha mt-3 mb-2" src="{{ captcha_src('subscription') }}" onclick="this.src='/captcha/subscription?'+Math.random()" title="点击图片重新获取验证码">
                        <button type="button" title="Subscribe" id="subFootCode" data-url="{{ route('feedbacks.store') }}">Subscribe Now</button>
                    </form>
                </div>
            </div>
            <div class="footer-share">
                <p>Follow us</p>
                <div class="footer-share-items">
                    <div class="footer-share-item">
                        <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                             data-title="Lyrical莱瑞美业">
                            <a href="javascript:void(0);" class="social-share-icon icon-facebook"></a>
                        </div>
                    </div>
                    <div class="footer-share-item">
                        <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                             data-title="Lyrical莱瑞美业">
                            <a href="javascript:void(0);" class="social-share-icon icon-linkedin"></a>
                        </div>
                    </div>
                    <div class="footer-share-item">
                        <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                             data-title="Lyrical莱瑞美业">
                            <a href="javascript:void(0);" class="social-share-icon icon-twitter"></a>
                        </div>
                    </div>
                    <div class="footer-share-item">
                        <div class="social-share" data-initialized="true" data-url="{{ config('app.url') }}"
                             data-title="Lyrical莱瑞美业">
                            <a href="javascript:void(0);" class="social-share-icon icon-google"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-top">
        <div class="m-wrapper">
            <div class="footer-top-center">
                <ul>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Company_Info')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('articles.show', ['slug' => 'about_us']) }}">@lang('app.About_Us')</a>
                            <a href="{{ route('articles.show', ['slug' => 'contact_us']) }}">@lang('app.Contact_Us')</a>
                            <a href="{{ route('articles.show', ['slug' => 'privacy_policy']) }}">@lang('app.Privacy_Policy')</a>
                            <a href="{{ route('articles.show', ['slug' => 'terms_and_conditions']) }}">@lang('app.Terms_and_Conditions')</a>
                            <a href="{{url('sitemap.html')}}">@lang('app.Sitemap')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Help_&_Support')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('articles.show', ['slug' => 'newbie_guide']) }}">@lang('app.Newbie_Guide')</a>
                            <a href="{{ route('articles.show', ['slug' => 'FAQs']) }}">@lang('app.FAQs')</a>
                            <a href="{{ route('articles.show', ['slug' => 'payment_methods']) }}">@lang('app.Payment_Methods')</a>
                            <a href="{{ route('articles.show', ['slug' => 'shipping_&_handling']) }}">@lang('app.Shipping_&_Handling')</a>
                            <a href="{{ route('articles.show', ['slug' => 'warranty_and_return']) }}">@lang('app.Warranty_and_Return')</a>
                            <a href="{{ route('articles.show', ['slug' => 'track_my_order']) }}">@lang('app.Track_My_Order')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Be_Our_Partner')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('articles.show', ['slug' => 'wholesale']) }}">@lang('app.Wholesale')</a>
                            <a href="{{ route('articles.show', ['slug' => 'drop_shipment']) }}">@lang('app.Drop_Shipment')</a>
                            <a href="{{ route('articles.show', ['slug' => 'news']) }}">@lang('app.News')</a>
                            <a href="{{ route('articles.show', ['slug' => 'videos']) }}">@lang('app.Videos')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Custom_Options')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('articles.show', ['slug' => 'base_design_guide']) }}">@lang('app.Base_Design_Guide')</a>
                            <a href="{{ route('articles.show', ['slug' => 'base_size']) }}">@lang('app.Base_Size')</a>
                            <a href="{{ route('articles.show', ['slug' => 'hair_style']) }}">@lang('app.Hair_Style')</a>
                            <a href="{{ route('articles.show', ['slug' => 'hair_texture']) }}">@lang('app.Hair_Texture')</a>
                            <a href="{{ route('articles.show', ['slug' => 'hair_density']) }}">@lang('app.Hair_Density')</a>
                            <a href="{{ route('articles.show', ['slug' => 'hair_type']) }}">@lang('app.Hair_Type')</a>
                            <a href="{{ route('articles.show', ['slug' => 'front_contour']) }}">@lang('app.Front_Contour')</a>
                            <a href="{{ route('articles.show', ['slug' => 'scallop_front']) }}">@lang('app.Scallop_Front')</a>
                        </div>
                    </li>
                    <li>
                        <p class="mobile-dropdown-menu"><span>@lang('app.Product_Help')</span><span class="iconfont">&#xe605;</span></p>
                        <div class="footer-block-content">
                            <a href="{{ route('articles.show', ['slug' => 'currency_rates']) }}">@lang('app.Currency_Rates')</a>
                            <a href="{{ route('articles.show', ['slug' => 'how_to_make_template']) }}">@lang('app.How_to_Make_Template')</a>
                            <a href="{{ route('articles.show', ['slug' => 'how_to_clean_toupee']) }}">@lang('app.How_to_Clean_Toupee')</a>
                            <a href="{{ route('articles.show', ['slug' => 'how_to_comb_the_hair']) }}">@lang('app.How_to_Comb_the_Hair')</a>
                            <a href="{{ route('articles.show', ['slug' => 'how_to_match_length']) }}">@lang('app.How_to_Match_Length')</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="m-wrapper">
            <ul class="friendship_link">
                <li>
                    <img src="{{ asset('img/footer-payment.png') }}">
                </li>
            </ul>
            <div class="pageurl_zb">
                <div style="text-align:center;">
                    <a href="#">A</a> |
                    <a href="#">B</a> |
                    <a href="#">C</a> |
                    <a href="#">D</a> |
                    <a href="#">E</a> |
                    <a href="#">F</a> |
                    <a href="#">G</a> |
                    <a href="#">H</a> |
                    <a href="#">I</a> |
                    <a href="#">J</a> |
                    <a href="#">K</a> |
                    <a href="#">L</a> |
                    <a href="#">M</a> |
                    <a href="#">N</a> |
                    <a href="#">O</a> |
                    <a href="#">P</a> |
                    <a href="#">Q</a> |
                    <a href="#">R</a> |
                    <a href="#">S</a> |
                    <a href="#">T</a> |
                    <a href="#">U</a> |
                    <a href="#">V</a> |
                    <a href="#">W</a> |
                    <a href="#">X</a> |
                    <a href="#">Y</a> |
                    <a href="#">Z</a> |
                    <a href="#">0-9</a>
                </div>
            </div>
            <p class="friendship_cop">2010-2019 Lyricalhair Co., Ltd. All Rights Reserved.<span> Technical Support : <a target="_blank" href="http://www.sahereman.com/">Sahereman</a></span></p>
        </div>
    </div>
</footer>
<!--右侧导航栏-->
<div class="right_navigation">
    <ul>
        <li class="backtop" title="@lang('app.Click to return to the top')">
            <img src="{{ asset('img/top_tip.png') }}">
        </li>
    </ul>
</div>
