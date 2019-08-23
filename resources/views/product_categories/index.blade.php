@extends('layouts.app')
@section('keywords', $category->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $category->seo_description ? : \App\Models\Config::config('description'))
@section('title', $category->seo_title ? : $category->name_en . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="productCate my_orders">
        <div class="container main productCate-content">
            <div class="col-left">
                {{-- 搜索页不显示 --}}
                <div class="block block-layered-nav">
                    <div class="block-title">
                        <strong><span>Categories</span></strong>
                    </div>
                    <div class="block-content">
                        <div class="categories-lists-items categories-menu">
                            @foreach(\App\Models\ProductCategory::categories() as $product_category)
                            <div class="categories-lists-item">
                                <div class="lists-item-title">
                                    {{--<a href="{{ route('seo_url', $product_category->slug) }}">--}}
                                    <a href="javascript:void(0)">
                                        <span>{{ $product_category->name_en }}</span>
                                    </a>
                                </div>
                                @if($product_category->children->isNotEmpty())
                                <ul class="categories-lists-item-ul">
                                    @foreach($product_category->children as $child)
                                    <li>
                                        <a href="{{ route('seo_url', $child->slug) }}"><span>{{ $child->name_en }}</span></a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="Crumbs-box">
                    <p class="Crumbs">
                        <a href="{{ route('root') }}">@lang('basic.home')</a>
                        {!! $crumbs !!}
                    </p>
                </div>
                <div class="category-image">
                    @if(!empty($category->banner))
                        <img src="{{ $category->banner_url }}">
                    @else
                        <img src="{{ asset('defaults/defaults_pc_category_banner.png') }}">
                    @endif
                </div>
                <div class="page-title category-title">
                    <h1>{{ $category->name_en }}</h1>
                </div>
                <div class="category-description">
                    <div class="iframe_content_description" id="cmsConDescription">
                        {{--{!! $category->description_en !!}--}}
                        {!! App::isLocale('zh-CN') ? $category->description_zh : $category->description_en !!}
                    </div>
                    {{--<iframe name="cmsCon_description" id="cmsConDescription" class="cmsCon_description" frameborder="0" width="100%"></iframe>--}}
                </div>
                <div class="category-products">
                    {{-- 筛选结果展示 --}}
                    @if(count($query_param_values) > 0)
                        <div class="screening-results">
                            <span class="screening-results-title">All Results:</span>
                            <div class="screening-results-option">
                                @foreach($query_param_values as $param => $value)
                                    <a href="{{ preg_replace('/param\-' . str_replace(' ', '\_', $param) . '\=' . '.+(\&|$)/U', '', url(Request::getRequestUri())) }}">
                                        <span class="broad-heading">{{ $param }}</span>
                                        <span>:</span>
                                        <span class="subclass">{{ $value }}</span>
                                        <span class="iconfont option-close">&#xe7b6;</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- Shop By 筛选内容--}}
                    <div class="shop-by">
                        <div class="shop-by-title block-title">
                            <strong><span>Sort By:</span></strong>
                        </div>
                        @if(count($param_values) > 0)
                            <div class="shop-by-content block-content">
                                <div class="categories-lists-items subtitle-filter">
                                    @foreach($param_values as $param => $values)
                                        @if(count($values) > 0)
                                            <div class="categories-lists-item">
                                                <div class="lists-item-title">
                                                    <span class="opener">+</span>
                                                    <span title="{{ $param }}">{{ $param }}</span>
                                                </div>
                                                <ul class="categories-lists-item-ul">
                                                    @foreach($values as $value => $count)
                                                        @if(!isset($query_param_values[$param]) || $query_param_values[$param] != $value)
                                                            <li>
                                                                <a href="{{ route('seo_url', $category->slug) . '?' . http_build_query(array_merge($query_data, ['is_by_param' => 1, 'param-' . str_replace(' ', '_', $param) => $value])) }}">
                                                                    {{ $value }}<span class="count">({{ $count }})</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        {{--<a class="arrow-up-btn" href="javascript:void (0)">--}}
                            {{--<span class="iconfont">&#xe614;</span>--}}
                        {{--</a>--}}
                    </div>
                    {{-- 排序 --}}
                    {{--<div class="toolbar">--}}
                        {{--<div class="sorter">--}}
                            {{--<div class="sort-by">--}}
                                {{--<label>SORT BY:</label>--}}
                                {{--<a class="active" href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&sort=index': '') }}"><span>@lang('product.Comprehensive')</span>/</a>--}}
                                {{--<a href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&sort=heat': '') }}"><span>@lang('product.Popularity')</span>/</a>--}}
                                {{--<a href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&sort=latest': '') }}"><span>@lang('product.New product')</span>/</a>--}}
                                {{--<a href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&sort=sales': '') }}"><span>@lang('product.Sales volume')</span>/</a>--}}
                                {{--<a href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&sort=price': '') }}"><span>Price</span>/</a>--}}
                                {{--@if(isset($query_data) && $query_data['order'] == 'desc')--}}
                                    {{--降序显示这个--}}
                                    {{--<a class="iconfont" href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&order=asc' : '') }}" title="">&#xe63b;</a>--}}
                                {{--@else--}}
                                    {{--升序显示下面这个--}}
                                    {{--<a class="category-asc iconfont" href="{{ URL::current() . (isset($query_data) ? '?' . http_build_query($query_data) . '&order=desc'  : '') }}" title="">&#xe63b;</a>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div> <!-- end: sorter -->--}}
                    {{--</div>--}}
                    @if(isset($products))
                        <ul class="products-grid category-products-grid">
                            @foreach($products as $product)
                                <li class="item">
                                    <div class="product-image-wrapper">
                                        <div class="products-item">
                                            {{-- 商品配图 --}}
                                            <div class="products-img">
                                                <a href="{{ route('seo_url', ['slug' => $product->slug]) }}" title="{{ $product->name_en }}" class="product-image">
                                                    <img src="{{ $product->thumb_url }}" alt="{{ $product->name_en }}">
                                                </a>
                                            </div>
                                            <div class="products-info visible-lg">
                                                {{-- 快速预览跳转到商品详情页面 --}}
                                                <button type="button" class="button btn-cart quick-view">
                                                    <a href="{{ route('seo_url', ['slug' => $product->slug]) }}">QUICK VIEW</a>
                                                </button>
                                                {{-- 添加收藏 --}}
                                                {{-- 需判断商品是否已经添加收藏列表如果没有显示 --}}
                                                @guest
                                                    <a class="wishlist-icon for-login-show" data-product=""><img alt="" src="{{ asset('img/lordImg/w-icon.png') }}">WISHLIST</a>
                                                @else
                                                    @if($user->isProductFavourite($product->id))
                                                        {{--如果已经添加收藏显示--}}
                                                    {{-- 添加商品的路径也没显示 --}}
                                                        <a class="wishlist-icon inwish" data-product="{{ $product->id }}"
                                                           data-favourite-code="{{ $user->getFavouriteByProduct($product->id)->id }}"
                                                           data-url="{{ route('user_favourites.destroy') }}">
                                                            <img alt="" src="{{ asset('img/lordImg/w-icon-hover.png') }}">WISHLIST
                                                        </a>
                                                    @else
                                                        <a class="wishlist-icon" data-product="{{ $product->id }}"
                                                           data-favourite-code=""
                                                           data-url="{{ route('user_favourites.store') }}">
                                                            <img alt="" src="{{ asset('img/lordImg/w-icon.png') }}">WISHLIST
                                                        </a>
                                                    @endif
                                                @endif
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 商品标题 --}}
                                    <h2 class="product-name">
                                        <a href="{{ route('seo_url', ['slug' => $product->slug]) }}" title="{{ $product->name_en }}">{{ $product->name_en }}</a>
                                    </h2>
                                    {{--商品标号一类--}}
                                    <h5 class="product-name" title="{{ $product->sub_name_en }}">{{ $product->sub_name_en }}</h5>
                                    <div class="dis_ni">
                                        <div class="ratings">
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
                                            {{-- 评价的数量 --}}
                                            <span class="amount">{{ $product->comment_count }} Review(s)</span>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        {{--原始价格--}}
                                        <p class="old-price">
                                            <span class="price">{{ get_global_symbol() }} {{ bcmul(get_current_price($product->price), 1.2, 2) }}</span>
                                        </p>
                                        {{--当前价格--}}
                                        <p class="special-price">
                                            <span class="price-label">Special Price</span>
                                            <span class="price">{{ get_global_symbol() }} {{ get_current_price($product->price) }}</span>
                                        </p>
                                    </div>
                                    <div class="actions clearer " style="padding-left: 20%; bottom: 25px;"></div>
                                </li>
                            @endforeach
                        </ul>
                        {{--end: Quick View--}}
                        <div class="toolbar-bottom">
                            <div class="toolbar">
                                <div class="pager">
                                    <div class="pages">
                                        {{ isset($query_data) ? $products->appends($query_data)->links() : $products->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="bottom-article">
                    <div class="iframe_content dis_ni">
                        {!! App::isLocale('zh-CN') ? $category->content_zh : $category->content_en !!}
                    </div>
                    <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        {{-- 左侧shopBy点击展开 --}}
        $(".subtitle-filter").on("click",".lists-item-title",function () {
            var activeDom = $(this).parents(".categories-lists-item"),
                isActive = $(activeDom).hasClass("item-active"),
                $allSubtitle = $(".subtitle-filter");
            if(isActive){
                $(activeDom).find(".categories-lists-item-ul").slideUp();
                $(activeDom).removeClass("item-active");
                $(this).parents(".lists-item-title").find(".opener").text("+");
            }else {
                $allSubtitle.find(".categories-lists-item").removeClass("item-active");
                $allSubtitle.find(".categories-lists-item-ul").slideUp();
                $allSubtitle.find(".opener").text("+");
                $(activeDom).find(".categories-lists-item-ul").slideDown();
                $(activeDom).addClass("item-active");
                $(this).parents(".lists-item-title").find(".opener").text("-");
            }
        });
        // 点击空白处关闭弹窗
        $(document).mouseup(function(e) {
            var  pop = $('.shop-by-content');
            if(!pop.is(e.target) && pop.has(e.target).length === 0) {
                // 可以在这里关闭弹窗
                pop.find(".categories-lists-item-ul").slideUp();
                pop.find(".categories-lists-item").removeClass("item-active");
                pop.find(".opener").text("+");
            }
        });
        // wishlist-icon的触摸事件
        $(".wishlist-icon").hover(function(){
            if(!($(this).hasClass('inwish'))){
                $(this).children("img").attr("src","{{ asset('img/lordImg/w-icon-hover.png') }}");
            }
        }, function(){
            if(!($(this).hasClass('inwish'))){
                $(this).children("img").attr("src","{{ asset('img/lordImg/w-icon.png') }}");
            }
        });
        // 点击wishlist按钮
        $(".wishlist-icon").click(function(){
            var clickDom = $(this);
            if($(this).hasClass('inwish')){
                // 移除收藏
                clickDom.removeClass("inwish").children("img").attr("src","{{ asset('img/lordImg/w-icon.png') }}");
                if(clickDom.hasClass("for-login-show")) {
                    return
                }
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                    favourite_id: clickDom.attr("data-favourite-code")
                };
                $.ajax({
                    type: "post",
                    url: clickDom.attr('data-url'),
                    data: data,
                    success: function (data) {
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
                // 添加收藏
                clickDom.addClass("inwish").children("img").attr("src","{{ asset('img/lordImg/w-icon-hover.png') }}");
                if(clickDom.hasClass("for-login-show")) {
                    return
                }
                var data = {
                    _token: "{{ csrf_token() }}",
                    product_id: clickDom.attr("data-product"),
                };
                $.ajax({
                    type: "post",
                    url: clickDom.attr('data-url'),
                    data: data,
                    success: function (data) {
                        $(".wishlist-icon").attr("data-favourite-code",data.data.favourite.id);
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
    //    底部文章内容
        var iframe_content = $('.iframe_content').html();
        $('.iframe_content').html("");
        $('#cmsCon').contents().find('body').html(iframe_content);
        $('#cmsCon').contents().find('body').css({"color":"#333","margin":0,"line-height":"130%","box-sizing":"border-box"});
        $('#cmsCon').contents().find('body').find("span").css("fontSize","14px");
        $('#cmsCon').contents().find('body').find("p").css("margin","0 0 8.4px");
        reinitIframe("cmsCon");
    //    上方描述文章页
        function reinitIframe(domID){
            var iframe_Description = document.getElementById(domID);
            iframe_Description.height = 0; //只有先设置原来的iframe高度为0，之前的iframe高度才不会对现在的设置有影响
            var bHeight = iframe_Description.contentWindow.document.body.scrollHeight;
            var dHeight = iframe_Description.contentWindow.document.documentElement.scrollHeight;
            var height = Math.max(bHeight, dHeight);
            iframe_Description.height = bHeight;
        }
        // var iframe_content_description = $('.iframe_content_description').html();
        // $('.iframe_content_description').html("");
        // $('#cmsConDescription').contents().find('body').html(iframe_content_description);
        $('#cmsConDescription').css({"color":"#333","margin":0,"line-height":"130%","box-sizing":"border-box"});
        $('#cmsConDescription').find("span").css("fontSize","14px");
        $('#cmsConDescription').find("p").css("margin","0 0 8.4px");
        // reinitIframe("cmsConDescription");
        // var autoSet_description = window.setInterval('reinitIframe()',500);

    //    点击shopBy的箭头按钮
    //     $(".arrow-up-btn").on("click",function () {
    //         var clickDom = $(this);
    //         if(clickDom.hasClass("active")){
    //             clickDom.removeClass("active");
    //             $(".shop-by").removeClass("active");
    //         }else {
    //             clickDom.addClass("active");
    //             $(".shop-by").addClass("active");
    //         }
    //     })
    </script>
@endsection
