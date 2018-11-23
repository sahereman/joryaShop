@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Search results' : '搜索结果')
@section('content')
    <div class="products-search-level">
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('product.All results')</a>
                    <span>></span>
                    <a href="#">@lang('product.Categories')</a>
                </p>
            </div>
            <div class="search-level">
                <ul>
                    <li class="dis_n more_load" data-url="{{ route('products.search_more') }}"></li>
                    <li class="active default">
                        <a code='index'>@lang('product.Comprehensive')</a>
                    </li>
                    <li>
                        <a code='heat'>@lang('product.Popularity')</a>
                    </li>
                    <li>
                        <a code='latest'>@lang('product.New product')</a>
                    </li>
                    <li>
                        <a code='sales'>@lang('product.Sales volume')</a>
                    </li>
                    <li class="icon">
                        <a code='0'>
                            <span>@lang('product.price')</span>
                            <div>
                                <i code='price_asc' class="w-icon-arrow arrow-up"></i>
                                <i code='price_desc' class="w-icon-arrow arrow-down"></i>
                            </div>
                        </a>
                    </li>
                </ul>
                <div>
                    <input type="text" class="min_price" placeholder="{{ App::isLocale('en') ? '&#36;' : '&yen;' }}"/>
                    <span></span>
                    <input type="text" class="max_price" placeholder="{{ App::isLocale('en') ? '&#36;' : '&yen;' }}"/>
                    <button class="searchByPrice">@lang('app.determine')</button>
                </div>
            </div>
            <!--商品分类展示-->
            <div class="classified-display">
                <div class="classified-products">
                    <ul class="classified-lists"></ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            var loading_animation;  //loading动画的全局name
            var sort = "index";   //排序传参用的参数默认为综合排序
            var dataoption_1;  //页面加载时用来请求ajax的data
            var dataoption_2;  //通过价格区间方式获取数据ajax
            var dataoption_3;  //滚动条使用
            var loading = false;    //阻止同时进行多次ajax异步请求
            var requestType = 0;   //用来判断滚动条加载数据时应该传递那种参数 0：页面加载时的默认排序，点击人气综合等排序 。1：根据价格区间来获取排序
            var page_num = 2;    //请求页面
            window.onload = function () {
                dataoption_1 = {
                    query: getQueryString("query"),
                    sort: sort,
                    page: 1,
                };
                getResults(dataoption_1, true);
            };
            //获取商品列表
            function getResults(data, type) {
                $.ajax({
                    type: "get",
                    url: $(".more_load").attr("data-url"),
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
                        var dataobj = json.data.products.data;
                        var html = "";
                        var country = $("#dLabel").find("span").html();
                        var name, symbol, price;
                        if (dataobj.length > 0) {
                            $.each(dataobj, function (i, n) {
                                name = (country == "中文") ? n.name_zh : n.name_en;
                                symbol = (country == "中文") ? "&yen;" : "&#36;";
                                price = (country == "中文") ? n.price : n.price_in_usd;
                                html += "<li>" +
                                        "<a href='/products/" + n.id + "'>" +
                                        "<div class='list-img'>" +
                                        "<img src='" + n.thumb_url + "'>" +
                                        "</div>" +
                                        "<div class='list-info'>" +
                                        "<p class='list-info-title' title='" + name + "'>" + name + "</p>" +
                                        "<p>" +
                                        "<span class='new-price'><i>" + symbol + "</i>" + price + "</span>" +
                                        "<span class='old-price'><i>" + symbol + "</i>" + (Math.ceil(price * 120) / 100) + "</span>" +
                                        "</p>" +
                                        "</div>" +
                                        "</a>" +
                                        "</li>";
                            });
                            loading = false;
                        } else {
                            if (json.data.products.current_page == 1) {
                                html = "<li class='empty_tips'>" +
                                        "<p>" +
                                        "<img src='{{ asset('img/warning.png') }}'>" +
                                        "@lang('product.not found')" +
                                        "“<span class='red'>" + getQueryString("query") + "</span>”@lang('product.related products')" +
                                        "</p>" +
                                        "</li>";
                            } else {
                                html = "<li class='ending_empty_tips'>" +
                                        "<p>@lang('product.All content has been loaded')</p>" +
                                        "</li>";
                            }
                            loading = true; // 当返回数组内容为空时阻止滚动条滚动
                        }
                        $(".classified-lists").append(html);
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

            $(window).scroll(function () {
                //通过判断滚动条的top位置与可视网页之和与整个网页的高度是否相等来决定是否加载内容；
                if ((($(window).scrollTop() + $(window).height()) + 300) >= $(document).height()) {
                    if (loading == false) {
                        loading = true;
                        if (requestType == 0) {
                            dataoption_3 = {
                                query: getQueryString("query"),
                                page: page_num,
                                sort: sort,
                            }
                        } else {
                            dataoption_3 = {
                                query: getQueryString("query"),
                                page: page_num,
                                min_price: $(".min_price").val(),
                                max_price: $(".max_price").val(),
                            }
                        }
                        getResults(dataoption_3, false);
                        page_num++;
                    }
                }
            });
            //点击商品分类获取不同的信息
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
                    query: getQueryString("query"),
                    page: 1,
                    sort: sort,
                };
                $(".classified-lists").html("");
                getResults(dataoption_1, true);
            });
            //根据价格区间来获取排序
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
                            query: getQueryString("query"),
                            page: 1,
                            min_price: $(".min_price").val(),
                            max_price: $(".max_price").val(),
                        };
                        $(".classified-lists").html("");
                        getResults(dataoption_2, true);
                    } else {
                        layer.msg("@lang('product.Please enter the correct price range')")
                    }
                }
            });
            function getUrlVars() {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars["query"];
            }

            $(document).ready(function () {
                var text = decodeURIComponent(getUrlVars());
                $(".selectInput_header").val(text);
            });
        });
    </script>
@endsection
