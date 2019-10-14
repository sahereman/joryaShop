@extends('layouts.app')
@section('keywords', $product->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $product->seo_description ? : (App::isLocale('zh-CN') ? $product->description_zh : $product->description_en))
@section('og:image', $product->photo_urls[0])
@section('twitter:image', $product->photo_urls[0])
@section('title', $product->seo_title ? : (App::isLocale('zh-CN') ? $product->name_zh : $product->name_en) . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="commodity-details">
        <div class="m-wrapper container">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    @if($category->parent)
                        <span>/&nbsp;</span>
                        <a href="{{ route('seo_url', $category->parent->slug) }}">
                            {{ App::isLocale('zh-CN') ? $category->parent->name_zh : $category->parent->name_en }}
                        </a>
                    @endif
                    <span>/&nbsp;</span>
                    <a href="{{ route('seo_url', $category->slug) }}">{{ App::isLocale('zh-CN') ? $category->name_zh : $category->name_en }}</a>
                    <span>></span>
                    <a href="javascript:void(0);">{{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}</a>
                </p>
            </div>
            {{-- 社会化分享 --}}
            <div class="socialization">
                <div class="socialization-email">
                    <a href="javascript:void(0)" class="socialization-email-btn"><span class="iconfont">&#xe606;</span></a>
                </div>
                <div class="addthis_inline_share_toolbox"></div>
            </div>
            <!--详情上半部分-->
            <div class="commodity_parameters">
                <!--商品放大镜效果新版-->
                <div class="magnifierContainer">
                    <div class="product-img-column">
                        <div class="img-box img-box-style1">
                            @if($product->photo_urls)
                                <div id="surround">
                                    <div class="big-img-box">
                                        <img class="cloudzoom" alt ="Cloud Zoom small image" id ="zoom1" src="{{ $product->photo_urls[0] }}"
                                             data-cloudzoom='zoomSizeMode:"image",autoInside: true,tintOpacity:0,lensOpacity:0,zoomPosition:"inside",zoomMatchSize:true,zoomFullSize:true'>
                                        <a id="zoom-btn" class="lightbox-group  zoomColorBoxs zoom-btn-small"
                                            href="{{ $product->photo_urls[0] }}"
                                            title="">Zoom</a>
                                    </div>
                                    <div id="slider1">
                                        <div class="thumbelina-but horiz left">&#706;</div>
                                        <ul>
                                            @foreach($product->photo_urls as $key => $photo_url)
                                                <li>
                                                    @if ($key == 0)
                                                        <a class="firstzoomColorBoxs" href="{{ $photo_url }}">
                                                    @else
                                                        <a class="zoomColorBoxs" href="{{ $photo_url }}">
                                                    @endif
                                                        <img class='cloudzoom-gallery' src="{{ $photo_url }}"
                                                             data-cloudzoom ="useZoom:'.cloudzoom', image:'{{ $photo_url }}' ">
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li class="for-choose-img dis_ni">
                                                <a class="cboxElement" href="">
                                                    <img class='cloudzoom-gallery' src=""
                                                         data-cloudzoom ="useZoom:'.cloudzoom', image:'' ">
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="thumbelina-but horiz right">&#707;</div>
                                    </div>
                                </div>
                            @endif
                        </div><!-- end: img-box -->
                    </div>
                </div>
                <!--商品参数-->
                <div class="parameters_content">
                    {{-- 商品标题 --}}
                    <h4 class="forstorage_name" info_url="{{ $product->thumb_url }}" info_code="{{ $product->id }}"
                        info_href="{{ route('seo_url', $product->slug) }}">
                        {{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}
                    </h4>
                    {{-- 商品小标题介绍 --}}
                    <p class="small_title">{!! App::isLocale('zh-CN') ? $product->sub_name_zh : $product->sub_name_en !!}</p>
                    {{-- 价格服务模块 --}}
                    <div class="price_service dis_ni">
                        <p class="original_price">
                            <span>@lang('product.product_details.the original price')</span>
                            {{--<span id="sku_original_price_in_usd"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</span>--}}
                            <span id="sku_original_price_in_usd"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($product->price), 1.2, 2) }}</span>
                        </p>
                        <p class="present_price">
                            <span>@lang('product.product_details.the current price')</span>
                            {{--<span id="sku_price_in_usd" class="changePrice_num"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>--}}
                            <span id="sku_price_in_usd"
                                  class="changePrice_num"><i>{{ get_global_symbol() }} </i>{{ get_current_price($product->price) }}</span>
                        </p>
                        <p class="service">
                            <span>@lang('product.product_details.service')</span>
                            {{--<span class="service-kind"><i>•</i>@lang('product.product_details.multiple quantity')</span>--}}
                            <span class="service-kind">
                                <i>•</i>{{ $product->service }}
                            </span>
                            {{--<span class="service-kind"><i>•</i>@lang('product.product_details.Quick refund in 48 hours')</span>--}}
                        </p>
                        <p class="itemlocation">
                            <span class="itemlocation_span">Item Location</span>
                            {{--<span class="itemlocation_local"><i>•</i>@lang('product.product_details.multiple quantity')</span>--}}
                            <span class="itemlocation_local">
                                <i>•</i>{{ $product->location }}
                            </span>
                            {{--<span class="service-kind"><i>•</i>@lang('product.product_details.Quick refund in 48 hours')</span>--}}
                        </p>
                    </div>
                    {{-- 评价 --}}
                    <div class="ratings dis_ni">
                        <div class="rating-box">
                            {{-- 商品星级评价，
                            按照之前的设定分为：
                             1星：width:20%
                             2星：width:40%
                             3星：width:60%
                             4星：width:80%
                             5星：width:100% --}}
                            @if($product->comment_count == 0)
                                <div class="rating" style="width: 98%;"></div>
                            @else
                                <div class="rating" style="width: {{ (int)bcmul(bcdiv(bcdiv($product->index, $product->comment_count, 2), 5, 2), 100, 0) }}%;"></div>
                            @endif
                        </div>
                        <p class="rating-links">
                            <a id="goto-reviews" href="#customer-reviews">{{ $product->comment_count }} Review(s)</a>
                            <span class="separator">|</span>
                            <a id="goto-reviews-form" href="#customer-reviews">Add Your Review</a>
                        </p>
                    </div>
                    {{-- 简介 --}}
                    <div class="short-description">
                        <div class="std">
                            <textarea class="std-content" name="" id="topArticle" cols="30" readonly>{!! App::isLocale('zh-CN') ? $product->description_zh : $product->description_en !!}</textarea>
                        </div>
                        <a href="javascript:void(0)" class="down-more" id="down-more">
                            <img src=" {{ asset('img/down-more.png') }}" alt="">
                        </a>
                    </div>
                    {{-- 新版价格存放位置 --}}
                    <div class="product-price">
                        <p class="old-price">
                            <span class="price" id="old-price-695"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($product->price), 1.2, 2) }}</span>
                        </p>
                        <p class="special-price">
                            <span class="price" id="product-price-695"><i>{{ get_global_symbol() }} </i><span id="product-price">{{ get_current_price($product->price) }}</span></span>
                        </p>
                        <div class="clear"></div>
                        @if($shipment_template == null)
                            <div class="free-shipping">FREE SHIPPING</div>
                        @endif

                    </div>
                    {{-- 商品价格优惠 --}}
                    <ul class="tier-prices product-pricing">
                        @if($product->discounts->isNotEmpty())
                            @foreach($product->discounts as $discount)
                                <li class="tier-price tier-0">
                                    Buy<strong class="benefit"> {{ $discount->number }} for <span class="price">{{ get_global_symbol() . ' ' . get_current_price($discount->price) }}</span> each</strong>
                                    and&nbsp;<span>save&nbsp;<span class="percent tier-0">{{ $discount->discount }}</span>%</span>
                                    <span class="msrp-price-hide-message"></span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    {{-- 动态渲染的skus选择器存放位置 --}}
                    <div id="sku-choose-store" class="sku-choose-store {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}"></div>
                    {{-- skus参数数组 --}}
                    <input type="hidden" class="parameter-data" value="{{ json_encode($attributes) }}"/>
                    {{--<div class="availableSold {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}">--}}
                        {{--<button class="Reset-filter">Reset Select</button>--}}
                    {{--</div>--}}
                    {{-- 商品数量相关 --}}
                    <div class="priceOfpro {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}">
                        <span class="buy_numbers">@lang('product.product_details.Quantity'):</span>
                        <div class="quantity_control">
                            <span class="reduce no_allow"><i>-</i></span>
                            <input type="number" name="number" id="pro_num" value="1" min="1" max="99">
                            <span class="add"><i>+</i></span>
                        </div>
                    </div>
                    <!--添加购物车与立即购买-->
                    <div class="addCart_buyNow">
                        @if($product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                            <a class="buy_now for_show_login" href="{{ route('products.custom.show', ['product' => $product->id]) }}">
                                Customize it now
                            </a>
                            {{--<a class="add_carts for_show_login" href="{{ route('products.custom.show', ['product' => $product->id]) }}">--}}
                                {{--@lang('app.Add to Shopping Cart')--}}
                            {{--</a>--}}
                        @else
                            <a class="buy_now" data-url="{{ route('orders.pre_payment') }}">
                                @lang('product.product_details.Buy now')
                            </a>
                            <a class="add_carts" data-url="{{ route('carts.store') }}">
                                @lang('app.Add to Shopping Cart')
                            </a>
                            @guest
                                <a class="add_favourites for_show_login" >
                                    <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                    <span>Add to wish list</span>
                                </a>
                            @else
                                <a class="add_favourites {{ $favourite ? 'active' : '' }}" code="{{ $product->id }}"
                                   data-url="{{ route('user_favourites.store') }}"
                                   data-url_2="{{ route('user_favourites.destroy') }}"
                                   data-favourite-code="{{ $favourite ? $favourite->id : '' }}">
                                    {{--<span class="favourites_img"></span>--}}
                                    <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                    <span>{{ $favourite ? 'Remove from wish list' : 'Add to wish list' }}</span>
                                </a>
                            @endguest
                        @endif
                    </div>
                    {{-- 运费等介绍 --}}
                    <div class="shipping-detail">
                        {{--Shipping--}}
                        @if($shipment_template)
                            <div class="content-box shipping-info">
                                <div class="info-title">
                                    <span>Shipping:</span>
                                </div>
                                <div class="info-content">
                                    {{--{{dd()}}--}}
                                    <p> <span class="info-content-price">{{$shipment_template->calc_unit_shipping_fee(1,Auth::user()->default_address->province)}}
                                        </span>{{$shipment_template->name}}  {{$shipment_template->sub_name}} | <a class="info-content-details" href="javascrpt:void(0)">See details</a></p>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="info-content">
                                    <p>{{$shipment_template->description}}</p>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="info-content">
                                    <p>tem location:</p>
                                    <p>{{$product->location}}</p>
                                    <p>Ships to: <span>{{Auth::user()->default_address->province}}</span></p>
                                </div>
                            </div>
                        @endif
                        {{--Payments--}}
                        <div class="content-box payment-info">
                            <div class="info-title">
                                <span>Payments:</span>
                            </div>
                            <div class="info-content">
                                <img src="{{ asset('img/payment-all.png') }}" alt="">
                            </div>
                        </div>
                        {{--Return--}}
                        <div class="content-box return-info">
                            <div class="info-title">
                                <span>Return:</span>
                            </div>
                            <div class="info-content">
                                <p><span class="info-content-price">Free 30 day returns| </span><a class="info-content-details" href="javascrpt:void(0)">See details</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--详情下半部分-->
            <div class="comments_details">
                <div class="comments_details_right pull-left" id="comments_details">
                    <ul class="tab nav nav-tabs" role="tablist">
                        <li onclick="tabs('#comments_details',0)"
                            class="active curr">@lang('product.product_details.Commodity details')</li>
                        <li onclick="tabs('#comments_details',1)" class="shopping_eva"
                            data-url="{{ route('products.comment', ['product' => $product->id]) }}">@lang('product.product_details.Commodity feedback')
                        </li>
                        @if($product->faqs->isNotEmpty())
                        <li onclick="tabs('#comments_details',2)" class="comments_faqs">FAQS</li>
                        @endif
                    </ul>
                    <div class="mc tabcon product_info">
                        {{--商品详情部分iframe--}}
                        <div class="iframe_content dis_ni">
                            {{-- 用来存放后台返回的的iframe的数据或者富文本 --}}
                            {!! $product->content_en !!}
                        </div>
                        {{-- 页面实际展示的部分，用js进行页面渲染 --}}
                        <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>
                    </div>
                    <div class="mc tabcon dis_n" id="customer-reviews">
                        <div class="comment-items">
                            <!--暂无评价-->
                            <div class="no_eva dis_n">
                                <p>@lang('product.product_details.No evaluation information yet')</p>
                            </div>
                            <dl></dl>
                        </div>
                        <!--分页-->
                        <div class="paging_box">
                            <a class="pre_page" href="javascript:void(0);">@lang('app.Previous page')</a>
                            <a class="next_page" href="javascript:void(0);">@lang('app.Next page')</a>
                        </div>
                    </div>
                    @if($product->faqs->isNotEmpty())
                        <div class="mc tabcon dis_n faqs-content">
                            @foreach($product->faqs as $key => $faq)
                                <div class="faq_qus_ans">
                                    <h3 class="faq_ques">
                                        {{ ($key + 1) . '. ' . $faq->question }}
                                        <span class="iconfont">&#xe60f;</span>
                                    </h3>
                                    <div class="faq_ans">
                                        <textarea class="faq_ans_content" name="" id="ans_content_{{ $key }}" cols="30" readonly>{{ $faq->answer }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            {{-- 友情推荐 --}}
            <div class="box-additional">
                <div class="box-additional-title">
                    <h2>Related Products</h2>
                    <div class="swiper-butrton-box">
                        <div class="swiper-button-prev swiper-button-black"></div>
                        <div class="swiper-button-next swiper-button-black"></div>
                    </div>
                </div>
                <div class="carousel-content">
                    <div class="swiper-container banner" id="carousel">
                        <div class="swiper-wrapper">
                            @foreach($guesses as $key => $guess)
                                @if($key % 3 == 0)
                                    <div class="swiper-slide">
                                @endif
                                        <div class="swiper-slide-item">
                                            <div class="product-image">
                                                <a href="{{ route('seo_url', $guess->slug) }}">
                                                    <img src="{{ $guess->thumb_url }}">
                                                </a>
                                            </div>
                                            <div class="product-details">
                                                <h3 class="product-name">{{$guess->name_en}}</h3>
                                                <div class="price-box">
                                                    <span class="original_price"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($guess->price), 1.2, 2) }}</span>
                                                    <span class="present_price"><i>{{ get_global_symbol() }} </i>{{ get_current_price($guess->price) }}</span>
                                                </div>
                                                {{--<a class="related-add-to-wishlist" href="javascript:void(0);">Add to Wishlist</a>--}}
                                            </div>
                                        </div>
                                @if($key % 3 == 2 || $key == count($guesses))
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 社会化分享弹窗(邮件) --}}
    <div class="social-email dis_n" id="social-email">
        <div class="social-email-content">
            <div class="product-share-info">
                <div class="product-share-img">
                    <img src="" alt="">
                </div>
                <div class="product-share-title">
                    <h4></h4>
                    <p></p>
                </div>
                <div class="product-share-price">
                    <p class="share-special-price share-price">
                        <span></span>
                    </p>
                </div>
            </div>
            <h2>Email This</h2>
            <ul class="simple-form">
                <li>
                    <label for="social-email-inp-to">To:</label>
                    <input type="email" id="social-email-inp-to" data-url="{{ route('products.share', ['product' => $product->id]) }}" placeholder="your-friend@example.com">
                </li>
                <li>
                    <label for="social-email-inp-from">From:</label>
                    <input type="email" id="social-email-inp-from" placeholder="your-name@example.com" value="{{Auth::user() ? Auth::user()->email : ''}}">
                </li>
                <li>
                    <label for="social-email-inp-subject">Subject:</label>
                    <input type="text" id="social-email-inp-subject" value="A LYRICALHAIR.COM customer thinks you’ll love this product from LYRICALHAIR.COM!">
                </li>
                <li>
                    <p>
                        <label for="social-email-inp-body">Body:</label>
                    </p>
                    <textarea id="social-email-inp-body" cols="30" rows="10" >I love this product on LYRICALHAIR.COM and thought you might too!</textarea>
                </li>
                <li>
                    <p>URL being shared:</p>
                    <p id="URLshared"></p>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5d3faaaad4206199"></script>
    <script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script src="{{ asset('js/lord/jquery.colorbox.min.js') }}"></script>
    <script src="{{ asset('js/lord/jquery.owlcarousel.min.js') }}"></script>
    <script src="{{ asset('js/lord/cloudzoom.js') }}"></script>
    <script src="{{ asset('js/lord/thumbelina.js') }}"></script>

    <script type="text/javascript">
        {{-- 初始化zoom --}}
        CloudZoom.quickStart();
        // 初始化slider
        $(function(){
            $('#slider1').Thumbelina({
                $bwdBut:$('#slider1 .left'),
                $fwdBut:$('#slider1 .right')
            });
        });
        //Init lightbox  图片弹窗
        $(".zoomColorBoxs").colorbox({
            rel: 'zoomColorBoxs',
            opacity:0.5,
            speed: 300,
            current: '{current} / {total}',
            previous: '',
            next: '',
            close: '',  //No comma here
            maxWidth: '95%',
            maxHeight: '95%'
        });
        // 简介查看更多
        $("#down-more").on("click",function () {
            var _taht = $(this),
                element = document.getElementById("topArticle"),
                isHasClass = $(this).hasClass("active");
            if (isHasClass){
                _taht.removeClass("active");
                $(".std").find("textarea").removeClass("active");
                // element.style.height = element.scrollHeight + "px";
                element.style.height = "38px";
            } else {
                _taht.addClass("active");
                $(".std").find("textarea").addClass("active");
                element.style.height = element.scrollHeight + "px";
            }
        });
        // 社会化分享弹窗
        $(".socialization-email-btn").on("click",function () {
            var clickDom = $("#social-email-inp");
            // 将页面可获取的内容进行赋值
            $(".product-share-img").find("img").prop("src",$("#zoom1").prop("src"));
            $(".product-share-title").find("h4").html($(".forstorage_name").html());
            $(".product-share-title").find("p").html($(".small_title").html());
            $(".share-special-price").find("span").html($(".special-price").find("span").html());
            $("#URLshared").html(window.location.href);
            layer.open({
                title: '',
                type: 1,
                shadeClose: true,
                area: ['auto', '80%'],
                content: $('#social-email'),
                btn: ['Submit'],
                yes: function(index){
                    $.ajax({
                        type: "post",
                        url: $("#social-email-inp-to").attr("data-url"),
                        data: {
                            _token: "{{ csrf_token() }}",
                            // email: clickDom.val()
                            to_email: $("#social-email-inp-to").val(),
                            from_email: $("#social-email-inp-from").val(),
                            subject: $("#social-email-inp-subject").val(),
                            body: $("#social-email-inp-body").val()
                        },
                        success: function (data) {
                            layer.msg("Mail sharing success");
                        },
                        error: function (err) {
                            if (err.status == 422) {
                                var arr = [];
                                var dataobj = err.responseJSON.errors;
                                for (let i in dataobj) {
                                    arr.push(dataobj[i]); //属性
                                }
                                layer.msg(arr[0][0]);
                            }
                        },
                    });
                    layer.close(index);
                }
            });
        });
        // 友情链接
        var swiper = new Swiper('#carousel', {
            centeredSlides: true,
            loop: true,
            speed: 200,
            effect : 'slide',
            preventLinksPropagation: true,
            fadeEffect: {
                crossFade: true,
            },
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        var loading_animation;  // loading动画的全局name
        var current_page;  // 评价的当前页
        var next_page;   // 下一页的页码
        var pre_page;   // 上一页的页码
        var country = $("#dLabel").find("span").html();
        var sku_id = 0, sku_stock = 0, sku_price = 0, sku_original_price = 0;
        var product = {!! $product !!};
        // 控制商品下单的数量显示
        $(".add").on("click", function () {
            // 获取商品ID及库存数量
            if(haschoose == false){
                layer.msg("Please Select");
                return
            }
            if(sku_id == 0||sku_stock == 0){
                layer.msg("The item is temporarily out of stock Please reselect!");
                return
            }
            // if ($(".kindOfPro").find("li").hasClass('active') != true) {
            // layer.msg("@lang('product.product_details.Please select specifications')");
            // } else {
            $(".reduce").removeClass('no_allow');
            if (parseInt($("#pro_num").val()) < sku_stock) {
                var num = parseInt($("#pro_num").val()) + 1;
                $("#pro_num").val(num);
            } else {
                layer.msg("@lang('order.Cannot add more quantities')");
            }
        });
        $(".reduce").on("click", function () {
            if ($(this).hasClass('no_allow') != true && $("#pro_num").val() > 1) {
                var num = parseInt($("#pro_num").val()) - 1;
                if (num == 1) {
                    $("#pro_num").val(1);
                    $(this).addClass('no_allow');
                } else {
                    $("#pro_num").val(num);
                }
            }
        });
        // 点击添加收藏
        $(".add_favourites").on("click", function () {
            var clickDom = $(this), data, url;
            if(clickDom.hasClass("for_show_login")) {
                if(!clickDom.hasClass("active")) {
                    clickDom.find("span").text("Remove from wish list");
                    layer.msg("Added to wish list successfully");
                    clickDom.addClass("active");
                }else {
                    clickDom.find("span").text("Add to wish list");
                    layer.msg("Remove success from wish list");
                    clickDom.removeClass("active");
                }
                return
            }
            if (clickDom.hasClass('active') != true) {
                data = {
                    _token: "{{ csrf_token() }}",
                    product_id: clickDom.attr("code"),
                };
                url = clickDom.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        layer.msg("Added to wish list successfully");
                        clickDom.find("span").text("Remove from wish list");
                        clickDom.attr("data-favourite-code",data.data.favourite.id);
                        clickDom.addClass('active');
                    },
                    error: function (err) {
                        if (err.status == 422) {
                            var arr = [];
                            var dataobj = err.responseJSON.errors;
                            for (let i in dataobj) {
                                arr.push(dataobj[i]); //属性
                            }
                            layer.msg(arr[0][0]);
                        }
                    },
                });
            } else {
                data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                    favourite_id: clickDom.attr("data-favourite-code")
                };
                url = clickDom.attr('data-url_2');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        clickDom.find("span").text("Add to wish list");
                        layer.msg("Remove success from wish list");
                        clickDom.removeClass('active');
                    },
                    error: function (err) {
                        if (err.status == 422) {
                            var arr = [];
                            var dataobj = err.responseJSON.errors;
                            for (let i in dataobj) {
                                arr.push(dataobj[i]); //属性
                            }
                            layer.msg(arr[0][0]);
                        }
                    },
                });
            }
        });
        // Tab控制函数
        function tabs(tabId, tabNum) {
            //设置点击后的切换样式
            $(tabId + " .tab li").removeClass("curr");
            $(tabId + " .tab li").eq(tabNum).addClass("curr");
            //根据参数决定显示内容
            $(tabId + " .tabcon").hide();
            $(tabId + " .tabcon").eq(tabNum).show();
            if (tabNum == 1) {
                var url = $(".shopping_eva").attr("data-url");
                getComments(url);
            }
        }
        // 切换
        $(".kindOfPro").on("click", "li", function () {
            $(".kindOfPro").find('li').removeClass("active");
            $(this).addClass('active');
            $(".changePrice_num").html("{{ get_global_symbol() }}" + $(this).attr('code_price'));
            $("#pro_num").val("1");
        });
        // 加入购物车
        $(".add_carts").on("click", function () {
            var clickDom = $(this);
            if(product.type == "custom") {
                window.location.href = "{{ route('products.custom.show', ['product' => $product->id]) }}";
            }else {
                if(haschoose == false){
                    layer.msg("Please Select");
                    return
                }
                if(sku_id == 0||sku_stock == 0){
                    layer.msg("The item is temporarily out of stock Please reselect!");
                    return
                }
            }
            var data = {
                _token: "{{ csrf_token() }}",
                sku_id: sku_id,
                number: $("#pro_num").val(),
            };
            var url = clickDom.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    layer.msg("@lang('product.product_details.Shopping cart added successfully')");
                    var oldCartNum = parseInt($(".shop_cart_num").html());
                    var newCartNum = oldCartNum + parseInt($("#pro_num").val())
                    $(".shop_cart_num").html(newCartNum);
                    // $(".for_cart_num").load(location.href + " .shop_cart_num");
                },
                error: function (err) {
                    var arr = [];
                    var dataobj = err.responseJSON.errors;
                    for (let i in dataobj) {
                        arr.push(dataobj[i]); //属性
                    }
                    layer.msg(arr[0][0]);
                }
            });
        });
        // 立即购买
        $(".buy_now").on("click", function () {
            var clickDom = $(this);
            if ($(this).hasClass('for_show_login') == true) {
            //     // $(".login").click();
            } else {
                var url = clickDom.attr('data-url');
                if(haschoose == false){
                    layer.msg("Please Select");
                    return
                }
                // getSkuId();
                if(sku_id == 0||sku_stock == 0){
                    layer.msg("The item is temporarily out of stock Please reselect!");
                    return
                }
                window.location.href = url + "?sku_id=" + sku_id + "&number=" + $("#pro_num").val() + "&sendWay=1";
            }
        });
        // 获取评价内容
        function getComments(url) {
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    loading_animation = layer.msg("@lang('app.Please wait')", {
                        icon: 16,
                        shade: 0.4,
                        time: false, // 取消自动关闭
                    });
                },
                success: function (json) {
                    var dataObj = json.data.comments.data;
                    if (dataObj.length <= 0) {
                        $(".no_eva").removeClass('dis_n');
                        $(".comment-score h3").text("0.0");
                        $(".pre_page").addClass("dis_ni");
                        $(".next_page").addClass("dis_ni");
                    } else {
                        var html = "";
                        $.each(dataObj, function (i, n) {
                            html+="<dt>"
                            html+="<span class='heading'>"+ n.title +"</span>Review by <span>"+ n.user.name  +"</span>"
                            html+="</dt>"
                            html+="<dd>"
                            html+="<table class='ratings-table'>"
                            html+="<colgroup><col width='1'>"
                            html+="<col>"
                            html+="</colgroup><tbody>"
                            html+="<tr>"
                            html+="<th>Product Rating</th>"
                            html+="<td>"
                            html+="<div class='rating-box'>"
                            html+="<div class='rating' style='width:"+ n.index +"%;'></div>"
                            html+="</div>"
                            html+="</td>"
                            html+="</tr>"
                            html+="</tbody>"
                            html+="</table>"
                            html+="<p>"+ n.content +"</p>"
                            html+="<small class='date'>(Posted on "+ n.created_at +")</small>"
                            html+="</dd>"
                        });
                        $(".comment-items dl").html("");
                        $(".comment-items dl").append(html);
                        $(".pre_page").removeClass("dis_ni");
                        $(".next_page").removeClass("dis_ni");
                        $(".pre_page").attr("data-url", json.data.comments.first_page_url);
                        $(".next_page").attr("data-url", json.data.comments.next_page_url);
                        if (json.data.previous_url == false) {
                            $(".pre_page").addClass("not_allow");
                            $(".pre_page").attr("disabled", true);
                        }else {
                            $(".pre_page").removeClass("not_allow");
                            $(".pre_page").attr("disabled", false);
                        }
                        if (json.data.next_url == false) {
                            $(".next_page").addClass("not_allow");
                            $(".next_page").attr("disabled", true);
                        }else {
                            $(".next_page").removeClass("not_allow");
                            $(".next_page").attr("disabled", false);
                        }
                    }
                },
                error: function (e) {
                    if (err.status == 422) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (let i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                },
                complete: function () {
                    layer.close(loading_animation);
                }
            });
            // 放大镜的缩略图的上一页与下一页
        }
        // 点击分页
        // 上一页
        $(".pre_page").on("click", function () {
            if(!$(this).hasClass("not_allow")){
                getComments($(this).attr("data-url"));
            }
        });
        // 下一页
        $(".next_page").on("click", function () {
            if(!$(this).hasClass("not_allow")){
                getComments($(this).attr("data-url"));
            }
        });
        // FAQS的标题点击
        $(".faqs-content").on("click",".faq_ques",function () {
            var clickDom = $(this),
                index = clickDom.parent(".faq_qus_ans").index();
            if(clickDom.hasClass("active")){
                clickDom.removeClass("active");
                clickDom.parent(".faq_qus_ans").find(".faq_ans").slideUp();
            }else {
                // $(".faqs-content").find(".faq_ques").removeClass("active");
                // $(".faqs-content").find(".faq_ans").slideUp();
                clickDom.addClass("active");
                clickDom.parent(".faq_qus_ans").find(".faq_ans").slideDown();
                var element = document.getElementById("ans_content_"+ index);
                element.style.height = element.scrollHeight + "px";
            }
        });
        // 原价计算
        // var old_price = js_number_format(Math.imul(float_multiply_by_100(origin_price), 12) / 1000);
        {{--$("#sku_original_price_in_usd").html("<i>{{ get_global_symbol() }}</i> " + old_price);--}}
        // 数据选择器兼容性处理
        if (!Array.prototype.filter) {
            Array.prototype.filter = function (fn, context) {
                var i,
                    value,
                    result = [],
                    length;

                if (!this || typeof fn !== 'function' || (fn instanceof RegExp)) {
                    throw new TypeError();
                }
                length = this.length;
                for (i = 0; i < length; i++) {
                    if (this.hasOwnProperty(i)) {
                        value = this[i];
                        if (fn.call(context, value, i, this)) {
                            result.push(value);
                        }
                    }
                }
                return result;
            };
        }
        var _findItemByValue = function (obj, prop, value) {
            return obj.filter(function (item) {
                return (item[prop] === value);
            });
        };
        // 数组去重
        function unique(arr) {
            var new_arr = arr.filter(function (element, index, self) {
                return self.indexOf(element) === index;
            });
            return new_arr;
        }
        // 数据计算方法
        function float_multiply_by_100(float) {
            float = String(float);
            // float = float.toString();
            var index_of_dec_point = float.indexOf('.');
            if (index_of_dec_point == -1) {
                float += '00';
            } else {
                var float_splitted = float.split('.');
                var dec_length = float_splitted[1].length;
                if (dec_length == 1) {
                    float_splitted[1] += '0';
                } else if (dec_length > 2) {
                    float_splitted[1] = float_splitted[1].substring(0, 1);
                }
                float = float_splitted.join('');
            }
            return Number(float);
        }
        function js_number_format(number) {
            number = String(number);
            var index_of_dec_point = number.indexOf('.');
            if (index_of_dec_point == -1) {
                number += '.00';
            } else {
                var number_splitted = number.split('.');
                var dec_length = number_splitted[1].length;
                if (dec_length == 1) {
                    number += '0';
                } else if (dec_length > 2) {
                    number_splitted[1] = number_splitted[1].substring(0, 2);
                    number = number_splitted.join('.');
                }
            }
            return number;
        }
        // 页面加载时将商品信息存储到localstorage中，方便之后进行调取
        // 判断浏览器是否支持 localStorage 属性
        var hisProductOld = [],
            hisProductNew = [],
            trimArray = []; // 用于数组去重
        // 页面加载时对本地缓存数据进行处理
        setStorageOption();
        function setStorageOption() {
            if (window.localStorage) {
                // 支持localstorage的浏览器便把商品信息存储到localstorage中方便调用，不超过5~10个,超出的个数按照时间顺序删除
                // 获取当前商品的相关信息并保存为一个商品对象
                var Currentcommodity = {
                    id: $(".forstorage_name").attr("info_code"),
                    name: $(".forstorage_name").text().replace(/(^\s*)|(\s*$)/g, ""),
                    photo_url: $(".forstorage_name").attr("info_url"),
                    sku_price_in_usd: $("#sku_price_in_usd").text(),
                    sku_original_price_in_usd: $("#sku_original_price_in_usd").text(),
                    product_href: $(".forstorage_name").attr("info_href")
                };
                if (JSON.parse(window.localStorage.getItem('historyProduct')) != null) {
                    hisProductOld = JSON.parse(window.localStorage.getItem('historyProduct'));
                }
                var num = 0;
                if (hisProductOld.length - 1 > 0) {
                    num = hisProductOld.length - 1
                }
                if (hisProductOld.length == 0) {
                    hisProductOld.push(Currentcommodity);
                } else {
                    if (hisProductOld[num].id != $(".forstorage_name").attr("info_code")) {
                        for (var i = 0; i <= hisProductOld.length - 1; i++) {
                            if ($(".forstorage_name").attr("info_code") == hisProductOld[i].id) {
                                hisProductOld.splice(jQuery.inArray(hisProductOld[i], hisProductOld), 1);
                            }
                        }
                        hisProductOld.push(Currentcommodity);
                    }
                }
                window.localStorage.setItem('historyProduct', JSON.stringify(hisProductOld));
                if (hisProductOld.length != 0) {
                    var html = "";
                    if (hisProductOld.length > 10) {
                        hisProductNew = hisProductOld.slice(hisProductOld.length - 10);
                    } else {
                        hisProductNew = hisProductOld;
                    }
                    window.localStorage.setItem('historyProduct', JSON.stringify(hisProductNew));
                    hisProductOld = hisProductOld.reverse();
                    $.each(hisProductOld, function (i, n) {
                        html += "<li>" +
                                "<a href='" + n.product_href + "'>" +
                                "<div>" +
                                "<img class='lazy' data-src='" + n.photo_url + "'>" +
                                "</div>" +
                                "<p>" +
                                "<span class='present_price'>" + n.sku_price_in_usd + "</span>" +
                                "</p>" +
                                "<p>" +
                                "<span class='presenthis_name' title='" + n.name + "'>" + n.name + "</span>" +
                                "</p>" +
                                "</a>" +
                                "</li>";
                    });
                    $(".comments_details_left .pro-lists").html("");
                    $(".comments_details_left .pro-lists").append(html);
                } else {
                    $(".browseFootprints").addClass("dis_n");
                }
            } else {
                $(".browseFootprints").addClass("dis_n");
            }
        }
        // 商品详情iframe
        var iframe_content = $('.iframe_content').html();
        $('.iframe_content').html("");
        $('#cmsCon').contents().find('body').html(iframe_content);
        $('#cmsCon').contents().find('body').find("a").css("text-decoration","none");
        var x = document.getElementById('cmsCon').contentWindow.document.getElementsByTagName('table');   
        x.border = "1";   
        autoHeight();  //动态调整高度
        var count = 0;
        var autoSet = window.setInterval('autoHeight()',500);
        function autoHeight(){
            var mainheight;
            count++;
            if(count == 1){
                mainheight = $('.cmsCon').contents().find("body").height()+50;
            }else{
                mainheight = $('.cmsCon').contents().find("body").height()+24;
            }
            $('.cmsCon').height(mainheight);
            if(count == 5){
                window.clearInterval(autoSet);
            }
        }

        // 模拟接口返回的数据
        /* tslint:disable */
        var originalData = {
            "brandName": "Apple",
            "title": "AppleiPhone8移动联通电信4G手机",
            "skuParamVoList": [{
                "paramId": "6977",
                "paramValue": "成色",
                "valueList": [{
                    "valueId": "1081969",
                    "valueValue": "全新"
                }, {
                    "valueId": "1080699",
                    "valueValue": "仅拆封"
                }]
            }, {
                "paramId": "6975",
                "paramValue": "颜色",
                "valueList": [{
                    "valueId": "730003",
                    "valueValue": "深空灰色"
                }, {
                    "valueId": "730004",
                    "valueValue": "银色"
                }, {
                    "valueId": "730005",
                    "valueValue": "金色"
                }]
            }, {
                "paramId": "7335",
                "paramValue": "配置",
                "valueList": [{
                    "valueId": "710004",
                    "valueValue": "64G"
                }, {
                    "valueId": "710006",
                    "valueValue": "256G"
                }]
            }, {
                "paramId": "72",
                "paramValue": "版本",
                "valueList": [{
                    "valueId": "1080627",
                    "valueValue": "国行"
                }, {
                    "valueId": "1080628",
                    "valueValue": "港澳版"
                }, {
                    "valueId": "1080697",
                    "valueValue": "日韩"
                }, {
                    "valueId": "1080629",
                    "valueValue": "其他版本"
                }]
            }],
            "allSkuVoList": [{
                "spudId": "117125101975042",
                "stock": "0",
                "minPrice": "23",
                "maxPrice": "2323",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1171251101969410",
                "stock": "0",
                "minPrice": "23",
                "maxPrice": "45",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171189301977602",
                "stock": "0",
                "minPrice": "12",
                "maxPrice": "4345",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "11712511892",
                "stock": "0",
                "minPrice": "123",
                "maxPrice": "9032",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171289297775618",
                "stock": "0",
                "minPrice": "123",
                "maxPrice": "3434",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "111189297778690",
                "stock": "0",
                "minPrice": "3234",
                "maxPrice": "6677",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "117125118930196",
                "stock": "2",
                "minPrice": "6088",
                "maxPrice": "6888",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1189301974018",
                "stock": "0",
                "minPrice": "7800",
                "maxPrice": "8800",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1251189297781250",
                "stock": "0",
                "minPrice": "3456",
                "maxPrice": "4567",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11751189301968898",
                "stock": "0",
                "minPrice": "923",
                "maxPrice": "3994",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "117125118929778",
                "stock": "0",
                "minPrice": "1233",
                "maxPrice": "4566",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11712517783298",
                "stock": "0",
                "minPrice": "8922",
                "maxPrice": "9332",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11789301976066",
                "stock": "0",
                "minPrice": "1234",
                "maxPrice": "4322",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "117125162",
                "stock": "0",
                "minPrice": "6789",
                "maxPrice": "7891",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "117301970946",
                "stock": "0",
                "minPrice": "3234",
                "maxPrice": "32345",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1189301979650",
                "stock": "0",
                "minPrice": "1293",
                "maxPrice": "4758",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "19301976578",
                "stock": "0",
                "minPrice": "12",
                "maxPrice": "3333",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1171251189301971",
                "stock": "0",
                "minPrice": "3923",
                "maxPrice": "9484",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1189301971970",
                "stock": "0",
                "minPrice": "8394",
                "maxPrice": "9222",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1171251178",
                "stock": "30",
                "minPrice": "3258",
                "maxPrice": "3298",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "117125175554",
                "stock": "0",
                "minPrice": "334",
                "maxPrice": "778",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1189301980674",
                "stock": "0",
                "minPrice": "3239",
                "maxPrice": "9933",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "251189301972994",
                "stock": "0",
                "minPrice": "2345",
                "maxPrice": "3234",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "301969922",
                "stock": "0",
                "minPrice": "3234",
                "maxPrice": "9999",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "11189297781762",
                "stock": "0",
                "minPrice": "234",
                "maxPrice": "90834",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1189301979138",
                "stock": "0",
                "minPrice": "2364",
                "maxPrice": "8736",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "117125118986",
                "stock": "0",
                "minPrice": "2364",
                "maxPrice": "8465",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "11189301981698",
                "stock": "0",
                "minPrice": "2345",
                "maxPrice": "9445",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "1171189297777666",
                "stock": "0",
                "minPrice": "334",
                "maxPrice": "445",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171301973506",
                "stock": "0",
                "minPrice": "2394",
                "maxPrice": "4859",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "189297784322",
                "stock": "0",
                "minPrice": "3456",
                "maxPrice": "3949",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "197779202",
                "stock": "0",
                "minPrice": "8934",
                "maxPrice": "9999",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171775106",
                "stock": "0",
                "minPrice": "2346",
                "maxPrice": "9933",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11797776130",
                "stock": "0",
                "minPrice": "5554",
                "maxPrice": "7778",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171251189",
                "stock": "0",
                "minPrice": "8883",
                "maxPrice": "9998",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11189297783810",
                "stock": "0",
                "minPrice": "1283",
                "maxPrice": "6374",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080629"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1189297782786",
                "stock": "0",
                "minPrice": "2384",
                "maxPrice": "9993",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "7777154",
                "stock": "0",
                "minPrice": "8833",
                "maxPrice": "9222",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "189301978114",
                "stock": "21",
                "minPrice": "4558",
                "maxPrice": "4598",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "7771251189297774594",
                "stock": "0",
                "minPrice": "834",
                "maxPrice": "9934",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1301974530",
                "stock": "0",
                "minPrice": "2394",
                "maxPrice": "8883",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "51189301977090",
                "stock": "0",
                "minPrice": "7845",
                "maxPrice": "43434",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080697"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "297782274",
                "stock": "41",
                "minPrice": "3258",
                "maxPrice": "3308",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730005"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "117129297776642",
                "stock": "0",
                "minPrice": "7485",
                "maxPrice": "9983",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "11718414466",
                "stock": "30",
                "minPrice": "3268",
                "maxPrice": "3308",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "111189301972482",
                "stock": "0",
                "minPrice": "34895",
                "maxPrice": "99993",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }, {
                "spudId": "11797780226",
                "stock": "0",
                "minPrice": "2389",
                "maxPrice": "6663",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1080699"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }, {
                "spudId": "1171251301970434",
                "stock": "0",
                "minPrice": "9433",
                "maxPrice": "9922",
                "spudParams": [{
                    "paramId": "72",
                    "valueId": "1080628"
                }, {
                    "paramId": "6975",
                    "valueId": "730003"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710006"
                }]
            }],
            "mySkuVoDetail": {
                "spudId": "117125118178",
                "stock": "30",
                "minPrice": "3258",
                "mySkuVoList": [{
                    "paramId": "72",
                    "valueId": "1080627"
                }, {
                    "paramId": "6975",
                    "valueId": "730004"
                }, {
                    "paramId": "6977",
                    "valueId": "1081969"
                }, {
                    "paramId": "7335",
                    "valueId": "710004"
                }]
            }
        }

        // 将原始数据进行处理
        // 用于连接数组下标的分隔符
        var joinKVStr = '_';
        var joinAttrStr = '__';
        // 判断链接符的正则
        var joinAttrStrRe = new RegExp(joinAttrStr, 'g');
        // sku 相关参数
        var skuDomHtml = "";  // 用于渲染sku选择器的页面虚拟节点
        var fatherDomBox = $(".sku-choose-store");
        // sku分类数组及渲染到页面上，sku大分类及各分类下的子选项
        var skuKindsArr = originalData.skuParamVoList;
        // 暂不明了
        var mySkuVoDetail = originalData.mySkuVoDetail;
        // 对sku大分类及各分类下的子选项进行处理，生成组合
        // 活跃状态的sku
        // 应该置灰不可点击的按钮
        var emptyMap = {}
        var activeSkuTagMap = {};
        // 已经选中的属性信息
        var hasSelectedList = [];
        // 当前选中的 sku属性对应的价格
        // 当前选中的 sku属性总库存
        var currentSeletedPrice = 0;
        var currentTotalCount = 0;
        var skuRankList = [];
        // 当前选择的 sku属性对应的库存、价格、spuDId等信息
        var currentSelectSkuRst = {};
        var SkuManage = {
            // 全部 sku 排列组合的数据
            skuRankList: [],
            // sku 的组合源
            skuParamVoList: skuKindsArr,
            // 在 skuRankList中，所有包含 sku 每一商品属性（例如黑色）的数据项的下标的集合
            // 例如：{ 10_100__20_200: [0, 1, 2, 3, 4, 5] }
            keyRankMap: {},
            // 任意选择状态下的商品库存和价格信息 Map
            indexKeyInfoMap: {},
            // 当没有选择任何 sku属性时，库存为 0 的 sku属性
            // 即总库存为 0 的单个 sku属性
            // 例如：['10_100']，表示 paramId = 10，valueId = 100 的 sku属性库存为 0
            emptySkuMap: [],
            // 所有库存为 0 的 sku中，包括的 sku属性的集合，用于优化算法
            // 例如，10_101__20_201的库存为 0，则此值为 ['10_101', '20_201']
            // 如果此数组长度为 0，说明不存在库存为 0 的 sku，后续就无需考虑置灰的情况，因为所有的 sku的库存都是大于 0 的，都是可选的
            emptySkuIncludeList: []
        };
        // 数组去重
        function uniqueArr(arr) {
            return arr.reduce(function (t, c) {
                return t.includes(c) ? t : t.concat(c);
            }, []);
        }
        // 对原始数组进行计算输出skuRankList
        function computeSkuData (){
            var spudSortParams = null;
            /* tslint:disable */
            SkuManage.skuRankList = Object.freeze(originalData.allSkuVoList.map(function (item) {
                // 按照 paramId 从小到大排序
                spudSortParams = item.spudParams.sort(function (a, b) { return a.paramId - b.paramId; });
                return {
                    spuDId: item.spudId,
                    paramIdJoin: spudSortParams.map(function (v) { return v.paramId + joinKVStr + v.valueId; }).join(joinAttrStr),
                    priceRange: [item.minPrice, item.maxPrice],
                    count: +item.stock
                };
            }));
        }
        // 计算在 skuRankList中，所有包含 sku 每一商品属性（例如黑色）的数据项的下标的集合
        function computeKeyRankMap(){
            var skuRankList = SkuManage.skuRankList;
            var skuRankListLen = skuRankList.length;
            var skuParamVoListLen = SkuManage.skuParamVoList.length;
            var valueItem = null;
            var keyRankMapKey = null;
            for (var i = 0; i < skuParamVoListLen; i++) {
                valueItem = SkuManage.skuParamVoList[i].valueList;
                for (var j = 0; j < valueItem.length; j++) {
                    keyRankMapKey = SkuManage.skuParamVoList[i].paramId + joinKVStr + valueItem[j].valueId;
                    for (var k = 0; k < skuRankListLen; k++) {
                        if (skuRankList[k].paramIdJoin.includes(keyRankMapKey)) {
                            if (!SkuManage.keyRankMap[keyRankMapKey]) {
                                SkuManage.keyRankMap[keyRankMapKey] = [];
                            }
                            SkuManage.keyRankMap[keyRankMapKey] = SkuManage.keyRankMap[keyRankMapKey].concat(k);
                        }
                    }
                }
            }
        }
        /**
         * 根据 paramId 从小到大的顺序，进行数组插入
         * @param arr 例如：['10_100', '30_300']
         * @param newKey 例如 '20_201'
         * @example sortByParamId(['10_100', '30_300'], '20_201') => ['10_100', '20_201', '30_300']
         */
        function sortByParamId(arr,newKey){
            var itemParamId = +newKey.split(joinKVStr)[0];
            var i = 0;
            for (; i < arr.length; i++) {
                if (+arr[i].split(joinKVStr)[0] > itemParamId) {
                    break;
                }
            }
            arr.splice(i, 0, newKey);
            return arr;
        }
        /**
         * 求数组交集，每个数组的数据项只能是数字，并且每个数组都要是排好序的，算法优化的需要
         * @param params 需要求交集的数组，例如 intersectionSortArr([2, 3, 7, 8], [3, 7, 9, 12, 18, 20], [7, 16, 18])
         */
        function intersectionSortArr() {
            var params = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                params[_i] = arguments[_i];
            }
            if (!params || params.length === 0)
                return [];
            if (params.length === 1) {
                return params[0];
            }
            var arr1 = params[0];
            var arr2 = params[1];
            if (params.length > 2) {
                return intersectionSortArr(arr1, intersectionSortArr.apply(void 0, [arr2].concat(params.slice(2))));
            }
            var arr = [];
            if (!arr1.length || !arr2.length || arr1[0] > arr2.slice(-1)[0] || arr2[0] > arr1.slice(-1)[0]) {
                return arr;
            }
            var j = 0;
            var k = 0;
            var arr1Len = arr1.length;
            var arr2Len = arr2.length;
            while (j < arr1Len && k < arr2Len) {
                if (arr1[j] < arr2[k]) {
                    j++;
                }
                else if (arr1[j] > arr2[k]) {
                    k++;
                }
                else {
                    arr.push(arr1[j]);
                    j++;
                    k++;
                }
            }
            return arr;
        }
        /**
         * 构造返回指定长度的数组
         * @param len 数组的长度
         * @param fill 数组每一项的填充值，默认填充 index的值
         */
        function getArrByLen(len, fill) {
            if (len === 0) return [];
            return (Array(len) + '').split(',').map(function (v, k) {
                return fill || k;
            });
        }
        /**
         * 将所给定的数组填充到给定的长度
         * @param arr 需要填充的数组
         * @param length 需要填充的长度
         * @param fill 新增填充的项的填充值
         */
        function completeArr(arr, length, fill) {
            return arr.concat(getArrByLen(length, fill));
        }
        /**
         * 给定 mArr长度个数组，从这些数组中取 n 个项，每个数组最多取一项，求所有的可能集合，其中，mArr的每个项的值代表这个数组的长度
         * 例如 composeMArrN(([1, 2, 3], 2))，表示给定了 3 个数组，第一个数组长度为 1，第二个数组长度为 2，第二个数组长度为 3，从这三个数组任意取两个数
         * example： composeMArrN(([1, 2, 3], 2))，返回：
         * [[0,0,-1],[0,1,-1],[0,-1,0],[0,-1,1],[0,-1,2],[-1,0,0],[-1,0,1],[-1,0,2],[-1,1,0],[-1,1,1],[-1,1,2]]
         * 返回的数组长度为 11，表示有1 种取法，数组中每个子数组就是一个取值组合，子数组中的数据项就表示取值的规则
         * 例如，对于上述结果的第一个子数组 [0, 0, -1] 来说，表示第一种取法是 取第一个数组下标为 0 和 第二个数组下标为 0 的数，下标为 2 的数组项值为 -1 表示第三个数组不取任何数
         * @param mArr 数据源信息
         * @param n 取数的个数
         * @param arr 递归使用，外部调用不需要传此项
         * @param hasSeletedArr 递归使用，外部调用不需要传此项
         * @param rootArr 递归使用，外部调用不需要传此项
         */
        function composeMArrN(mArr, n, arr, hasSeletedArr, rootArr) {
            if (arr === void 0) {
                arr = [];
            }
            if (hasSeletedArr === void 0) {
                hasSeletedArr = [];
            }
            if (rootArr === void 0) {
                rootArr = [];
            }
            if (!n || n < 1 || mArr.length < n) {
                return arr;
            }
            for (var i = 0; i < mArr.length; i++) {
                // 当前层级已经存在选中项了
                if (hasSeletedArr.includes(i)) continue;
                hasSeletedArr = hasSeletedArr.slice();
                hasSeletedArr.push(i);
                for (var j = 0; j < mArr[i]; j++) {
                    var arr1 = completeArr(arr, i - arr.length, -1);
                    arr1.push(j);
                    if (n === 1) {
                        arr1 = completeArr(arr1, mArr.length - arr1.length, -1);
                        rootArr.push(arr1);
                    } else {
                        composeMArrN(mArr, n - 1, arr1, hasSeletedArr, rootArr);
                    }
                }
            }
            return rootArr;
        }
        // 任意选择状态下的商品库存和价格信息
        // 例如，选中黑色 + 16G，计算出其对应的总库存和价格范围数据
        function computeAllCaseInfo(){
            var caseCom = [];
            var includeIndexArrTemp = [];
            var priceArrTemp = [];
            var countArrTemp = [];
            var spuDIdTemp = [];
            var mArr = SkuManage.skuParamVoList.map(function (item) { return item.valueList.length; });
            var skuParamVoListLen = SkuManage.skuParamVoList.length;
            var _loop = function (index) {
                SkuManage.indexKeyInfoMap[index] = {};
                caseCom = composeMArrN(mArr, index + 1).map(function (item) {
                    return item.reduce(function (t, c, kk) {
                        if (c === -1)
                            return t;
                        // 需要按照 paramId 从小到大排序
                        return sortByParamId(t, SkuManage.skuParamVoList[kk].paramId + joinKVStr + SkuManage.skuParamVoList[kk].valueList[c].valueId);
                    }, []);
                });
                caseCom.forEach(function (v) {
                    priceArrTemp_1 = [];
                    countArrTemp_1 = [];
                    spuDIdTemp_1 = [];
                    includeIndexArrTemp_1 = intersectionSortArr.apply(void 0, v.map(function (vv) { return (SkuManage.keyRankMap[vv] || []); }));
                    includeIndexArrTemp_1.forEach(function (item) {
                        priceArrTemp_1 = priceArrTemp_1.concat(SkuManage.skuRankList[item].priceRange);
                        countArrTemp_1.push(SkuManage.skuRankList[item].count);
                        spuDIdTemp_1.push(SkuManage.skuRankList[item].spuDId);
                    });
                    SkuManage.indexKeyInfoMap[index][v.join(joinAttrStr)] = {
                        spuDIdArr: spuDIdTemp_1,
                        // 转为数字
                        priceArr: priceArrTemp_1.map(function (item) { return +item; }),
                        totalCount: countArrTemp_1.reduce(function (t, c) { return t + c; }, 0)
                    };
                });
            };
            for (var index = 0; index < skuParamVoListLen; index++) {
                _loop(index);
            }
            /* eslint-disable-next-line */
            console.log('computeAllCaseInfo done', SkuManage.indexKeyInfoMap);
        }
        /**
         * 当前选择状态下，再次选择时，库存为 0 的 sku属性，返回值例如：['20_201']
         * @param arrKeyCount 选中了几个sku属性
         * @param activeSpuTagMapKey 已经选中的sku属性，例如：'10_100'
         */
        function computeEmptyInfo(arrKeyCount, activeSpuTagMapKey) {
            var nextEmptyKV = [];
            if (arrKeyCount === 0) {
                return Object.keys(SkuManage.indexKeyInfoMap[0]).filter(function (item) {
                    return SkuManage.indexKeyInfoMap[0][item].totalCount === 0;
                });
            }
            if (arrKeyCount >= SkuManage.skuParamVoList.length) {
                // 选择了全部 sku 属性
                return nextEmptyKV;
            }
            var nextKeyMap = SkuManage.indexKeyInfoMap[arrKeyCount];
            var activeSpuTagList = activeSpuTagMapKey.split(joinAttrStr);
            var activeSpuTagArrLen = activeSpuTagList.length;
            var nextEmptyKeyArr = [];
            Object.keys(nextKeyMap).forEach(function (item) {
                if (nextKeyMap[item].totalCount !== 0) return;
                var i = 0;
                var itemArr = item.split(joinAttrStr);
                itemArr.forEach(function (v) {
                    if (v === activeSpuTagList[i]) i++;
                });
                if (i === activeSpuTagArrLen) {
                    nextEmptyKeyArr.push(item);
                }
            });
            if (nextEmptyKeyArr.length) {
                var activeSpuTagArr = activeSpuTagMapKey.split(joinAttrStr);
                nextEmptyKV = uniqueArr(nextEmptyKeyArr.map(function (item) {
                    // 删掉当前已经选中的，剩下的一个就是应该置灰的
                    activeSpuTagArr.forEach(function (v) {
                        item = item.replace(v, '');
                    });
                    return item.replace(joinAttrStrRe, '');
                }));
            }
            return nextEmptyKV;
        }
        // 计算所有库存为0的sku中包括的sku的属性的集合
        function RemoveStockZero() {
            SkuManage.skuRankList.forEach(function (item) {
                if (item.count === 0) {
                    SkuManage.emptySkuIncludeList = SkuManage.emptySkuIncludeList.concat(item.paramIdJoin.split(joinAttrStr));
                }
            });
        }
        function emptySet(){
            SkuManage.emptySkuIncludeList = uniqueArr(SkuManage.emptySkuIncludeList);
            if (SkuManage.emptySkuIncludeList.length) {
                SkuManage.emptySkuMap = computeEmptyInfo(0)
                console.log('this.emptySkuMap', SkuManage.emptySkuMap)
            }
        }
        /**
         * 拼接已经选中的 sku属性
         * @param activeSpuTagMap 例如：{ 10:'101', 20: '201' }
         * @example { 10:'101', 20: '201' } => ['10_101', '20_201']
         */
        function getSelectedIndexKeyArr(activeSpuTagMap) {
            // paramId从小到大排序
            return Object.keys(activeSpuTagMap).filter(function (key) {
                return activeSpuTagMap[key];
            }).sort(function (a, b) {
                return +a - +b;
            }).map(function (key) {
                return key + joinKVStr + activeSpuTagMap[key];
            });
        }
        /**
         * 求数组交集, intersectionSortArr 的宽松版本，没有 intersectionSortArr 对参数要求那么严格，但是在大数据量的情况下，效率也不如 intersectionSortArr 好
         * @param params 需要求交集的数组，例如 intersectionArr(['swwsw', 'swsw'], ['12', 3, 4], [5,6])
         */
        function intersectionArr() {
            var params = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                params[_i] = arguments[_i];
            }
            if (!params || params.length === 0) return [];
            if (params.length === 1) {
                return params[0];
            }
            var arr1 = params[0];
            var arr2 = params[1];
            if (params.length > 2) {
                return intersectionArr(arr1, intersectionArr.apply(void 0, [arr2].concat(params.slice(2))));
            }
            var arr = [];
            uniqueArr(arr1).forEach(function (item) {
                if (arr2.includes(item)) {
                    arr.push(item);
                }
            });
            return arr;
        }
        /**
         * 从 m 个数字中取 n 个，所有可能的取法（不考虑顺序）
         * @param m 数据总数
         * @param n 取数个数
         * @param arr 递归使用，外部调用不需要传此项
         * @param hasSeletedArr 递归使用，外部调用不需要传此项
         * @param rootArr 递归使用，外部调用不需要传此项
         */
        function composeMN(m, n, arr, hasSeletedArr, rootArr) {
            if (arr === void 0) { arr = []; }
            if (hasSeletedArr === void 0) { hasSeletedArr = []; }
            if (rootArr === void 0) { rootArr = []; }
            for (var i = 0; i < m; i++) {
                if (hasSeletedArr.includes(i))
                    continue;
                hasSeletedArr = hasSeletedArr.slice();
                hasSeletedArr.push(i);
                var arr1 = arr.slice();
                arr1.push(i);
                if (n !== 1) {
                    composeMN(m, n - 1, arr1, hasSeletedArr, rootArr);
                }
                else {
                    rootArr.push(arr1);
                }
            }
            return rootArr;
        }
        function excuteBySeleted(activeSpuTagMap) {
            // 从小到大排序
            var activeSpuTagMapKeyList = Object.keys(activeSpuTagMap).filter(function (item) {
                return activeSpuTagMap[item];
            }).sort(function (a, b) {
                return +a - +b;
            });
            var activeSpuTagMapKVArr = getSelectedIndexKeyArr(activeSpuTagMap);
            // 没有选择任何 sku属性
            if (activeSpuTagMapKeyList.length === 0) {
                return {
                    currentRst: null,
                    nextEmptyKV: SkuManage.emptySkuMap
                }
            }
            var arrKeyCount = activeSpuTagMapKeyList.length
            // 取得当前条件对应的库存和价格
            var currentRst = SkuManage.indexKeyInfoMap[arrKeyCount - 1][activeSpuTagMapKVArr.join(joinAttrStr)];
            // 需要置灰的 sku属性
            var nextEmptyKV = [];
            // 不需要考虑置灰的情况，直接返回
            // 如果当前所选的 sku属性，都不在 this.emptySkuMap 内，即选择的 sku属性都是有库存的，则说明无论下一步选择哪些 sku属性，都无需考虑置灰
            if (intersectionArr(SkuManage.emptySkuIncludeList, activeSpuTagMapKVArr).length === 0) {
                return {
                    // 当前选中的sku属性按钮对应的库存和价格信息
                    currentRst,
                    nextEmptyKV: SkuManage.emptySkuMap
                }
            }
            // 取得置灰的属性信息
            for (var i = 0; i < activeSpuTagMapKeyList.length; i++) {
                var currentList = composeMN(activeSpuTagMapKeyList.length, i + 1);
                nextEmptyKV = nextEmptyKV.concat(currentList.reduce(function (t, item) {
                    var currentSpuTagMapKey = item.reduce(function (total, c) {
                        return total + activeSpuTagMapKeyList[c] + joinKVStr + activeSpuTagMap[activeSpuTagMapKeyList[c]] + joinAttrStr;
                    }, '').slice(0, -joinAttrStr.length);
                    return t.concat(computeEmptyInfo(item.length, currentSpuTagMapKey));
                }, []));
            }
            return {
                // 当前选中的sku属性按钮对应的库存和价格信息
                currentRst: currentRst,
                // 应该置为灰色不可点击状态的按钮，需要加上当任何属性不选择是库存为 0 的属性
                nextEmptyKV: uniqueArr(nextEmptyKV.concat(this.emptySkuMap))
            };
        }
        // 获取库存为 0 的sku属性，依赖于 currentSelectSkuRst
        function getEmptyMap(){
            console.log("进入获取库存为0的函数处理")
            var kv = null
            // key 为 paramId，值为以 valueId 组成的数组，在这个数组中的 valueId 就是需要置灰的
            var emptyMap ={};
            // ['10_100', '20_200']
            console.log(currentSelectSkuRst)
            currentSelectSkuRst.nextEmptyKV.forEach(function (item) {
                kv = item.split(joinKVStr);
                emptyMap[kv[0]] = (emptyMap[kv[0]] || []).concat(kv[1]);
            });
            return emptyMap
        }
        // activeSkuMap 发生改变
        function activeSkuMapUpdate (){
            currentSelectSkuRst = excuteBySeleted(activeSkuTagMap);
            var hasSelectedListArr = [];
            var valueInfo = null;
            console.log("确认skuKindsArr是否存在valueList",skuKindsArr)
            console.log("activeSkuTagMap确认",activeSkuTagMap)
            skuKindsArr.forEach(function (item) {
                valueInfo = item.valueList.filter(function (v) { 
                    console.log(activeSkuTagMap[item])
                    console.log(item)
                    console.log(item.paramId)
                    console.log("测试一下详细查找", activeSkuTagMap[item.paramId])
                  return v.valueId === activeSkuTagMap[item.paramId]; 
                })[0];
                console.log("valueInfo内容",valueInfo)
                if (valueInfo) {
                    hasSelectedListArr.push({
                        paramId: item.paramId,
                        valueId: valueInfo.valueId,
                        valueValue: valueInfo.valueValue
                    });
                }
            });
            hasSelectedList = hasSelectedListArr;
            console.log("hasSelectedListArr",hasSelectedListArr)
            console.log("this.hasSelectedList的内容",hasSelectedList)
            console.log("getEmptyMap执行开始")
            emptyMap = getEmptyMap();
            console.log("getEmptyMap执行完")
            // 取最低价格进行显示
            currentSeletedPrice = currentSelectSkuRst.currentRst ? (Math.min.apply(Math, currentSelectSkuRst.currentRst.priceArr)) : 0;
            currentTotalCount = currentSelectSkuRst.currentRst ? (currentSelectSkuRst.currentRst.totalCount) : 0;
            console.log('tag change done:', emptyMap, currentSelectSkuRst);
        }
        // 需要对skuManage进行的操作
        function init() {
            computeKeyRankMap();
            computeAllCaseInfo();
            RemoveStockZero();
            emptySet();
        }
        // 页面加载时需要进行处理的函数
        function created() {
            computeSkuData();
            init();
            activeSkuMapUpdate();
        }
        created()
        
        // 页面渲染函数
        function renderPage(){
            $.each(skuKindsArr,function (skuKindsArr_index,skuKindsArr_val) {
                skuDomHtml += "<div class='sku-select'>"
                skuDomHtml += "<div class='sku-select-name'>"
                skuDomHtml += "<span class='dynamic_name' data-paramId='"+ skuKindsArr_val.paramId +"' >"+ skuKindsArr_val.paramValue +" </span>"
                skuDomHtml += "</div>"
                skuDomHtml += "<div class='sku-select-module'>"
                skuDomHtml += "<div data-paramId='"+ skuKindsArr_val.paramId +"'  data-name='"+ skuKindsArr_val.paramValue +"' class='sku-select-value'>"
                skuDomHtml += "<input type='hidden' readonly name='"+ skuKindsArr_val.paramValue +"'>"
                skuDomHtml += "<span class='sku-select-value-show'>Please Select</span>"
                skuDomHtml += "</div>"
                skuDomHtml += "<div class='sku-select-options'>"
                skuDomHtml += "<ul data-paramId='"+ skuKindsArr_val.paramId +"' data-name='"+ skuKindsArr_val.paramValue +"'>"
                $.each(skuKindsArr_val.valueList,function (sku_map_data_i,sku_map_data_n) {
                    skuDomHtml += "<li data-paramId='"+ skuKindsArr_val.paramId +"' data-valueId='" + sku_map_data_n.valueId + "'>"+ sku_map_data_n.valueValue +"</li>"
                });
                skuDomHtml += "</ul>"
                skuDomHtml += "</div>"
                skuDomHtml += "</div>"
                skuDomHtml += "</div>"
            })
            fatherDomBox.html("").append(skuDomHtml);
        }
        // 进行页面渲染
        renderPage();
        // 点击自定义下拉菜单
        $("#sku-choose-store").on("click",".sku-select-value",function () {
            var clickDom = $(this);
            if(clickDom.hasClass("active")){
                clickDom.removeClass("active");
                clickDom.parent(".sku-select-module").find(".sku-select-options").slideUp();
            }else {
                $("#sku-choose-store").find(".sku-select-value").removeClass("active");
                $("#sku-choose-store").find(".sku-select-options").slideUp();
                clickDom.addClass("active");
                clickDom.parent(".sku-select-module").find(".sku-select-options").slideDown();
            }
        });
        // 点击每个下拉菜单中选择项
        $("#sku-choose-store").on("click","li",function () {
            var _that = $(this),
                selected_val = _that.attr("data-value"),
                _that_parent = _that.parent("ul");
            var paramId = _that.attr("data-paramId");
            var valueId = _that.attr("data-valueId");
            _that.parents(".sku-select-module").find(".sku-select-value-show").html(_that.html());
            _that.parents(".sku-select-module").find("input").val(selected_val);
            $("#sku-choose-store").find(".sku-select-value").removeClass("active");
            $("#sku-choose-store").find(".sku-select-options").slideUp();
            $("#pro_num").val("1");
            selectTag(paramId,valueId)
        });

        // 每个选项点击时判断按钮的状态
        function selectTag (paramId, valueId) {
            console.log('selectTag', paramId, valueId);
            console.log("应该置灰的不可点击按钮", emptyMap);
            if (emptyMap && emptyMap[paramId] && emptyMap[paramId].some(function (item) { return item === valueId; })) {
                // 当前点击按钮已经被置灰，不做响应
                return;
            }
            if (activeSkuTagMap[paramId] === valueId) {
                // 如果已经点选了，再次点选就取消点选
                // this.$delete(activeSkuTagMap, paramId);
            }
            else {
                // this.$set(activeSkuTagMap, paramId, valueId);
                activeSkuTagMap[paramId] = valueId
                console.log(activeSkuTagMap)
                console.log("点击是新的选项")
            }
            activeSkuMapUpdate();
        }
















        // -********************************** sku数据选择器的相关内容 ************************************
        // map方法的兼容性写法
        /**
         * map遍历数组
         * @param callback [function] 回调函数；
         * @param context [object] 上下文；
         */
        Array.prototype.myMap = function myMap(callback,context){
            context = context || window;
            if('map' in Array.prototye) {
                return this.map(callback,context);
            }
            //IE6-8下自己编写回调函数执行的逻辑
            var newAry = [];
            for(var i = 0,len = this.length; i < len;i++) {
                if(typeof callback === 'function') {
                    var val = callback.call(context,this[i],i,this);
                    newAry[newAry.length] = val;
                }
            }
            return newAry;
        };
        // 数组选择器
        // 定义skus数组内容
        // 数据融合公用方法
        function  dataFusion(intArray,outArray) {
            $.each(intArray, function (sku_arr_i,sku_arr_n) {
                $.each(sku_arr_n, function (sku_arr_n_index,sku_arr_n_item) {
                    outArray.push(sku_arr_n_item);
                })
            });
        }
        // 数组按属性值进行分类
        function dataFusionClassify(intArray,outArray,mapname,attributename) {
            for(var i = 0; i < intArray.length; i++){
                var coalesce_a = intArray[i];     // skus_arr_coalesce循环的每个单独数据的定义
                if(!mapname[coalesce_a[attributename]]&&coalesce_a[attributename] != undefined){       // 如果map对象中不存在查找的name值则将这个name新增到map对象中
                    outArray.push({
                        name: coalesce_a[attributename],
                        data: [coalesce_a]
                    });
                    mapname[coalesce_a[attributename]] = coalesce_a;
                }else{       // 如果查找的name值存在于map对象中，则将该条数据除了name外对应的数据添加到map中已有相应名字对象的data数组中
                    for(var j = 0; j < outArray.length; j++){
                        var coalesce_d = outArray[j];
                        if(coalesce_d['name'] == coalesce_a[attributename]){
                            coalesce_d.data.push(coalesce_a);
                            break;
                        }
                    }
                }
            }
        }
        var skus_arr = [],   // 后台返回数据转json的存放数组
            skus_arr_Arry = [],
            skus_arr_coalesce = [], // 存放json第一次循环后的混合数组
            map = {},  // 定义一个map对象用于数组中具有相同name值得数据进行合并  // 根据数组的name属性对数据进行map操作与数组整合
            skus_map = [],  // 经过
            // map操作融合后的数据,同时对该数组进行循环，将内容渲染到页面中
            already_selected = [],   // 存放用户做出选择的skus参数的数组
            temporary_storage = [], // 用于临时存储根据索引从原始数组中存储的数据
            haschoose = false,   // 定义判断用户是否已经进行选择
            sku_parameter = {     // 定义一个sku参数对象集合，单个相关参数存放在该对象中方便区分与查找
                html: '',
                optionHtml: '',   // 每次选择之后刷新的option的模板
                sku_data_warehouse: $(".parameter-data"), // 初始存放数据的仓库的选择器
                sku_choose_store: $(".sku-choose-store"),    // 存放渲染节点的父级节点选择器
                choose_classify_index: '',   // 下拉菜单select被操作的对应的data-index值
                choose_classify_sku_id: '',   // 活跃状态下被选中的select的sku_id值
                sku_select_amount: 0  // 定义sku中select选择器的数量
            };
        skus_arr = JSON.parse(sku_parameter.sku_data_warehouse.val());
        for (var skus_arr_index in skus_arr) {
            var  skus_arr_object = {};
            skus_arr_object[skus_arr_index] = skus_arr[skus_arr_index];
            skus_arr_Arry.push(skus_arr_object)
        }
        // 原始数组进行重构
        var skus_arr_refactor = [];  // 将原始数组进行重构
        var skus_arr_refactor_kinds = [];
        for (var skus_arr_index in skus_arr) {
            var  skus_arr_object = {};
            var skus_arr_object_child = [];
            skus_arr_object.product_sku_id = skus_arr[skus_arr_index][0].product_sku_id;
            skus_arr_object.delta_price = skus_arr[skus_arr_index][0].delta_price;
            skus_arr_object.price = skus_arr[skus_arr_index][0].price;
            skus_arr_object.stock = skus_arr[skus_arr_index][0].stock;
            for(var skus_arr_child_index in skus_arr[skus_arr_index]){
                if(skus_arr[skus_arr_index][skus_arr_child_index].value){
                    if(skus_arr[skus_arr_index][skus_arr_child_index].photo_url) {
                        skus_arr_object_child.push({
                            name: skus_arr[skus_arr_index][skus_arr_child_index].name,
                            value: skus_arr[skus_arr_index][skus_arr_child_index].value,
                            photo_url: skus_arr[skus_arr_index][skus_arr_child_index].photo_url
                        })
                    }else {
                        skus_arr_object_child.push({name: skus_arr[skus_arr_index][skus_arr_child_index].name,value: skus_arr[skus_arr_index][skus_arr_child_index].value})
                    }
                }
            }
            skus_arr_object.sku_kinds = skus_arr_object_child;
            skus_arr_refactor.push(skus_arr_object)
        }
        for(var i=0;i<skus_arr_refactor.length;i++){
            skus_arr_refactor_kinds.push(skus_arr_refactor[i].sku_kinds)
        }
        var skus_arr_refactor_map = {};
        var skus_arr_refactor_map_arr = [];
        var skus_arr_refactor_kinds_coalesce = [];
        dataFusion(skus_arr_refactor_kinds,skus_arr_refactor_kinds_coalesce);
        dataFusionClassify(skus_arr_refactor_kinds_coalesce,skus_arr_refactor_map_arr,skus_arr_refactor_map,'name');
        // 最初版本的数组的遍历
        // dataFusion(skus_arr,skus_arr_coalesce);
        //当 数据完成第一次处理之后将页面中的input的value进行置空
        sku_parameter.sku_data_warehouse.val("");
        /**数组根据数组对象中的某个属性值进行排序的方法
         * 使用例子：newArray.sort(sortBy('number',false)) //表示根据number属性降序排列;若第二个参数不传递，默认表示升序排序
         * @param attr 排序的属性 如number属性
         * @param rev true表示升序排列，false降序排序
         * */
        function compare(attr,rev){
            //第二个参数没有传递 默认升序排列
            if(rev ==  undefined){
                rev = 1;
            }else{
                rev = (rev) ? 1 : -1;
            }
            return function(a,b){
                a = a[attr];
                b = b[attr];
                if(a < b){
                    return rev * -1;
                }
                if(a > b){
                    return rev * 1;
                }
                return 0;
            }
        }
        // 最初版本的数组的分类
        // dataFusionClassify(skus_arr_coalesce,skus_map,map,'name');
        // 根据数组对象进行去重
        function arrayUnique2(arr, name) {
            var hash = {};
            return arr.reduce(function (item, next) {
                hash[next[name]] ? '' : hash[next[name]] = true && item.push(next);
                return item;
            }, []);
        }
        //   对处理过后的数据进行循环，动态生成相应的DOM节点
        function renderingNode(){
            $.each(skus_arr_refactor_map_arr,function (sku_map_i,sku_map_n) {
                sku_parameter.html += "<div class='sku-select'>"
                sku_parameter.html += "<div class='sku-select-name'>"
                if(sku_map_n.name == "Hair Color"){
                    sku_parameter.html += "<span class='dynamic_name' data-type='Hair Color'>"+ sku_map_n.name +
                        " <a target='_blank' href='{{ asset('img/HairColor.jpg') }}'>"+
                        "<img src='{{ asset('img/photo-choose.png') }}'></a></span>"
                }else if(sku_map_n.name == "Hair Density"){
                    sku_parameter.html += "<span class='dynamic_name'>"+ sku_map_n.name +
                        " <a target='_blank' href='{{ asset('img/HairDensity.jpg') }}'>"+
                        "<img src='{{ asset('img/photo-choose.png') }}'></a></span>"
                }else{
                    sku_parameter.html += "<span class='dynamic_name'>"+ sku_map_n.name +" </span>"
                }
                sku_parameter.html += "</div>"
                sku_parameter.html += "<div class='sku-select-module'>"
                sku_parameter.html += "<div data-index='"+ sku_map_i +"'  data-name='"+ sku_map_n.name +"' class='sku-select-value'>"
                sku_parameter.html += "<input type='hidden' readonly name='"+ sku_map_n.name +"'>"
                sku_parameter.html += "<span class='sku-select-value-show'>Please Select</span>"
                sku_parameter.html += "</div>"
                sku_parameter.html += "<div class='sku-select-options'>"
                sku_parameter.html += "<ul data-index='"+ sku_map_i +"' data-name='"+ sku_map_n.name +"'>"
                var sku_map_item =arrayUnique2(sku_map_n.data,'value');
                sku_map_item.sort(compare("value"));
                $.each(sku_map_item,function (sku_map_data_i,sku_map_data_n) {
                    if(sku_map_n.name == "Hair Color") {
                        sku_parameter.html += "<li data-value='" + sku_map_data_n.value.replace(/\'|\"/g,"") + "' data-img='"+ sku_map_data_n.photo_url +"'>"+ sku_map_data_n.value +"</li>"
                    }else {
                        sku_parameter.html += "<li data-value='" + sku_map_data_n.value.replace(/\'|\"/g,"") + "'>"+ sku_map_data_n.value +"</li>"
                    }
                });
                sku_parameter.html += "</ul>"
                sku_parameter.html += "</div>"
                sku_parameter.html += "</div>"
                sku_parameter.html += "</div>"
            });
        }
        // renderingNode();
        // 判断数组中是否存在重复标号
        function isExists(arr,aim,search){
            return arr.some(function(item) {
                if (item[aim] == search) {
                    return true;
                }
            });
        }
        // 删除需要更新的旧数据
        function delOldData(Array,aim,search){
            for (var select_i = 0; select_i< Array.length;select_i++){
                if(already_selected[select_i][aim] == search){
                    Array.splice(select_i,1);
                }
            }
        }
        // 获取sku_id
        function getSkuId(){
            // 初始化sku_id的值为0，每次判断是否为0，来判断是否有该商品
            sku_id = 0;
            sku_stock = 0;
            var searchArr = [],
                newSkuArray = [],
                childSkuArr = [],
                firstResultArr = [],
                secondResultArr = [],
                forTipArr = [];
            var allChooseSelect = $(".sku-choose-store").find("input");
            $.each(allChooseSelect,function (chooseSelect_in,chooseSelect_value) {
                if(!$(chooseSelect_value).val()){
                    // 判断是否已经做出了所有的选择
                    forTipArr.push($(chooseSelect_value).val())
                }else {
                    searchArr.push({name: $(chooseSelect_value).attr("name"),value: $(chooseSelect_value).val() })
                }
            });
            if(forTipArr.length!=0){
                haschoose = false;
                return haschoose
            }else {
                haschoose = true;
            }

            // 对原始数组进行遍历
            for(var arr_key in skus_arr) {
                newSkuArray.push({id:arr_key,data:skus_arr[arr_key]});
                for(var newSkuArrayKey in newSkuArray) {
                    childSkuArr = newSkuArray[newSkuArrayKey].data
                    for (var childSkuArrKey in childSkuArr){
                        for (var searchArrKey = 0; searchArrKey< searchArr.length; searchArrKey++){
                            if (childSkuArr[childSkuArrKey].value!=undefined&&searchArr[searchArrKey].value!=undefined) {
                                if(childSkuArr[childSkuArrKey].value.replace(/\'|\"/g,"") == searchArr[searchArrKey].value&&childSkuArr[childSkuArrKey].name == searchArr[searchArrKey].name){
                                    firstResultArr.push(newSkuArray[newSkuArrayKey]);
                                }
                            }
                        }
                    }
                }
            }
            // 一次轮询后的数字进行去重操作
            var firstResultArr_hash=[];
            firstResultArr_hash = arrayUnique2(firstResultArr,"id");
            $.each(firstResultArr_hash,function (firstResultArr_in,firstResultArr_value) {
                var firstResultData = firstResultArr_value.data;
                for (var firstResultData_i = 0;firstResultData_i<firstResultData.length;firstResultData_i++) {
                    for (var searchArr_index = 0;searchArr_index<searchArr.length;searchArr_index++){
                        if(firstResultData[firstResultData_i].name==searchArr[searchArr_index].name&&firstResultData[firstResultData_i].value.replace(/\'|\"/g,"")==searchArr[searchArr_index].value){
                            secondResultArr.push(firstResultData[firstResultData_i]);
                        }
                    }
                }
            });
            var searchResultMap = {},
                finalArray = [];
            var aimLength = $(".sku-choose-store").find('input').length;
            dataFusionClassify(secondResultArr,finalArray,searchResultMap,'product_sku_id');
            $.each(finalArray,function (finalArray_i,finalArray_n) {
                if(finalArray_n.data.length == aimLength) {
                    sku_id = finalArray_n.name;
                    sku_stock = skus_arr[sku_id][0].stock;
                    sku_price = skus_arr[sku_id][0].price;
                    $("#product-price").text(sku_price);
                }
            });
            console.log(sku_id)
        }
        // sku_parameter.sku_choose_store.html("").append(sku_parameter.html);
        // 重置筛选条件
        $(".Reset-filter").on("click",function () {
            already_selected = []
            $("#sku-choose-store").find("select").val("select");
            $("#sku-choose-store").find("option").prop("disabled",false);
            $("#sku-choose-store").find("option[value='select']").prop("disabled",true);
        });

    //    自定义下拉菜单
        var isFirstChooseClick = null;   //    定义一个参数用来判断点击的是否为第一个选择器的第一次点击
        var isCkickLast = null; // 判断点击的选择器与上一次点击的选择器是否相同
        var isFirstSameBtn = false; // 判断是否连续点击的第一个
        // $("#sku-choose-store").on("click",".sku-select-value",function () {
        //     var clickDom = $(this);
        //     if(clickDom.hasClass("active")){
        //         clickDom.removeClass("active");
        //         clickDom.parent(".sku-select-module").find(".sku-select-options").slideUp();
        //     }else {
        //         $("#sku-choose-store").find(".sku-select-value").removeClass("active");
        //         $("#sku-choose-store").find(".sku-select-options").slideUp();
        //         clickDom.addClass("active");
        //         clickDom.parent(".sku-select-module").find(".sku-select-options").slideDown();
        //     }
        //     // if(isFirstChooseClick == clickDom.attr("data-index")){
        //     //     // clickDom.parent(".sku-select-module").find(".sku-select-options").find("li").removeClass("forbid-choose-style");
        //     //     isFirstSameBtn = true;
        //     // }
        // });
        // var active_name = '',  // 用户选择的sku对用的id
        //     active_value = '',  // 用户选择的sku对应的value值
        //     exists = false;    // 用于判断数组是否存在重复选择的值
        // $("#sku-choose-store").on("click","li",function () {
        //     var _that = $(this),
        //         selected_val = _that.attr("data-value"),
        //         _that_parent = _that.parent("ul");
        //     _that.parents(".sku-select-module").find(".sku-select-value-show").html(_that.html());
        //     _that.parents(".sku-select-module").find("input").val(selected_val);
        //     $("#sku-choose-store").find(".sku-select-value").removeClass("active");
        //     $("#sku-choose-store").find(".sku-select-options").slideUp();
        //     $("#pro_num").val("1");
        //     if(!_that.hasClass("allow-choose")){
        //         // 如果点击了允许点击之外的选项，把临时仓库置空，方便存储新的结果
        //         temporary_storage = [];
        //     }
        //     // 切换颜色的时候修改放大镜当前显示的图片
        //     if(_that.parents(".sku-select").find(".dynamic_name").attr("data-type") === "Hair Color") {
        //         var zoomImgUrl = _that.attr("data-img");
        //         magnifyingAdd(zoomImgUrl);
        //     }
        //     // 将选择存储到临时仓库 already_selected
        //    //  判断操作的是那个选择器
        //     sku_parameter.choose_classify_index = _that_parent.attr("data-index");
        //     // if(!isFirstSameBtn){
        //     //     isFirstChooseClick = sku_parameter.choose_classify_index;
        //     // }
        //     // 判断是sku选择器都已经被选择过
        //     var hasChooseLength = already_selected.length;  // 已经做出选择的sku选择项的存储数组
        //     if(hasChooseLength != 0){
        //         active_name = ''; // 用户选择的sku对用的name
        //         active_value = '';  // 用户选择的sku对应的value值
        //         exists = false;    // 用于判断数组是否存在重复选择的值
        //     // sku选择器已经做出过选择，判断当前操作的项数组中是否存在，如果存在则进行刷新，如果不存在则往数组中插入
        //         skus_arr_refactor_kinds.forEach(function (item) {
        //             for(var item_key = 0; item_key<item.length; item_key++ ){
        //                 var sku_arrFindSel = item[item_key].value;
        //                 if(sku_arrFindSel === selected_val){
        //                     active_name = item[item_key].name; // 用户选择的sku对用的name
        //                     active_value = item[item_key].value;  // 用户选择的sku对应的value值
        //                     exists = false;    // 用于判断数组是否存在重复选择的值
        //                 }
        //                 exists = isExists(already_selected,'name',active_name);
        //                 if (exists){
        //                     delOldData(already_selected,'name',active_name);
        //                 }
        //             }
        //         });
        //         already_selected.push({
        //             name:active_name,
        //             value:active_value
        //         });
        //     }else {
        //         active_name = ''; // 用户选择的sku对用的name
        //         active_value = '';  // 用户选择的sku对应的value值
        //         exists = false;    // 用于判断数组是否存在重复选择的值
        //         //用户进行第一次选择
        //         skus_arr_refactor_kinds.forEach(function (item) {
        //             for(var item_key = 0; item_key<item.length; item_key++ ){
        //                 var sku_arrFindSel = item[item_key].value;
        //                 if(sku_arrFindSel === selected_val){
        //                     active_name = item[item_key].name; // 用户选择的sku对用的id
        //                     active_value = item[item_key].value;  // 用户选择的sku对应的value值
        //                 }
        //             }
        //         });
        //         already_selected.push({
        //             name:active_name,
        //             value:active_value
        //         });
        //     }
        //     // 已经获取到了用户已经进行选择的数组 already_selected，通过 already_selected的内容对总数组进行索引
        //
        //     // 当临时仓库为空的时候，通过already_selected的内容从临时仓库中进行索引（想法，难点，个数问题）
        //     var already_selected_length = already_selected.length;
        //     console.log(already_selected_length);
        //
        //     if(temporary_storage.length == 0){
        //     //    当临时仓库为空的时候，根据所选项从总数组中进行索引并存进临时仓库
        //         for(var kinds_index = 0; kinds_index<skus_arr_refactor_kinds.length; kinds_index++){
        //             var refactor_kinds_child = skus_arr_refactor_kinds[kinds_index];
        //             for (var child_index = 0;child_index < refactor_kinds_child.length; child_index++){
        //                 // 对已选择的数组进行循环与总数组的值进行比较
        //                 // for (var selected_index = 0; selected_index<already_selected.length;selected_index++){
        //                 //     if(refactor_kinds_child[child_index].value === active_value&&
        //                 //         refactor_kinds_child[child_index].name === active_name){
        //                 if(refactor_kinds_child[child_index].value === active_value&&
        //                     refactor_kinds_child[child_index].name === active_name){
        //                     if(!_that.hasClass("allow-choose")){
        //                         // 如果点击了允许之外的选在则将临时仓库进行更新
        //                         temporary_storage.push(refactor_kinds_child);
        //                     }
        //                 }
        //                 // }
        //             }
        //         }
        //     }else {
        //     //    当临时仓库不为空的时候，根据所选项从临时仓库中进行查询
        //         if(!_that.hasClass("allow-choose")){
        //         //   当用户点击的非允许的选项时根据选项从总的数组中进行选择
        //             for(var kinds_index = 0; kinds_index<skus_arr_refactor_kinds.length; kinds_index++){
        //                 var refactor_kinds_child = skus_arr_refactor_kinds[kinds_index];
        //                 for (var child_index = 0;child_index < refactor_kinds_child.length; child_index++){
        //                     // 对已选择的数组进行循环与总数组的值进行比较
        //                     if(refactor_kinds_child[child_index].value === active_value&&
        //                         refactor_kinds_child[child_index].name === active_name){
        //                         if(!_that.hasClass("allow-choose")){
        //                             // 如果点击了允许之外的选在则将临时仓库进行更新
        //                             temporary_storage.push(refactor_kinds_child);
        //                         }
        //                     }
        //                 }
        //             }
        //         }else {
        //             //   当用户点击的允许的选项时根据选项从总的数组中进行选择
        //             for(var kinds_index = 0; kinds_index<temporary_storage.length; kinds_index++){
        //                 var refactor_kinds_child = skus_arr_refactor_kinds[kinds_index];
        //                 for (var child_index = 0;child_index < refactor_kinds_child.length; child_index++){
        //                     // 对已选择的数组进行循环与总数组的值进行比较
        //                     if(refactor_kinds_child[child_index].value === active_value&&
        //                         refactor_kinds_child[child_index].name === active_name){
        //                         if(!_that.hasClass("allow-choose")){
        //                             // 如果点击了允许之外的选在则将临时仓库进行更新
        //                             temporary_storage.push(refactor_kinds_child);
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     // 将临时仓库进行整合处理，方便循环
        //     var aimSelect ;
        //     var temporary_storage_change1 = [];
        //     var temporary_storage_change2 = [];
        //     var temporary_storage_map = {};
        //     dataFusion(temporary_storage,temporary_storage_change1);
        //     dataFusionClassify(temporary_storage_change1,temporary_storage_change2,temporary_storage_map,'name');
        //     $.each(temporary_storage_change2,function (storage_index,storage_value) {
        //         var storage_value_item =arrayUnique2(storage_value.data,'value');
        //         storage_value_item.sort(compare("value"));
        //         aimSelect = $(".sku-choose-store").find("ul[data-name='"+ storage_value.name +"']");
        //         $(aimSelect).find("li").removeClass("allow-choose");
        //         $(aimSelect).find("li").addClass("forbid-choose");
        //         $(aimSelect).find("li").addClass("forbid-choose-style");
        //         $.each(storage_value_item,function (storage_value_index,storage_value_content) {
        //             $(aimSelect).find("li[data-value='"+ storage_value_content.value.replace(/\'|\"/g,"") +"']").removeClass("forbid-choose");
        //             $(aimSelect).find("li[data-value='"+ storage_value_content.value.replace(/\'|\"/g,"") +"']").removeClass("forbid-choose-style");
        //             $(aimSelect).find("li[data-value='"+ storage_value_content.value.replace(/\'|\"/g,"") +"']").addClass("allow-choose");
        //         });
        //         $.each(already_selected,function (already_selected_key,already_selected_value) {
        //             if(aimSelect.attr("data-name") === already_selected_value.name) {
        //                 if(!$(aimSelect).find("li[data-value='"+ already_selected_value.value.replace(/\'|\"/g,"") +"']").hasClass("forbid-choose")){
        //                     $(aimSelect).parents(".sku-select-module").find(".sku-select-value-show").html(already_selected_value.value)
        //                     $(aimSelect).parents(".sku-select-module").find("input").val(already_selected_value.value.replace(/\'|\"/g,""));
        //                 }else {
        //                     $(aimSelect).parents(".sku-select-module").find(".sku-select-value-show").html($($(aimSelect).find("li[class='allow-choose']")[0]).html());
        //                     $(aimSelect).parents(".sku-select-module").find("input").val($($(aimSelect).find("li[class='allow-choose']")[0]).html().replace(/\'|\"/g,""));
        //                     // 如果已显泽的
        //                     active_name = already_selected_value.name; // 用户选择的sku对用的name
        //                     active_value = $($(aimSelect).find("li[class='allow-choose']")[0]).html().replace(/\'|\"/g,"");  // 用户选择的sku对应的value值
        //                     exists = false;    // 用于判断数组是否存在重复选择的值
        //                     exists = isExists(already_selected,'name',active_name);
        //                     if (exists){
        //                         delOldData(already_selected,'name',active_name);
        //                     }
        //                     already_selected.push({
        //                         name:active_name,
        //                         value:active_value
        //                     });
        //                 }
        //             }
        //         });
        //     })
        //     if(already_selected.length == skus_arr_refactor_map_arr.length){
        //         getSkuId();
        //     }
        // });

        // 点击空白处关闭弹窗
        $(document).mouseup(function(e) {
            var  pop = $('.sku-choose-store');
            if(!pop.is(e.target) && pop.has(e.target).length === 0) {
                pop.find(".sku-select-options").slideUp();
                pop.find(".sku-select-value").removeClass("active");
            }
        });
    //    放大镜图片增加方法
        function magnifyingAdd(zoomImgUrl){
            $(".for-choose-img").removeClass("dis_ni");
            $(".for-choose-img").find("a").prop("href",zoomImgUrl);
            $(".for-choose-img").find("img").prop("src",zoomImgUrl);
            $(".for-choose-img").find("img").attr("data-cloudzoom" ,"useZoom:'.cloudzoom', image:'"+ zoomImgUrl +"' ");
            $("#zoom1").prop("src",zoomImgUrl);
            $("#zoom-btn").prop("href",zoomImgUrl);
            $("#slider1").find(".firstzoomColorBoxs").addClass("zoomColorBoxs");
            $("#slider1").find(".firstzoomColorBoxs").addClass("cboxElement");
            // 插入新节点后重新初始化放大镜插件
            CloudZoom.quickStart();
            //插入新节点后重新初始化zoom弹窗
            $(".zoomColorBoxs").colorbox({
                rel: 'zoomColorBoxs',
                opacity:0.5,
                speed: 300,
                current: '{current} / {total}',
                previous: '',
                next: '',
                close: '',  //No comma here
                maxWidth: '95%',
                maxHeight: '95%'
            });
            $("#slider1").find("img").removeClass("cloudzoom-gallery-active");
            $(".for-choose-img").find("img").addClass("cloudzoom-gallery-active");
        }
    </script>
@endsection
