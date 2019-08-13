@extends('layouts.app')
@section('keywords', $category->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $category->seo_description ? : \App\Models\Config::config('description'))
@section('title', $category->seo_title ? : (App::isLocale('zh-CN') ? $category->name_zh : $category->name_en) . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="productCate my_orders">
        <div class="container main productCate-content">
            <div class="col-left">
                <div class="block block-layered-nav">
                    <div class="block-title">
                        <strong><span>Categories</span></strong>
                    </div>
                    <div class="block-content">
                        <div class="categories-lists-items categories-menu">
                            <div class="categories-lists-item">
                                <div class="lists-item-title"><a href="#"><span>Hair Systems</span></a></div>
                            </div>
                            <div class="categories-lists-item">
                                <div class="lists-item-title"><a href="#"><span>Mens Hair Systems</span></a></div>
                                <ul class="categories-lists-item-ul">
                                    <li>
                                        <a href="#"><span>Stock Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Custom Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Lace Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Lace Front Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Skin Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Monofilament Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Men's Hair Toupees</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="categories-lists-item">
                                <div class="lists-item-title"><a href="#"><span>Womens Hair Systems</span></a></div>
                                <ul class="categories-lists-item-ul">
                                    <li>
                                        <a href="#"><span>Womens Hair Systems</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Full Cap Wigs</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Hair Integration</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Medical Wigs</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Stock Wigs for Women</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="categories-lists-item">
                                <div class="lists-item-title"><a href="#"><span>Accessories</span></a></div>
                                <ul class="categories-lists-item-ul">
                                    <li>
                                        <a href="#"><span>Ordering tools</span></a>
                                    </li>
                                    <li>
                                        <a href="#"><span>Maintenance &amp; accessories</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="categories-lists-item">
                                <div class="lists-item-title"><a href="#"><span>Salon Collaboration</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block block-layered-nav">
                    <div class="block-title">
                        <strong><span>Shop By</span></strong>
                    </div>
                    <div class="block-content">
                        <div class="categories-lists-items subtitle-filter">
                            @foreach(\App\Models\ProductParam::paramNameValues() as $name => $values)
                                <div class="categories-lists-item">
                                    <div class="lists-item-title">
                                        <span>{{ $name }}</span>
                                        <span class="opener">+</span>
                                    </div>
                                    <ul class="categories-lists-item-ul">
                                        @foreach($values as $value => $count)
                                            <li>
                                                <a href="{{ route('products.search') . '?is_by_param=1&param=' . $name . '&value=' . $value }}">
                                                    {{ $value }}<span class="count">({{ $count }})</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="Crumbs-box">
                    {{-- 面包屑 --}}
                    <p class="Crumbs">
                        <a href="{{ route('root') }}">@lang('basic.home')</a>
                        <span>/</span>
                        <a  class="dynamic-path" href="javascript:void(0);"></a>
                    </p>
                    <div class="page-title category-title">
                        {{-- 引号内添加用户搜索的关键词 --}}
                    </div>
                    <div class="category-products">
                        <div class="toolbar">
                            <div class="sorter">
                                <div class="sort-by">
                                    <label>SORT BY:</label>
                                    <a class="active" href="#"><span>@lang('product.Comprehensive')</span>/</a>
                                    <a href="#"><span>@lang('product.Popularity')</span>/</a>
                                    <a href="#"><span>@lang('product.New product')</span>/</a>
                                    <a href="#"><span>@lang('product.Sales volume')</span>/</a>
                                    <a href="#"><span>Price</span>/</a>
                                    @if(true)
                                        {{--降序显示这个--}}
                                        <a class="iconfont" href="#" title="">&#xe63b;</a>
                                    @else
                                        {{--升序显示下面这个--}}
                                        <a class="category-asc iconfont" href="#" title="">&#xe63b;</a>
                                    @endif
                                </div>
                            </div> <!-- end: sorter -->
                        </div>
                        <input type="hidden" class="more_load" value="{{ route('products.search_more') }}">
                        <ul class="products-grid category-products-grid"></ul>
                        {{--end: Quick View--}}
                        <div class="toolbar-bottom">
                            <div class="toolbar">
                                <div class="pager">
                                    <div class="pages">
                                        <strong>Page:</strong>
                                        <ol>
                                            {{-- 当前页不是第一页的时候显示 路径为当前页的前一页 --}}
                                            <li class="previous">
                                                <a class="next iconfont" href="https://www.lordhair.com/mens-hair-systems.html?p=2" title="Previous">&#xe603;</a>
                                            </li>
                                            {{-- 默认显示五个页码多余的不显示 --}}
                                            <li class="current">1</li>
                                            <li><a href="#">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li><a href="#">4</a></li>
                                            <li><a href="#">5</a></li>
                                            {{--当前页是最后一页时不显示 路径为当前页的前一页--}}
                                            <li class="next">
                                                <a class="next iconfont" href="https://www.lordhair.com/mens-hair-systems.html?p=2" title="Next">&#xe63a;</a>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            var loading_animation; // loading动画的全局name
            var sort = "index"; // 排序传参用的参数默认为综合排序
            var dataoption_1; // 页面加载时用来请求ajax的data
            var dataoption_2; // 通过价格区间方式获取数据ajax
            var dataoption_3; // 滚动条使用
            var loading = false; // 阻止同时进行多次ajax异步请求
            var requestType = 0; // 用来判断滚动条加载数据时应该传递那种参数 0：页面加载时的默认排序，点击人气综合等排序 。1：根据价格区间来获取排序
            var page_num = 2; // 请求页面
            window.onload = function () {
                dataoption_1 = {
                    // query: getQueryString("query"),
                    sort: sort,
                    page: 1,
                };
                var url = $(".more_load").val();
                getResults(dataoption_1, true, url);
            };
            //获取商品列表
            function getResults(data, type, url) {
                $.ajax({
                    type: "get",
                    url: url,
                    data: data,
                    async: type,
                    beforeSend: function () {
                        loading_animation = layer.msg("@lang('app.Please wait')", {
                            icon: 16,
                            shade: 0.4,
                            time: false, //取消自动关闭
                        });
                    },
                    success: function (json) {
                        console.log(json);
                        var dataobj = json.data.products.data;
                        var html = "";
                        var country = $("#dLabel").find("span").html();
                        var name, symbol, price;
                        if (dataobj.length > 0) {
                            $.each(dataobj, function (i, n) {
                                name = (country == "中文") ? n.name_zh : n.name_en;
                                // symbol = (country == "中文") ? "&#165;" : "&#36;";
                                // price = (country == "中文") ? n.price : n.price_in_usd;
                                symbol = global_symbol;
                                price = get_current_price(n.price);
                                html +="<li class='item'>"
                                html +="<div class='product-image-wrapper'>"
                                html +="<div class='products-item'>"
                                html +="<div class='products-img'>"
                                html +="<a href='/products/" + n.id + "' title='' class='product-image'>"
                                html +="<img src='"+ n.thumb_url  +"' alt=''>"
                                html +="</a>"
                                html +="</div>"
                                html +="<div class='products-info visible-lg'>"
                                html +="<button type='button' class='button btn-cart quick-view'>"
                                html +="<a href='/products/" + n.id + "'>QUICK VIEW</a>"
                                html +="</button>"
                                {{-- 需判断商品是否已经添加收藏列表如果没有显示 --}}
                                if(1===1) {
                                    html +="<a class='wishlist-icon' data-product=''><img alt='' src='{{ asset('img/lordImg/w-icon.png') }}'>WISHLIST</a>"
                                }else {
                                    html +="<a class='wishlist-icon inwish' data-product=''><img alt='' src='{{ asset('img/lordImg/w-icon-hover.png') }}'>WISHLIST</a>"
                                }
                                html +="<div class='clear'></div>"
                                html +="</div>"
                                html +="</div>"
                                html +="</div>"
                                html +="<h2 class='product-name'>"
                                html +="<a href='/products/" + n.id + "'>"+ name +"</a>"
                                html +="</h2>"
                                html +="<h5 class='product-name'>Product Code: S22 Stock (UTS)</h5>"
                                html +="<div class=''>"
                                html +="<div class='ratings'>"
                                html +="<div class='rating-box'>"
                                {{-- 商品星级评价，按照之前的设定分为：
                                         1星：width:20%
                                         2星：width:40%
                                         3星：width:60%
                                         4星：width:80%
                                         5星：width:100%--}}
                                    html +="<div class='rating' style='width:98%'></div>"
                                html +="</div>"
                                html +="<span class='amount'>50 Review(s)</span>"
                                html +="</div>"
                                html +="</div>"
                                html +="<div class='price-box'>"
                                html +="<p class='old-price'>"
                                html +="<span class='price'><i>" + symbol + "</i>" + js_number_format(Math.imul(float_multiply_by_100(price), 12) / 1000) + "</span>"
                                html +="</p>"
                                html +="<p class='special-price'>"
                                html +="<span class='price-label'>Special Price</span>"
                                html +="<span class='price'><i>" + symbol + "</i>" + price + "</span>"
                                html +="</p>"
                                html +="</div>"
                                html +="<div class='actions clearer' style='padding-left: 20%; bottom: 25px;'></div>"
                                html +="</li>"
                            });
                            loading = false;
                        } else {
                            if (json.data.products.current_page == 1) {
                                html = "<li class='empty_tips'>" +
                                        "<p>" +
                                        "<img src='{{ asset('img/warning.png') }}'>" +
                                        "@lang('product.not found')" +
                                        "@lang('product.related products')" +
                                        "</p>" +
                                        "</li>";
                            } else {
                                {{--html = "<li class='ending_empty_tips'>" +--}}
                                        {{--"<p>@lang('product.All content has been loaded')</p>" +--}}
                                        {{--"</li>";--}}
                            }
                            loading = true; // 当返回数组内容为空时阻止滚动条滚动
                        }
                        $(".products-grid").append(html)
                        // $(".classified-lists").append(html);
                    },
                    error: function (e) {
                        console.log(e);
                    },
                    complete: function () {
                        layer.close(loading_animation);
                    }
                });
            }

            /*获取url参数*/
            function getQueryString(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if (r != null)
                    return decodeURI(r[2]);
                return null;
            }

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
                // number = number.toString();
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

            // $(window).scroll(function () {
            //     // 通过判断滚动条的top位置与可视网页之和与整个网页的高度是否相等来决定是否加载内容；
            //     if ((($(window).scrollTop() + $(window).height()) + 300) >= $(document).height()) {
            //         if (loading == false) {
            //             loading = true;
            //             if (requestType == 0) {
            //                 dataoption_3 = {
            //                     // query: getQueryString("query"),
            //                     page: page_num,
            //                     sort: sort,
            //                 }
            //             } else {
            //                 dataoption_3 = {
            //                     // query: getQueryString("query"),
            //                     page: page_num,
            //                     min_price: $(".min_price").val(),
            //                     max_price: $(".max_price").val(),
            //                 }
            //             }
            //             var url = $(".more_load").val();
            //             getResults(dataoption_3, false, url);
            //             page_num++;
            //         }
            //     }
            // });
            // 点击商品分类获取不同的信息
            $(".search-level ul").on('click', 'li', function () {
                requestType = 0;
                page_num = 2;
                $(".search-level ul").find('li').removeClass("active");
                $(this).addClass("active");
                if ($(this).hasClass("icon")) {
                    if ($(this).attr('code') == '0') {
                        sort = 'price_asc';
                        $(this).attr('code', '1');
                        $(this).find('.arrow-up').css('opacity', '1');
                        $(this).find('.arrow-down').css('opacity', '0');
                    } else {
                        sort = 'price_desc';
                        $(this).attr('code', '0');
                        $(this).find('.arrow-up').css('opacity', '0');
                        $(this).find('.arrow-down').css('opacity', '1');
                    }
                } else {
                    $(this).parents('ul').find('.arrow-up').css('opacity', '1');
                    $(this).parents('ul').find('.arrow-down').css('opacity', '1');
                    sort = $(this).find('a').attr('code');
                }
                dataoption_1 = {
                    // query: getQueryString("query"),
                    page: 1,
                    sort: sort,
                };
                $(".classified-lists").html("");
                var url = $(".more_load").val();
                getResults(dataoption_1, true, url);
            });
            // 根据价格区间来获取排序
            $(".searchByPrice").on("click", function () {
                requestType = 1;
                page_num = 2;
                $(".search-level ul").find('li').removeClass("active");
                $(".search-level ul").find('.default').addClass("active");
                if (parseInt($(".min_price").val()) >= parseInt($(".max_price").val())) {
                    layer.msg("@lang('product.Please enter the correct price range')");
                } else {
                    if ($(".min_price").val() != "" && $(".max_price").val() != "") {
                        dataoption_2 = {
                            // query: getQueryString("query"),
                            page: 1,
                            min_price: $(".min_price").val(),
                            max_price: $(".max_price").val(),
                        };
                        $(".classified-lists").html("");
                        var url = $(".more_load").val();
                        getResults(dataoption_2, true, url);
                    } else {
                        layer.msg("@lang('product.Please enter the correct price range')");
                    }
                }
            });
        });
    </script>
@endsection
