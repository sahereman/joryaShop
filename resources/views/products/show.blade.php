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
                            <span class="price" id="product-price-695"><i>{{ get_global_symbol() }} </i>{{ get_current_price($product->price) }}</span>
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
        var sku_id, sku_stock, sku_price, sku_original_price;
        var product = {!! $product !!};
        // 控制商品下单的数量显示
        $(".add").on("click", function () {
            // 获取商品ID及库存数量
            getSkuId();
            if(haschoose == false){
                layer.msg("Please Select");
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
                getSkuId();
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
                // 获取sku_id
                getSkuId();
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
            skus_arr_coalesce = [], // 存放json第一次循环后的混合数组
            map = {},  // 定义一个map对象用于数组中具有相同name值得数据进行合并  // 根据数组的name属性对数据进行map操作与数组整合
            skus_map = [],  // 经过map操作融合后的数据,同时对该数组进行循环，将内容渲染到页面中
            already_selected = [],   // 存放用户做出选择的skus参数的数组
            temporary_storage = [], // 用于临时存储根据索引从原始数组中存储的数据
            haschoose = true,   // 定义判断用户是否已经进行选择
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
        dataFusion(skus_arr,skus_arr_coalesce);
        //当 数据完成第一次处理之后将页面中的input的value进行置空
        sku_parameter.sku_data_warehouse.val("");
        // 第二种数据分组的方式
        // var attr_value_options = [];
        // $.each(skus_arr, function (product_sku_id, product_sku_attr_values) {
        //     $.each(product_sku_attr_values, function (product_sku_attr_values_index, product_sku_attr_value) {
        //         if (! attr_value_options[product_sku_attr_value.name]) {
        //             attr_value_options[product_sku_attr_value.name] = [];
        //         }
        //         if (attr_value_options[product_sku_attr_value.name].indexOf(product_sku_attr_value.value) == -1) {
        //             attr_value_options[product_sku_attr_value.name].push(product_sku_attr_value.value);
        //         }
        //     })
        // });
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
        dataFusionClassify(skus_arr_coalesce,skus_map,map,'name');
         // 根据数组对象进行去重
        function arrayUnique2(arr, name) {
            var hash = {};
            return arr.reduce(function (item, next) {
                hash[next[name]] ? '' : hash[next[name]] = true && item.push(next);
                return item;
            }, []);
        }
         //   对处理过后的数据进行循环，动态生成相应的DOM节点
        //   虚拟的DOM结构
        // <div class="priceOfpro forgetSel">
        //   <span class="dynamic_name">参数名</span>
        //   <select name="parameter-name">
        //     <option value="参数值">参数值</option>
        //   </select>
        // </div>
        function renderingNode(){
            $.each(skus_map,function (sku_map_i,sku_map_n) {
                // 如果名称为undefind代表该属性为库存价格等
                sku_parameter.html += "<div class='priceOfpro forgetSel'>"
                if(sku_map_n.name == "Hair Color"){
                    sku_parameter.html += "<span class='dynamic_name' data-type='Hair Color'>"+ sku_map_n.name +" <a target='_blank' href='{{ asset('img/HairColor.jpg') }}'><img src='{{ asset('img/photo-choose.png') }}'></a></span>"
                }else if(sku_map_n.name == "Hair Density"){
                    sku_parameter.html += "<span class='dynamic_name'>"+ sku_map_n.name +" <a target='_blank' href='{{ asset('img/HairDensity.jpg') }}'><img src='{{ asset('img/photo-choose.png') }}'></a></span>"
                }else{
                    sku_parameter.html += "<span class='dynamic_name'>"+ sku_map_n.name +" </span>"
                }
                sku_parameter.html += "<select data-index='"+ sku_map_i +"' name='"+ sku_map_n.name +"'>"
                var sku_map_item =arrayUnique2(sku_map_n.data,'value');
                sku_map_item.sort(compare("value"));
                sku_parameter.html += "<option value='select' disabled selected>Please Select</option>"
                $.each(sku_map_item,function (sku_map_data_i,sku_map_data_n) {
                    if(sku_map_n.name == "Hair Color") {
                        sku_parameter.html += "<option value='" + sku_map_data_n.value + "' data-img='"+ sku_map_data_n.photo_url +"'>"+ sku_map_data_n.value +"</option>"
                    }else {
                        sku_parameter.html += "<option value='" + sku_map_data_n.value + "'>"+ sku_map_data_n.value +"</option>"
                    }
                });
                sku_parameter.html += "</select>"
                sku_parameter.html += "</div>"
            });
        }
        renderingNode();
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
            var searchArr = [],
                newSkuArray = [],
                childSkuArr = [],
                firstResultArr = [],
                secondResultArr = [],
                forTipArr = [];
            var allChooseSelect = $(".sku-choose-store").find("select");
            $.each(allChooseSelect,function (chooseSelect_in,chooseSelect_value) {
                if($(chooseSelect_value).val() == "select"||!$(chooseSelect_value).val()){
                    console.log($(chooseSelect_value).val())
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
            for(var arr_key in skus_arr) {
                newSkuArray.push({id:arr_key,data:skus_arr[arr_key]});
                for(var newSkuArrayKey in newSkuArray) {
                    childSkuArr = newSkuArray[newSkuArrayKey].data
                    for (var childSkuArrKey in childSkuArr){
                        for (var searchArrKey in searchArr){
                            if (childSkuArr[childSkuArrKey].value!=undefined&&searchArr[searchArrKey].value!=undefined) {
                                if(childSkuArr[childSkuArrKey].value == searchArr[searchArrKey].value&&childSkuArr[childSkuArrKey].name == searchArr[searchArrKey].name){
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
                        if(firstResultData[firstResultData_i].name==searchArr[searchArr_index].name&&firstResultData[firstResultData_i].value==searchArr[searchArr_index].value){
                            secondResultArr.push(firstResultData[firstResultData_i]);
                        }
                    }
                }
            });
            var searchResultMap = {},
                finalArray = [];
            var aimLength = $(".sku-choose-store").find('select').length;
            dataFusionClassify(secondResultArr,finalArray,searchResultMap,'product_sku_id');
            $.each(finalArray,function (finalArray_i,finalArray_n) {
                if(finalArray_n.data.length == aimLength) {
                    sku_id = finalArray_n.name;
                    sku_stock = skus_arr[sku_id][0].stock
                }
            });
        }
        sku_parameter.sku_choose_store.html("").append(sku_parameter.html);
        sku_parameter.sku_choose_store.find("select").on("change",function () {
            $("#pro_num").val("1");
            var _that = $(this),
                selected_val = _that.val();
            // 插入放大镜图片的内容与数据无关
            if(_that.parents(".forgetSel").find(".dynamic_name").attr("data-type") == "Hair Color") {
                $(".for-choose-img").removeClass("dis_ni");
                $(".for-choose-img").find("a").prop("href",_that.find("option:selected").attr("data-img"));
                $(".for-choose-img").find("img").prop("src",_that.find("option:selected").attr("data-img"));
                $(".for-choose-img").find("img").attr("data-cloudzoom" ,"useZoom:'.cloudzoom', image:'"+ _that.find("option:selected").attr("data-img") +"' ");
                $("#zoom1").prop("src",_that.find("option:selected").attr("data-img"));
                $("#zoom-btn").prop("href",_that.find("option:selected").attr("data-img"));
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
             // 每次切换时将临时仓库进行置空操作
             temporary_storage = [];
             sku_parameter.choose_classify_index = $(this).attr("data-index");
        // for(var change_index = 0;change_index<=skus_map.length-1;change_index++){
            // 判断当前的操作的select的DOM节点
            // if(change_index == sku_parameter.choose_classify_index) {
                for(var search_key in skus_arr){
                    for (var search_key_child in skus_arr[search_key]) {
                            // 通过轮询的方式在原始数组查找与所选项目相同的数组内容
                            if(skus_arr[search_key][search_key_child].value == selected_val){
                                // already_selected为用于存放用户已选择的选项的数组
                                // 如果数组为空便将用户选择的内容添加到数组中
                                // 如果数组不为空则分两种情况考虑：
                                // 1、以skus_map数组为依据判断页面中sku种类的数量，如果already_selected的长度小于skus_map的长度则继续添加新的数据
                                //    添加新数据的同时判断数组内是否已存在当前操作的select的已选值，如果存在则更新，反之则继续添加
                                // 2、同样是根据skus_map数组的长度进行判断，当长度相同时代表用户对所有的sku参数都已经进行过操作如果继续操作则只需要更新即可
                                //  ！！！！！当存在重置功能时，重置时也需要将already_selected进行置空操作
                                var select_index = _that.attr("data-index"),     // 用户进行操作的DOM的标号
                                    active_sku_id = skus_arr[search_key][search_key_child].product_sku_id,  // 用户选择的sku对用的id
                                    active_value = skus_arr[search_key][search_key_child].value,  // 用户选择的sku对应的value值
                                    exists = false;    // 用于判断数组是否存在重复选择的值
                                if(already_selected.length < skus_map.length ||already_selected.length == 0) {
                                    if(already_selected.length != 0) {
                                        // 用来判断数组是否存在重复的select的选择标号
                                        exists = isExists(already_selected,'select_index',select_index);
                                    }
                                    if (exists){
                                        delOldData(already_selected,'select_index',select_index);
                                    }
                                }else {
                                    exists = isExists(already_selected,'select_index',select_index);
                                    if (exists){
                                        delOldData(already_selected,'select_index',select_index);
                                    }
                                }
                                // 将已选择的参数添加到数组中进行存储
                                already_selected.push({select_index: select_index,product_sku_id: active_sku_id,value:active_value,name:_that.attr('name')});
                                // 将查找到的数据存储到临时仓库中
                                temporary_storage.push(skus_arr[search_key]);
                                // console.log(temporary_storage)
                                if (temporary_storage.length == 1) {
                                    sku_id = temporary_storage[0][0].product_sku_id;
                                }
                            //    通过对临时仓库和以选择仓库的数据进行对比查找到相应的已选择的
                            }
                    }
                }
                //   将搜索的结果进行处理对剩下的select的option选项进行刷新
                var temporary_storage_change1 = [],
                    temporary_storage_change2 = [],
                    temporary_storage_map = {};
                dataFusion(temporary_storage,temporary_storage_change1);
                dataFusionClassify(temporary_storage_change1,temporary_storage_change2,temporary_storage_map,'name');
                var aimSelect ;
                    // 将处理好的数据进行渲染    optionHtml
                $.each(temporary_storage_change2,function (storage_index,storage_value) {
                    var storage_value_item =arrayUnique2(storage_value.data,'value');
                    storage_value_item.sort(compare("value"));
                    sku_parameter.optionHtml = "";
                    aimSelect = $(".sku-choose-store").find("select[name='"+ storage_value.name +"']");
                    // $(aimSelect).find("option").prop("disabled",true);
                    $(aimSelect).find("option").removeClass("allow-choose");
                    $(aimSelect).find("option").addClass("forbid-choose");
                    $.each(storage_value_item,function (storage_value_index,storage_value_content) {
                        // sku_parameter.optionHtml += "<option value='" + storage_value_content.value + "'>"+ storage_value_content.value +"</option>"
                        $(aimSelect).find("option[value='"+ storage_value_content.value +"']").prop("disabled",false);
                        $(aimSelect).find("option[value='"+ storage_value_content.value +"']").removeClass("forbid-choose");
                        $(aimSelect).find("option[value='"+ storage_value_content.value +"']").addClass("allow-choose");
                    });
                    // $(aimSelect).find("option").remove();
                    // $(aimSelect).append(sku_parameter.optionHtml);
                    $.each(already_selected,function (already_selected_key,already_selected_value) {
                        if(aimSelect.attr("data-index") == already_selected_value.select_index) {
                            if(!$(aimSelect).find("option[value='"+ already_selected_value.value +"']").hasClass("forbid-choose")){
                                $(aimSelect).find("option[value='"+ already_selected_value.value +"']").prop("selected",true);
                            }else {
                                $($(aimSelect).find("option[class='allow-choose']")[0]).prop("selected",true);
                                // layer.msg("The selected combination is currently out of stock and the selected properties have been reset")
                            }
                        }
                    });
                    // $(aimSelect).find("option[value='"+ selected_val +"']").attr("selected",true);
                })
            // }else {
            //
            // }
        // }
        });
        // 重置筛选条件
        $(".Reset-filter").on("click",function () {
            already_selected = []
            $("#sku-choose-store").find("select").val("select");
            $("#sku-choose-store").find("option").prop("disabled",false);
            $("#sku-choose-store").find("option[value='select']").prop("disabled",true);
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

    </script>
@endsection
