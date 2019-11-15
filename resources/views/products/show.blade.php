@extends('layouts.app')
@section('keywords', $product->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $product->seo_description ? : (App::isLocale('zh-CN') ? $product->description_zh : $product->description_en))
@section('og:image', $product->photo_urls[0])
@section('twitter:image', $product->photo_urls[0])
@section('title', $product->seo_title ? : (App::isLocale('zh-CN') ? $product->name_zh : $product->name_en) . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="commodity-details">
        <div class="main-content">
            <!--面包屑-->
            <div class="Crumbs-box">
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
                                             data-cloudzoom='zoomSizeMode:"zoom",zoomPosition: 3,startMagnification: 2'>
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
                    {{-- 简介 --}}
                    <div class="short-description">
                        <div class="std">
                            {{-- <textarea class="std-content" name="" id="topArticle" cols="30" readonly>{!! App::isLocale('zh-CN') ? $product->description_zh : $product->description_en !!}</textarea> --}}
                            <p class="std-content" id="topArticle" readonly>{!! App::isLocale('zh-CN') ? $product->description_zh : $product->description_en !!}</p>
                        </div>
                        {{-- <a href="javascript:void(0)" class="down-more" id="down-more">
                            <img src=" {{ asset('img/down-more.png') }}" alt="">
                        </a> --}}
                    </div>
                    {{-- 评价 --}}
                    <div class="ratings">
                        <div class="rating-box dis_ni">
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
                            <a id="goto-reviews" href="#customer-reviews">Review({{ $product->comment_count }})</a>
                            {{-- <span class="separator">|</span> --}}
                            {{-- <a id="goto-reviews-form" href="#customer-reviews">Add Your Review</a> --}}
                        </p>
                    </div>
                    {{-- 新版价格存放位置 --}}
                    <div class="product-price">
                        <p class="special-price">
                            <span class="price" id="product-price-695"><i>{{ get_global_symbol() }} </i><span id="product-price">{{ get_current_price($product->price) }}</span></span>
                        </p>
                        <p class="old-price">
                            <span class="price" id="old-price-695"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($product->price), 1.2, 2) }}</span>
                        </p>
                        <div class="clear"></div>
                        {{-- @if($shipment_template == null)
                            <div class="free-shipping">FREE SHIPPING</div>
                        @endif --}}

                    </div>
                    {{-- 动态渲染的skus选择器存放位置 --}}
                    @if($product->type == \App\Models\Product::PRODUCT_TYPE_DUPLICATE)
                        {{-- 复制引入 --}}
                        @include('products._duplicate')
                    @elseif($product->type == \App\Models\Product::PRODUCT_TYPE_REPAIR)
                        {{-- 修复引入 --}}
                        @include('products._repair')
                    @else
                        {{-- 正常商品 --}}
                        @if($attributes)
                            <div id="sku-choose-store" class="sku-choose-store {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}" price="{{ $product->price }}">
                                <div class="sku-select">
                                    <div class="sku-select-name">
                                        <span class="dynamic_name">Condition</span>
                                    </div>
                                    <div class="sku-select-module">
                                        <div class="sku-select-value select-for-show">
                                            <span class="sku-select-value-show">New with tags</span>
                                        </div>
                                    </div>
                                </div>
                                @foreach($attributes as $attr_name => $attr_values)
                                    <div class="sku-select">
                                        <div class="sku-select-name">
                                            <span class="dynamic_name" data-paramid="{{ $attr_name }}">{{ $attr_name }}</span>
                                        </div>
                                        <div class="sku-select-module">
                                            <div data-paramid="undefined" data-name="undefined" class="sku-select-value">
                                                <input type="hidden" readonly="" data-paramid="{{ $attr_name }}" value="{{ $attr_values[0]['value'] }}" name="{{ $attr_values[0]['value'] }}"
                                                       photo-url="{{ isset($attr_values[0]['photo_url']) ? $attr_values[0]['photo_url'] : '' }}" delta-price="{{ $attr_values[0]['delta_price'] }}">
                                                <span class="sku-select-value-show">{{ $attr_values[0]['value'] }}</span>
                                            </div>
                                            <div class="sku-select-options" style="display: none;">
                                                <ul data-paramid="undefined" data-name="undefined">
                                                    @foreach($attr_values as $attr_value)
                                                        <li data-paramid="{{ $attr_name }}" data-valueid="{{ $attr_value['value'] }}"
                                                            photo-url="{{ isset($attr_value['photo_url']) ? $attr_value['photo_url'] : '' }}" delta-price="{{ $attr_value['delta_price'] }}">
                                                            {{ $attr_value['value'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- <div class="loading-box">
                                    <img src="{{ asset('img/loading_lord.gif') }}">
                                </div> --}}
                            </div>
                        @endif
                    @endif
                    {{-- skus参数数组 --}}
                    {{-- <input type="hidden" class="parameter-data" data-url="{{ route('products.search_by_sku_attr', ['product' => $product->id]) }}" value="{{ json_encode($attributes) }}"/> --}}
                    {{--<div class="availableSold {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}">--}}
                        {{--<button class="Reset-filter">Reset Select</button>--}}
                    {{--</div>--}}
                    {{-- 商品价格优惠 --}}
                    <ul class="tier-prices product-pricing">
                        @if($product->discounts->isNotEmpty())
                            @foreach($product->discounts as $discount)
                                <li class="tier-price tier-0" data-product-num="{{ $discount->number }}">
                                    <p>Buy {{ $discount->number }}</p>
                                    <p class="benefit"><span class="price">{{ get_global_symbol() . ' ' . get_current_price($discount->price) }}</span> /ea</p>
                                    <span class="msrp-price-hide-message"></span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    {{-- 商品数量相关 --}}
                    <div class="priceOfpro {{ $product->type == \App\Models\Product::PRODUCT_TYPE_CUSTOM ? ' dis_ni' : '' }}">
                        <div class="priceOfpro-left">
                            <span class="buy_numbers">@lang('product.product_details.Quantity'):</span>
                            <div class="quantity_control">
                                <span class="reduce no_allow"><i>-</i></span>
                                <input type="number" name="number" id="pro_num" value="1" min="1" max="99">
                                <span class="add"><i>+</i></span>
                            </div>
                        </div>
                        <div class="priceOfpro-right">
                            @if($product->type != \App\Models\Product::PRODUCT_TYPE_CUSTOM)
                                @guest
                                    <a class="add_favourites for_show_login" >
                                        <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                        <span>Add to wish list</span>
                                    </a>
                                @else
                                    <a class="add_favourites {{ $favourite ? 'active' : '' }}" code="{{ $product->id }}" data-url="{{ route('user_favourites.store') }}"
                                       data-url_2="{{ route('user_favourites.destroy') }}" data-favourite-code="{{ $favourite ? $favourite->id : '' }}">
                                        <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                        <span>{{ $favourite ? 'Remove from wish list' : 'Add to wish list' }}</span>
                                    </a>
                                @endguest
                            @endif
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
                        @elseif($product->type == \App\Models\Product::PRODUCT_TYPE_DUPLICATE)
                            {{-- 复制引入 --}}
                            <a class="buy_now duplicateType" data-url="{{ route('orders.pre_payment') }}" data-url2="{{ route('orders.pre_payment') }}" 
                               data-url-sku="{{ route('products.duplicate.store', ['product' => $product->id]) }}" code="{{ $product->id }}">
                                @lang('product.product_details.Buy now')
                            </a>
                            <a class="add_carts duplicateType" data-url="{{ route('carts.store') }}" 
                               data-url-sku="{{ route('products.duplicate.store', ['product' => $product->id]) }}" code="{{ $product->id }}">
                                @lang('app.Add to Shopping Cart')
                            </a>
                        @elseif($product->type == \App\Models\Product::PRODUCT_TYPE_REPAIR)
                            {{-- 修复引入 --}}
                            <a class="buy_now repairType" data-url="{{ route('orders.pre_payment') }}" data-url2="{{ route('orders.pre_payment') }}"
                               data-url-sku="{{ route('products.repair.store', ['product' => $product->id]) }}" code="{{ $product->id }}">
                                    @lang('product.product_details.Buy now')
                            </a>
                            <a class="add_carts repairType" data-url="{{ route('carts.store') }}"
                               data-url-sku="{{ route('products.repair.store', ['product' => $product->id]) }}" code="{{ $product->id }}">
                                @lang('app.Add to Shopping Cart')
                            </a>
                        @else
                            <a class="buy_now normalType" data-url="{{ route('orders.pre_payment') }}" data-url2="{{ route('orders.pre_payment_by_sku_attr') }}" code="{{ $product->id }}">
                                @lang('product.product_details.Buy now')
                            </a>
                            {{-- <a class="add_carts" data-url="{{ route('carts.store') }}" code="{{ $product->id }}"> --}}
                            <a class="add_carts normalType" data-url="{{ route('carts.store_by_sku_attr') }}" code="{{ $product->id }}">
                                @lang('app.Add to Shopping Cart')
                            </a>
                            {{-- @guest
                                <a class="add_favourites for_show_login" >
                                    <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                    <span>Add to wish list</span>
                                </a>
                            @else
                                <a class="add_favourites {{ $favourite ? 'active' : '' }}" code="{{ $product->id }}"
                                   data-url="{{ route('user_favourites.store') }}"
                                   data-url_2="{{ route('user_favourites.destroy') }}"
                                   data-favourite-code="{{ $favourite ? $favourite->id : '' }}">
                                    <img src="{{ asset('img/favorite-eye.png') }}" alt="">
                                    <span>{{ $favourite ? 'Remove from wish list' : 'Add to wish list' }}</span>
                                </a>
                            @endguest --}}
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
                                    <p>
                                        <span class="info-content-price">
                                            {{ $shipment_template->calc_unit_shipping_fee(1, Auth::user()->default_address->province) }}
                                        </span>
                                        {{ $shipment_template->name }}  {{ $shipment_template->sub_name }} | <a class="info-content-details" href="javascrpt:void(0)">See details</a>
                                    </p>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="info-content">
                                    <p>{{ $shipment_template->description }}</p>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="info-content">
                                    <p>Item Location:</p>
                                    <p>{{ $product->location }}</p>
                                    <p>Ships to: <span>{{ Auth::user()->default_address->province }}</span></p>
                                </div>
                            </div>
                        @endif
                        {{--Payments--}}
                        <div class="content-box payment-info">
                            <div class="info-title">
                                <span>Payments:</span>
                            </div>
                            <div class="info-content">
                                <img src="{{ asset('img/payment-all.png') }}" alt="Lyricalhair.com">
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
                        {{-- 商品详情信息 --}}
                        @if($product->grouped_param_value_string)
                            <p class="category-iframe-title">Item specifics</p>
                            <div class="product_info_table">
                                {{-- <h2 class="info_table_title"></h2> --}}
                                <ul class="info_table_tbody">
                                    {{-- 这个也是循环内容为了查看样式单独拿出来，正式循环时包括在下面的循环里 --}}
                                    @foreach($product->grouped_param_value_string as $key => $value)
                                        <li>
                                            <span class="info_table_name">{{ $key }}:</span>
                                            <span class="info_table_content">{{ $value }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{--商品详情部分iframe--}}
                        <div class="iframe_content dis_ni">
                            {{-- 用来存放后台返回的的iframe的数据或者富文本 --}}
                            {!! $product->content_en !!}
                        </div>
                        <p class="category-iframe-title">Category</p>
                        <div class="category-iframe">
                            {{-- 分类导航 --}}
                            <div class="particulars-category">
                                <ul>
                                    <?php $_ii=1; while ($_ii++ < 14): ?>
                                      <li><span>&#8226;</span><a href="">SALE SALE SALE !!</a></li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                            {{-- 详情内容 --}}
                            <div class="particulars-content">
                                {{-- 页面实际展示的部分，用js进行页面渲染 --}}
                                <h3>Item description</h3>
                                <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>
                            </div>
                        </div>
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
                    <div class="swiper-button-box">
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
    // 修改当前页面的header底色
        $(".header-top").css("background-color","#fff");
        $(".header-bottom").css("background-color","#f6f6f6");
        // 初始化zoom
        CloudZoom.quickStart();
        // 初始化slider
        $(function () {
            $('#slider1').Thumbelina({
                $bwdBut: $('#slider1 .left'),
                $fwdBut: $('#slider1 .right')
            });
        });
        //Init lightbox  图片弹窗
        $(".zoomColorBoxs").colorbox({
            rel: 'zoomColorBoxs',
            opacity: 0.5,
            speed: 300,
            current: '{current} / {total}',
            previous: '',
            next: '',
            close: '',  //No comma here
            maxWidth: '95%',
            maxHeight: '95%'
        });
        // 简介查看更多
        $("#down-more").on("click", function () {
            var _taht = $(this),
                    element = document.getElementById("topArticle"),
                    isHasClass = $(this).hasClass("active");
            if (isHasClass) {
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
        $(".socialization-email-btn").on("click", function () {
            var clickDom = $("#social-email-inp");
            // 将页面可获取的内容进行赋值
            $(".product-share-img").find("img").prop("src", $("#zoom1").prop("src"));
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
                yes: function (index) {
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
        var sku_id = 0, sku_stock = 0, sku_price = 0, sku_original_price = 0, sku_photo_url = '';
        var product = {!! $product !!};
        // 控制商品下单的数量显示
        $(".add").on("click", function () {
            // 获取商品ID及库存数量
            // if (sku_id == 0 || sku_stock == 0) {
            //     layer.msg("The item is temporarily out of stock Please reselect!");
            //     return
            // }
            // if ($(".kindOfPro").find("li").hasClass('active') != true) {
            // layer.msg("@lang('product.product_details.Please select specifications')");
            // } else {
            $(".reduce").removeClass('no_allow');
            // if (parseInt($("#pro_num").val()) < sku_stock) {
                var num = parseInt($("#pro_num").val()) + 1;
                $("#pro_num").val(num);
            // } else {
            //     layer.msg("@lang('order.Cannot add more quantities')");
            // }
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
            if (clickDom.hasClass("for_show_login")) {
                if (!clickDom.hasClass("active")) {
                    clickDom.find("span").text("Remove from wish list");
                    layer.msg("Added to wish list successfully");
                    clickDom.addClass("active");
                } else {
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
                        clickDom.attr("data-favourite-code", data.data.favourite.id);
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
                                arr.push(dataobj[i]); // 属性
                            }
                            layer.msg(arr[0][0]);
                        }
                    },
                });
            }
        });
        // Tab控制函数
        function tabs(tabId, tabNum) {
            // 设置点击后的切换样式
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
        // 修复商品传递相关选项参数 data-url-sku
        function repairParameters(Dom,Operate){
            var repair_attr_values_json = [];
            // custom_attr_values_json.push({
            //     type: 'Hair',
            //     name: 'Grey Hair Need',
            //     value: $("input[name='Need Grey Hair Type']:checked").parents("label").find(".val-text").text(),
            //     delta_price: '0',
            // });
            var allChooseSkus = $("#sku-choose-store").find("input[type='hidden']");
            $.each(allChooseSkus, function (i, n) {
                repair_attr_values_json.push({
                    name: $(n).attr("data-paramid"),
                    value: $(n).val(),
                    delta_price: $(n).attr("delta-price"),
                });
            });
            var data = {
                _token: "{{ csrf_token() }}",
                repair_attr_values: repair_attr_values_json,
            };
            $.ajax({
                type: "post",
                url: $(Dom).attr("data-url-sku"),
                data: data,
                success: function (data) {
                    console.log(data)
                    // window.location.href = $(".addToCartSuccess").val();
                    var product_sku_id = data.data.product_sku_id;
                    if(Operate == "Cart") {
                        addCarts(Dom,product_sku_id)
                    }else {
                        BuyNow(Dom,product_sku_id)
                    }
                },
                error: function (err) {
                    if (err.status == 422) {
                        var exception = err.responseJSON.exception;
                        if (exception) {
                            layer.msg(exception.message);
                        }
                        var arr = [];
                        var errors = err.responseJSON.errors;
                        for (let i in errors) {
                            arr.push(errors[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                },
            });
        }
        // 复制商品传递相关选项参数 data-url-sku
        function duplicateParameters(Dom,Operate){
            var duplicate_attr_values_json = [];
            var allChooseSkus = $("#sku-choose-store").find("input[type='hidden']");
            $.each(allChooseSkus, function (i, n) {
                duplicate_attr_values_json.push({
                    name: $(n).attr("data-paramid"),
                    value: $(n).val(),
                    delta_price: $(n).attr("delta-price"),
                });
            });
            var data = {
                _token: "{{ csrf_token() }}",
                duplicate_attr_values: duplicate_attr_values_json,
            };
            $.ajax({
                type: "post",
                url: $(Dom).attr("data-url-sku"),
                data: data,
                success: function (data) {
                    // window.location.href = $(".addToCartSuccess").val();
                    var product_sku_id = data.data.product_sku_id;
                    if(Operate == "Cart") {
                        addCarts(Dom,product_sku_id)
                    }else {
                        BuyNow(Dom,product_sku_id)
                    }
                },
                error: function (err) {
                    if (err.status == 422) {
                        var exception = err.responseJSON.exception;
                        if (exception) {
                            layer.msg(exception.message);
                        }
                        var arr = [];
                        var errors = err.responseJSON.errors;
                        for (let i in errors) {
                            arr.push(errors[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                },
            });
        }
        // 加入购物车函数
        function addCarts(Dom,SkuId){
            if(SkuId) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_id: SkuId,
                    number: $("#pro_num").val(),
                };
            }else {
                var data = {
                    _token: "{{ csrf_token() }}",
                    // sku_id: sku_id,
                    product_id: Dom.attr("code"),
                    number: $("#pro_num").val(),
                };
                var allChooseSkus = $("#sku-choose-store").find("input[type='hidden']");
                $.each(allChooseSkus, function (i, n) {
                    data["product_sku_attr_values[" + $(n).attr("data-paramid") + "]"] = $(n).val()
                });
            }
            // console.log(data)
            var url = Dom.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    layer.msg("@lang('product.product_details.Shopping cart added successfully')");
                    var oldCartNum = parseInt($(".shop_cart_num").html());
                    var newCartNum = oldCartNum + parseInt($("#pro_num").val());
                    $(".shop_cart_num").html(newCartNum);
                    // $(".for_cart_num").load(location.href + " .shop_cart_num");
                },
                error: function (err) {
                    var arr = [];
                    var dataobj = err.responseJSON.errors;
                    for (let i in dataobj) {
                        arr.push(dataobj[i]); // 属性
                    }
                    layer.msg(arr[0][0]);
                }
            });
        }
        // 立即购买函数
        function BuyNow(Dom,SkuId){
            var url = Dom.attr('data-url');
            var url2 = Dom.attr('data-url2');
            var method = "post"
            if(SkuId) {
                var data = {
                    _token: "{{ csrf_token() }}",
                    sku_id: SkuId,
                    number: $("#pro_num").val(),
                };
                method = "get"
            }else {
                var data = {
                    _token: "{{ csrf_token() }}",
                    // sku_id: sku_id,
                    product_id: Dom.attr("code"),
                    number: $("#pro_num").val(),
                };
                var allChooseSkus = $("#sku-choose-store").find("input[type='hidden']");
                $.each(allChooseSkus, function (i, n) {
                    data["product_sku_attr_values[" + $(n).attr("data-paramid") + "]"] = $(n).val()
                });
                method = "post"
            }
            $.ajax({
                type: method,
                url: url2,
                data: data,
                success: function (data) {
                    if(SkuId) {
                        window.location.href = url + "?sku_id=" + SkuId + "&number=" + $("#pro_num").val() + "&sendWay=1";
                    }else {
                        window.location.href = url + "?sku_id=" + data.data.sku_id + "&number=" + $("#pro_num").val() + "&sendWay=1";
                    }
                    // layer.msg("@lang('product.product_details.Shopping cart added successfully')");
                    // var oldCartNum = parseInt($(".shop_cart_num").html());
                    // var newCartNum = oldCartNum + parseInt($("#pro_num").val())
                    // $(".shop_cart_num").html(newCartNum);
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
        }
        // 加入购物车
        $(".add_carts").on("click", function () {
            var clickDom = $(this);
            if (product.type == "custom") {
                window.location.href = "{{ route('products.custom.show', ['product' => $product->id]) }}";
            } 
            if(clickDom.hasClass("repairType")){
                repairParameters(clickDom,"Cart");
            }
            if(clickDom.hasClass("duplicateType")){
                duplicateParameters(clickDom,"Cart")
            }
            if(clickDom.hasClass("normalType")){
                addCarts(clickDom,false)
            }
            
        });
        // 立即购买
        $(".buy_now").on("click", function () {
            var clickDom = $(this);
            if(clickDom.hasClass("repairType")){
                repairParameters(clickDom,"Buy");
            }
            if(clickDom.hasClass("duplicateType")){
                duplicateParameters(clickDom,"Buy");
            }
            if(clickDom.hasClass("normalType")){
                BuyNow(clickDom,false);
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
                            html += "<dt>";
                            html += "<span class='heading'>" + n.title + "</span>Review by <span>" + n.user.name + "</span>";
                            html += "</dt>";
                            html += "<dd>";
                            html += "<table class='ratings-table'>";
                            html += "<colgroup><col width='1'>";
                            html += "<col>";
                            html += "</colgroup><tbody>";
                            html += "<tr>";
                            html += "<th>Product Rating</th>";
                            html += "<td>";
                            html += "<div class='rating-box'>";
                            html += "<div class='rating' style='width:" + n.index + "%;'></div>";
                            html += "</div>";
                            html += "</td>";
                            html += "</tr>";
                            html += "</tbody>";
                            html += "</table>";
                            html += "<p>" + n.content + "</p>";
                            html += "<small class='date'>(Posted on " + n.created_at + ")</small>";
                            html += "</dd>";
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
                        } else {
                            $(".pre_page").removeClass("not_allow");
                            $(".pre_page").attr("disabled", false);
                        }
                        if (json.data.next_url == false) {
                            $(".next_page").addClass("not_allow");
                            $(".next_page").attr("disabled", true);
                        } else {
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
            if (!$(this).hasClass("not_allow")) {
                getComments($(this).attr("data-url"));
            }
        });
        // 下一页
        $(".next_page").on("click", function () {
            if (!$(this).hasClass("not_allow")) {
                getComments($(this).attr("data-url"));
            }
        });
        // FAQS的标题点击
        $(".faqs-content").on("click", ".faq_ques", function () {
            var clickDom = $(this),
                    index = clickDom.parent(".faq_qus_ans").index();
            if (clickDom.hasClass("active")) {
                clickDom.removeClass("active");
                clickDom.parent(".faq_qus_ans").find(".faq_ans").slideUp();
            } else {
                // $(".faqs-content").find(".faq_ques").removeClass("active");
                // $(".faqs-content").find(".faq_ans").slideUp();
                clickDom.addClass("active");
                clickDom.parent(".faq_qus_ans").find(".faq_ans").slideDown();
                var element = document.getElementById("ans_content_" + index);
                element.style.height = element.scrollHeight + "px";
            }
        });
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
        $('#cmsCon').contents().find('body').find("a").css("text-decoration", "none");
        var x = document.getElementById('cmsCon').contentWindow.document.getElementsByTagName('table');
        x.border = "1";
        autoHeight();  //动态调整高度
        var count = 0;
        var autoSet = window.setInterval('autoHeight()', 500);
        function autoHeight() {
            var mainheight;
            count++;
            if (count == 1) {
                mainheight = $('.cmsCon').contents().find("body").height() + 50;
            } else {
                mainheight = $('.cmsCon').contents().find("body").height() + 24;
            }
            $('.cmsCon').height(mainheight);
            if (count == 5) {
                window.clearInterval(autoSet);
            }
        }

        // 获取sku相关数据
        function GetSkus(data) {
            var searchUrl = $(".parameter-data").attr("data-url");
            $.ajax({
                type: "POST",
                url: searchUrl,
                data: data,
                beforeSend: function(){},
                success: function (json) {
                    console.log(json);
                    // 进行页面渲染
                    renderPage(json.data);
                    sku_id = json.data.selected.sku.id;
                    // sku_stock = json.data.selected.sku.stock;
                    sku_photo_url = json.data.selected.sku.photo_url;
                    var price = json.data.selected.sku.price;
                    $("#product-price").html(price);
                    // console.log(data);
                    if(data){
                        // console.log("出现图片")
                        magnifyingAdd(sku_photo_url);
                    }
                },
                error: function (err) {
                    console.log(err);
                    if (err.status == 422) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (var i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                },
                complete:function(){},
            });
        }
        // GetSkus();
        // 页面渲染函数
        function renderPage(searchData){
            console.log(searchData);
            // 用于页面渲染Dom
            var skuDomHtml = "";
            $.each(searchData.data,function (skuKindsArr_index,skuKindsArr_val) {
                skuDomHtml += "<div class='sku-select'>";
                skuDomHtml += "<div class='sku-select-name'>";
                skuDomHtml += "<span class='dynamic_name' data-paramId='"+ skuKindsArr_index +"' >"+ skuKindsArr_index +" </span>";
                skuDomHtml += "</div>";
                skuDomHtml += "<div class='sku-select-module'>";
                skuDomHtml += "<div data-paramId='"+ skuKindsArr_val.paramId +"'  data-name='"+ skuKindsArr_val.paramValue +"' class='sku-select-value'>";
                skuDomHtml += "<input type='hidden' readonly data-paramId='"+ skuKindsArr_index +"' value='"+ searchData.selected[skuKindsArr_index] +"' name='"+ searchData.selected[skuKindsArr_index]+"'>";
                skuDomHtml += "<span class='sku-select-value-show'>"+ searchData.selected[skuKindsArr_index] +"</span>";
                skuDomHtml += "</div>";
                skuDomHtml += "<div class='sku-select-options'>";
                skuDomHtml += "<ul data-paramId='"+ skuKindsArr_val.paramId +"' data-name='"+ skuKindsArr_val.paramValue +"'>";
                if (skuKindsArr_val.true) {
                    $.each(skuKindsArr_val.true, function (sku_map_data_i, sku_map_data_n) {
                        if (sku_map_data_n.switch) {
                            skuDomHtml += "<li data-paramId='" + skuKindsArr_index + "' data-valueId='" + sku_map_data_n.value + "'>" + sku_map_data_n.value + "</li>";
                        } else {
                            skuDomHtml += "<li class='forbid-choose' data-paramId='" + skuKindsArr_index + "' data-valueId='" + sku_map_data_n.value + "'>" + sku_map_data_n.value + "</li>";
                        }
                    });
                }
                if (skuKindsArr_val.false) {
                    $.each(skuKindsArr_val.false, function (sku_map_data_i, sku_map_data_n) {
                        if (sku_map_data_n.switch) {
                            skuDomHtml += "<li data-paramId='" + skuKindsArr_index + "' data-valueId='" + sku_map_data_n.value + "'>" + sku_map_data_n.value + "</li>";
                        } else {
                            skuDomHtml += "<li class='forbid-choose' data-paramId='" + skuKindsArr_index + "' data-valueId='" + sku_map_data_n.value + "'>" + sku_map_data_n.value + "</li>";
                        }
                    });
                }
                skuDomHtml += "</ul>";
                skuDomHtml += "</div>";
                skuDomHtml += "</div>";
                skuDomHtml += "</div>";
            });
            $("#sku-choose-store").html("").append(skuDomHtml);
        }
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

        // 原始基础价格

        var _INITIALPRICE = float_multiply_by_100($("#sku-choose-store").attr("price"));

        // 计算后新的价格数

        var _NEWPRICE = float_multiply_by_100($("#sku-choose-store").attr("price"));    // 新的价格数

        // 临时数据存储

        var _CHOOSEPRICEARR = [];            // 用来存储所有选择的价格的数组

        $("#sku-choose-store").on("click", "li", function () {
            var _that = $(this),
                selected_val = _that.attr("data-valueId"),
                _that_parent = _that.parent("ul");
            _that.parents(".sku-select-module").find(".sku-select-value-show").html(_that.html());
            _that.parents(".sku-select-module").find("input").val(selected_val);
            _that.parents(".sku-select-module").find("input").attr("delta-price",_that.attr("delta-price"));
            $("#sku-choose-store").find(".sku-select-value").removeClass("active");
            $("#sku-choose-store").find(".sku-select-options").slideUp();
            $("#pro_num").val("1");
            var photo_url = _that.attr("photo-url");
            if(photo_url != ""){
                magnifyingAdd(photo_url)
            }
            if(_that.find(".text-span").length!=0){
                // 修复商品
                // $("#product-price").text(_that.attr("delta-price"));
                priceTotal(_that)
                _that.parents(".sku-select-module").find("input").attr("delta-property",_that.attr("delta-price"));
            }else {
                // 非修复和复制商品
                // 所有sku选择器中input的价格数组
                _CHOOSEPRICEARR = [];
                var sku_input_prices = $("#sku-choose-store").find("input[type='hidden']")
                $.each(sku_input_prices,function(i,n){
                    _CHOOSEPRICEARR.push( float_multiply_by_100($(n).attr("delta-price")))
                })
                var addPrice = _CHOOSEPRICEARR.reduce(function(a, b) {
                    return Math.max(a, b);
                });
                _NEWPRICE = _INITIALPRICE + addPrice;
                $("#product-price").text(js_number_format(_NEWPRICE/100));
            }
            
            // 根据所选择的内容，获取sku接口
            // var data = {};
            // if (_that.hasClass("forbid-choose")) {
            //     data["product_sku_attr_values[" + _that.attr("data-paramid") + "]"] = _that.attr("data-valueid");
            // } else {
            //     var allChooseInput = $("#sku-choose-store").find("input[type='hidden']");
            //     $.each(allChooseInput, function (i, n) {
            //         data["product_sku_attr_values[" + $(n).attr("data-paramid") + "]"] = $(n).val()
            //     })
            // }
            // console.log(data)
            // GetSkus(data)
        });

        // 点击空白处关闭弹窗
        $(document).mouseup(function(e) {
            var  pop = $('.sku-choose-store');
            if(!pop.is(e.target) && pop.has(e.target).length === 0) {
                pop.find(".sku-select-options").slideUp();
                pop.find(".sku-select-value").removeClass("active");
            }
        });
        // 放大镜图片增加方法
        function magnifyingAdd(zoomImgUrl) {
            $(".for-choose-img").removeClass("dis_ni");
            $(".for-choose-img").find("a").prop("href", zoomImgUrl);
            $(".for-choose-img").find("img").prop("src", zoomImgUrl);
            $(".for-choose-img").find("img").attr("data-cloudzoom", "useZoom:'.cloudzoom', image:'" + zoomImgUrl + "' ");
            $("#zoom1").prop("src", zoomImgUrl);
            $("#zoom-btn").prop("href", zoomImgUrl);
            $("#slider1").find(".firstzoomColorBoxs").addClass("zoomColorBoxs");
            $("#slider1").find(".firstzoomColorBoxs").addClass("cboxElement");
            // 插入新节点后重新初始化放大镜插件
            CloudZoom.quickStart();
            //插入新节点后重新初始化zoom弹窗
            $(".zoomColorBoxs").colorbox({
                rel: 'zoomColorBoxs',
                opacity: 0.5,
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
        //数据计算方法
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
        // 选择优惠价格
        $(".tier-prices").on("click","li",function(){
            if($(this).hasClass("active")){
                $(".tier-prices").find("li").removeClass("active");
                $("#pro_num").val(1);
            }else {
                $(".tier-prices").find("li").removeClass("active");
                $(this).addClass("active");
                var tierNum = $(this).attr("data-product-num");
                $("#pro_num").val(tierNum);
            }
        });
        // 价格合计函数 (修复商品和复制商品使用)
        var hasChoosePrice = [];  // 已经选择的sku相关数组
        var initialPrice = float_multiply_by_100($("#sku-choose-store").attr("price"));  // 商品的原始价格
        var newPrice = initialPrice; //商品进行选择后的价格计算结果，默认值为商品默认价格
        var choosePrice = 0; //选择的商品价格

        function priceTotal(Dom){
            // 判断是否有价格参数
            var isExist = false;
            if(hasChoosePrice.length == 0){
                choosePrice = float_multiply_by_100(Dom.attr("delta-price"));
                newPrice = choosePrice + newPrice;
                hasChoosePrice.push({"name": Dom.parents(".sku-select-module").find("input").attr("data-paramid"), "price": choosePrice});
            }else {
                for (var i in hasChoosePrice) {
                    if (hasChoosePrice[i].name == Dom.parents(".sku-select-module").find("input").attr("data-paramid")) {
                        isExist = true;
                        newPrice = newPrice - hasChoosePrice[i].price;
                        choosePrice = float_multiply_by_100(Dom.attr("delta-price"));
                        hasChoosePrice[i].price = choosePrice;
                        newPrice = choosePrice + newPrice;
                    }
                }
                if (!isExist) {
                    choosePrice = float_multiply_by_100(Dom.attr("delta-price"));
                    newPrice = choosePrice + newPrice;
                    hasChoosePrice.push({"name": Dom.parents(".sku-select-module").find("input").attr("data-paramid"), "price": choosePrice});
                }
            }
            $("#product-price").text(js_number_format(newPrice / 100));
        }
    </script>
@endsection
