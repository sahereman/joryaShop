@extends('layouts.app')
@section('keywords', $product->seo_keywords ? : \App\Models\Config::config('keywords'))
@section('description', $product->seo_description ? : \App\Models\Config::config('description'))
@section('title', $product->seo_title ? : (App::isLocale('zh-CN') ? $product->name_zh : $product->name_en) . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="commodity-details">
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    @if($category->parent)
                        <span>></span>
                        <a href="{{ route('product_categories.index', ['category' => $category->parent->id]) }}">{{ App::isLocale('zh-CN') ? $category->parent->name_zh : $category->parent->name_en }}</a>
                    @endif
                    <span>></span>
                    <a href="{{ route('product_categories.index', ['category' => $category->id]) }}">{{ App::isLocale('zh-CN') ? $category->name_zh : $category->name_en }}</a>
                    <span>></span>
                    <a href="javascript:void(0);">{{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}</a>
                </p>
            </div>
            <!--详情上半部分-->
            <div class="commodity_parameters">
                <!--商品放大镜效果-->
                <div class="magnifierContainer">
                    @if($product->photo_urls)
                        <div class="imgLeft">
                            <!-- 中号图片 -->
                            <div class="imgMedium" id="imgMedium">
                                <!-- 放大镜 -->
                                <div class="magnifier" id="magnifier">
                                    <img src="{{ asset('img/zoom_pup.png') }}">
                                </div>
                                <!-- 图片 -->
                                <div class="mediumContainer" id="mediumContainer">
                                    <img class="lazy" data-src="{{ $product->photo_urls[0] }}">
                                </div>
                                <div id="zhezhao"></div>
                            </div>
                            <!-- 缩略图 -->
                            <div class="spec-scroll">
                                <a class="prev">&lt;</a>
                                <a class="next">&gt;</a>
                                <div class="img_items">
                                    <ul class="img_x" id="img_x">
                                        @foreach($product->photo_urls as $photo_url)
                                            <li code="{{ $photo_url }}">
                                                <img code="{{ $photo_url }}" src="{{ $photo_url }}">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="imgRight">
                            <!-- 大图 -->
                            <div class="img_u" id="img_u">
                                <img src="{{ $product->photo_urls[0] }}">
                            </div>
                        </div>
                    @endif
                </div>
                <!--商品参数-->
                <div class="parameters_content">
                    <h4 class="forstorage_name" info_url="{{ $product->thumb_url }}" info_code="{{ $product->id }}"
                        info_href="{{ route('products.show', ['product' => $product->id]) }}">
                        {{ App::isLocale('zh-CN') ? $product->name_zh : $product->name_en }}
                    </h4>
                    <p class="small_title">{!! App::isLocale('zh-CN') ? $product->description_zh : $product->description_en !!}</p>
                    <div class="price_service">
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
                    {{--<div class="priceOfpro">
                        <span>@lang('product.product_details.freight')</span>
                        <span><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->shipping_fee_in_usd : $product->shipping_fee }}</span>
                        <span><i>{{ get_global_symbol() }} </i>{{ get_current_price($product->shipping_fee) }}</span>
                    </div>--}}

                    @if(count($parameters) > 0)
                        @foreach($parameters as $key => $specifications)
                            <div class="priceOfpro forgetSel"
                                 data-url="{{ route('products.get_sku_parameters', ['product' => $product->id]) }}">
                                <span class="dynamic_name">{{ $key }}</span>
                                <select name="{{ $key }}">
                                    @if(count($specifications) > 0)
                                        @foreach($specifications as $index => $specification)
                                            <option value="{{ $specification }}">{{$specification}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endforeach
                    @endif
                    @if(count($skus) > 0)
                        <div class="forSkusHidde dis_n">
                            @foreach($skus as $specifications)
                                <input type="text" value="{{ $specifications }}"/>
                            @endforeach
                        </div>
                    @endif
                    <div class="priceOfpro">
                        <span class="buy_numbers">@lang('product.product_details.Quantity')</span>
                        <div class="quantity_control">
                            <span class="reduce no_allow"><i>-</i></span>
                            <input name="number" id="pro_num" type="number" value="1" min="1" max="99">
                            <span class="add"><i>+</i></span>
                        </div>
                        <div class="availableSold">
                            <span class="defalutavailableSold" data-stock='{{ $skus->first()->stock }}'
                                  data-sales='{{ $skus->first()->sales }}'>
                                {{ $skus->first()->stock }} Available / <i>{{ $skus->first()->sales }} Sold</i>
                            </span>
                        </div>
                    </div>
                    <!--添加购物车与立即购买-->
                    <div class="addCart_buyNow">
                        @guest
                        <a class="buy_now for_show_login">
                            @lang('product.product_details.Buy now')
                        </a>
                        <a class="add_carts for_show_login">
                            @lang('app.Add to Shopping Cart')
                        </a>
                        @else
                            <a class="buy_now" data-url="{{ route('orders.pre_payment') }}">
                                @lang('product.product_details.Buy now')
                            </a>
                            <a class="add_carts" data-url="{{ route('carts.store') }}">
                                @lang('app.Add to Shopping Cart')
                            </a>
                            @endguest
                            <a class="add_favourites {{ $favourite ? 'active' : '' }}" code="{{ $product->id }}"
                               data-url="{{ route('user_favourites.store') }}"
                               data-url_2="{{ $favourite ? route('user_favourites.destroy', ['favourite' => $favourite->id]) : '' }}">
                                <span class="favourites_img"></span>
                                <span>@lang('product.product_details.Collection')</span>
                            </a>
                    </div>
                </div>
                <!--猜你喜欢-->
                <div class="guess_like">
                    <p>
                        <span class="line"></span>
                        <span>&bull;</span>
                        <span>@lang('app.you may also like')</span>
                        <span>&bull;</span>
                        <span class="line"></span>
                    </p>
                    <ul>
                        @foreach($guesses as $guess)
                            <li>
                                <a href="{{ route('products.show', ['product' => $guess->id]) }}">
                                    <div>
                                        <img class="lazy" data-src="{{ $guess->thumb_url }}">
                                    </div>
                                    <p>
                                        {{--<span class="present_price"><i>@lang('basic.currency.symbol')</i>{{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>--}}
                                        <span class="present_price"><i>{{ get_global_symbol() }} </i>{{ get_current_price($guess->price) }}</span>
                                        {{--<span class="original_price"><i>@lang('basic.currency.symbol')</i>{{ App::isLocale('en') ? bcmul($guess->price_in_usd, 1.2, 2) : bcmul($guess->price, 1.2, 2) }}</span>--}}
                                        <span class="original_price"><i>{{ get_global_symbol() }} </i>{{ bcmul(get_current_price($guess->price), 1.2, 2) }}</span>
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!--详情下半部分-->
            <div class="comments_details">
                <div class="comments_details_left pull-left" id="list">
                    <ul class="tab">
                        <li onclick="tabs('#list',0)" class="curr">Browsing History</li>
                        <!--<li onclick="tabs('#list',1)">@lang('product.product_details.Popular sales')</li>-->
                    </ul>
                    <div class="mc tabcon">
                        <ul class="pro-lists">
                            {{--@foreach($hot_sales as $hot_sale)
                                <li>
                                    <a href="{{ route('products.show', ['product' => $hot_sale->id]) }}">
                                        <div>
                                            <img class="lazy" data-src="{{ $hot_sale->thumb_url }}">
                                        </div>
                                        <p>
                                            <span class="present_price"><i>{{ get_global_symbol() }} </i>{{ get_current_price($hot_sale->price) }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endforeach--}}
                        </ul>
                    </div>
                    <div class="mc tabcon dis_n">
                        <ul class="pro-lists">
                            @foreach($best_sellers as $best_seller)
                                <li>
                                    <a href="{{ route('products.show', ['product' => $best_seller->id]) }}">
                                        <div>
                                            <img class="lazy" data-src="{{ $best_seller->thumb_url }}">
                                        </div>
                                        <p>
                                            {{--<span class="present_price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $best_seller->price_in_usd : $best_seller->price }}</span>--}}
                                            <span class="present_price"><i>{{ get_global_symbol() }} </i>{{ get_current_price($best_seller->price) }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="comments_details_right pull-left" id="comments_details">
                    <ul class="tab">
                        <li onclick="tabs('#comments_details',0)"
                            class="curr">@lang('product.product_details.Commodity details')</li>
                        <li onclick="tabs('#comments_details',1)" class="shopping_eva"
                            data-url="{{ route('products.comment', ['product' => $product->id]) }}">@lang('product.product_details.Commodity feedback')
                            <strong>({{ $comment_count }})</strong></li>
                    </ul>
                    <div class="mc tabcon product_info">
                        <div class="iframe_content dis_ni">
                            {!! App::isLocale('zh-CN') ? $product->content_zh : $product->content_en !!}
                        </div>
                        <iframe name="cmsCon" id="cmsCon" class="cmsCon" frameborder="0" width="100%" scrolling="no" height="auto"></iframe>
                    </div>
                    <div class="mc tabcon dis_n">
                        <ul class="comment-score">
                            <li>
                                <span>@lang('product.product_details.Overall rating')</span>
                                <h3 class="composite_index">4</h3>
                            </li>
                            <li>
                                <span>@lang('product.product_details.Description match')</span>
                                <h3 class="description_index">4</h3>
                            </li>
                            <li>
                                <span>@lang('product.product_details.Logistics Services')</span>
                                <h3 class="shipment_index">4</h3>
                            </li>
                        </ul>
                        <div class="comment-items">
                            <div class="items-title">
                                <a class="active">@lang('product.product_details.Commodity feedback')
                                    <strong>({{ $comment_count }})</strong></a>
                                <!--<a>图片评价</a>-->
                            </div>
                            <!--暂无评价-->
                            <div class="no_eva dis_n">
                                <p>@lang('product.product_details.No evaluation information yet')</p>
                            </div>
                        </div>
                        <!--分页-->
                        <div class="paging_box">
                            <a class="pre_page" href="javascript:void(0);">@lang('app.Previous page')</a>
                            <a class="next_page" href="javascript:void(0);">@lang('app.Next page')</a>
                        </div>
                    </div>
                    <!--浏览足迹-->
                    <!--<div class="browseFootprints">
                        <div class="browseFootprints_title">
                            <p>Browsing history</p>
                        </div>
                        <div class="browseFootprints_content">
                            <ul></ul>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var loading_animation;  // loading动画的全局name
        var current_page;  // 评价的当前页
        var next_page;   // 下一页的页码
        var pre_page;   // 上一页的页码
        var country = $("#dLabel").find("span").html();
        var sku_id, sku_stock, sku_price, sku_original_price;

        $('#img_x li').eq(0).css('border', '2px solid #bc8c61');
        $('#img_x li').eq(0).addClass("active");
        $('#zhezhao').mousemove(function (e) {
            $('#img_u').show();
            $('#magnifier').show();
            var left = e.offsetX - parseInt($('#magnifier').width()) / 2;
            var top = e.offsetY - parseInt($('#magnifier').height()) / 2;
            left = left < 0 ? 0 : left;
            left = left > (parseInt($('#zhezhao').outerWidth()) - parseInt($('#magnifier').outerWidth())) ? (parseInt($('#zhezhao').outerWidth()) - parseInt($('#magnifier').outerWidth())) : left;
            top = top < 0 ? 0 : top;
            top = top > (parseInt($('#zhezhao').outerHeight()) - parseInt($('#magnifier').outerHeight())) ? (parseInt($('#zhezhao').outerHeight()) - parseInt($('#magnifier').outerHeight())) : top;

            $('#magnifier').css('left', left + 'px');
            $('#magnifier').css('top', top + 'px');

            var leftRate = left / parseInt($('#zhezhao').outerWidth());
            var bigLeft = leftRate * parseInt($('#img_u img').outerWidth()) + 20;
            $('#img_u img').css('margin-left', -bigLeft + 'px');

            var topRate = top / parseInt($('#zhezhao').outerHeight());
            var bigTop = topRate * parseInt($('#img_u img').outerHeight()) + 20;
            $('#img_u img').css('margin-top', -bigTop + 'px');
        });
        $('#zhezhao').mouseleave(function () {
            $('#img_u').hide();
            $('#magnifier').hide();
        });
        $('#img_x li').mouseover(function () {
            $("#img_x li").removeClass("active");
            $(this).addClass("active");
            $(this).css('border', '2px solid #bc8c61').siblings().css('border', '2px solid transparent');
            $('#mediumContainer img').eq(0).attr('src', $(this).attr('code'));
            $('#img_u img').eq(0).attr('src', $(this).attr('code'));
        });
        // 控制商品下单的数量显示
        $(".add").on("click", function () {
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
            if (clickDom.hasClass('active') != true && clickDom.attr('data-url_2') == '') {
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
                        clickDom.attr('data-url_2', "{{ config('app.url') }}" + '/user_favourites/' + data.data.favourite.id);
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
                };
                url = clickDom.attr('data-url_2');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        clickDom.attr('data-url_2', '');
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
        //Tab控制函数
        function tabs(tabId, tabNum) {
            //设置点击后的切换样式
            $(tabId + " .tab li").removeClass("curr");
            $(tabId + " .tab li").eq(tabNum).addClass("curr");
            //根据参数决定显示内容
            $(tabId + " .tabcon").hide();
            $(tabId + " .tabcon").eq(tabNum).show();
            if (tabNum == 1) {
                getComments(1);
            }
        }
        //切换
        $(".kindOfPro").on("click", "li", function () {
            $(".kindOfPro").find('li').removeClass("active");
            $(this).addClass('active');
            $(".changePrice_num").html("{{ get_global_symbol() }}" + $(this).attr('code_price'));
            $("#pro_num").val("1");
        });
        //加入购物车
        $(".add_carts").on("click", function () {
            var clickDom = $(this);
            if ($(this).hasClass('for_show_login') == true) {
                $(".login").click();
            } else {
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
                        layer.alert("@lang('product.product_details.Shopping cart added successfully')");
                        $(".for_cart_num").load(location.href + " .shop_cart_num");
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
        });
        //立即购买
        $(".buy_now").on("click", function () {
            var clickDom = $(this);
            if ($(this).hasClass('for_show_login') == true) {
                $(".login").click();
            } else {
                var url = clickDom.attr('data-url');
                window.location.href = url + "?sku_id=" + sku_id + "&number=" + $("#pro_num").val() + "&sendWay=1";
            }
        });
        // 获取评价内容
        function getComments(page) {
            var data = {
                page: page,
            };
            var url = $(".shopping_eva").attr("data-url");
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
                    var dataObj_photo;
                    if (dataObj.length <= 0) {
                        $(".no_eva").removeClass('dis_n');
                        $(".comment-score h3").text("0.0");
                        $(".pre_page").addClass("not_allow");
                        $(".pre_page").attr("disabled", true);
                        $(".next_page").addClass("not_allow");
                        $(".next_page").attr("disabled", true);
                    } else {
                        var html = "";
                        // var name;
                        var parameters;
                        $(".composite_index").text((json.data.composite_index).toFixed(1));
                        $(".description_index").text((json.data.description_index).toFixed(1));
                        $(".shipment_index").text((json.data.shipment_index).toFixed(1));
                        $.each(dataObj, function (i, n) {
                            // name = (country == "中文") ? n.order_item.sku.name_zh : n.order_item.sku.name_en;
                            parameters = (country == "中文") ? n.order_item.sku.parameters_zh : n.order_item.sku.parameters_en;
                            dataObj_photo = n.photo_urls;
                            html += "<div class='item'>";
                            html += "<div class='evaluation_results_left'>";
                            html += "<div class='eva_user_img'>";
                            html += "<img src='" + n.user.avatar_url + "'>";
                            html += "</div>";
                            html += "<span>" + n.user.name + "</span>";
                            html += "</div>";
                            html += "<div class='evaluation_results_right'>";
                            html += "<div class='five_star_evaluation'>";
                            html += "<img src='" + "{{ config('app.url') }}" + "/img/star-" + n.composite_index + ".png' />";
                            html += "</div>";
                            html += "<p class='product_parameters'>";
                            // html += "<span>" + name + "</span>";
                            html += "<span>" + parameters + "</span>";
                            html += "</p>";
                            html += "<p class='eva_text'>" + n.content + "</p>";
                            html += "<ul class='evaluation_img'>";
                            $.each(dataObj_photo, function (a, b) {
                                html += "<li class='eva_img'>";
                                html += "<img src='" + b + "'>";
                                html += "</li>";
                            });
                            html += "</ul>";
                            html += "<p class='eva_date'>" + n.created_at + "</p>";
                            html += "</div>";
                            html += "</div>";
                        });
                        $(".comment-items .no_eva").nextAll().remove();
                        $(".comment-items").append(html);
                        $(".pre_page").attr("data-url", json.data.comments.prev_page_url);
                        $(".next_page").attr("data-url", json.data.comments.next_page_url);
                        $(".pre_page").attr("code", json.data.comments.from);
                        $(".next_page").attr("code", json.data.comments.to);
                        if (json.data.comments.prev_page_url == null) {
                            $(".pre_page").addClass("not_allow");
                            $(".pre_page").attr("disabled", true);
                        }
                        if (json.data.comments.next_page_url == null) {
                            $(".next_page").addClass("not_allow");
                            $(".next_page").attr("disabled", true);
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
            getComments($(this).attr("code"));
        });
        // 下一页
        $(".next_page").on("click", function () {
            getComments($(this).attr("code"));
        });
        // 图片预览小图移动效果,页面加载时触发
        $(function () {
            var tempLength = 0; // 临时变量,当前移动的长度
            var viewNum = 5; // 设置每次显示图片的个数量
            var moveNum = 2; // 每次移动的数量
            var moveTime = 300; // 移动速度,毫秒
            var scrollDiv = $(".spec-scroll .img_items ul"); // 进行移动动画的容器
            var scrollItems = $(".spec-scroll .img_items ul li"); // 移动容器里的集合
            var moveLength = scrollItems.eq(0).width() * moveNum; // 计算每次移动的长度
            var countLength = (scrollItems.length - viewNum) * scrollItems.eq(0).width(); // 计算总长度,总个数*单个长度
            // 下一张
            $(".spec-scroll .next").on("click", function () {
                if (tempLength < countLength) {
                    if ((countLength - tempLength) > moveLength) {
                        scrollDiv.animate({left: "-=" + moveLength + "px"}, moveTime);
                        tempLength += moveLength;
                    } else {
                        scrollDiv.animate({left: "-=" + (countLength - tempLength) + "px"}, moveTime);
                        tempLength += (countLength - tempLength);
                    }
                }
            });
            // 上一张
            $(".spec-scroll .prev").on("click", function () {
                if (tempLength > 0) {
                    if (tempLength > moveLength) {
                        scrollDiv.animate({left: "+=" + moveLength + "px"}, moveTime);
                        tempLength -= moveLength;
                    } else {
                        scrollDiv.animate({left: "+=" + tempLength + "px"}, moveTime);
                        tempLength = 0;
                    }
                }
            });
        });

        //数组选择器
        //定义skus数组内容
        var skus_arr = [];
        var size = null,
                colour = null,
                density = null;
        var skus_hide = $(".forSkusHidde").find("input");
        for (var skus_i = 0; skus_i <= skus_hide.length - 1; skus_i++) {
            skus_arr.push(JSON.parse($(skus_hide[skus_i]).val()));
        }
        sku_id = skus_arr[0].id;
        sku_stock = skus_arr[0].stock;
        $("#sku_price_in_usd").html("<i>{{ get_global_symbol() }}</i> " + skus_arr[0].price);
        var old_price = js_number_format(Math.imul(float_multiply_by_100(skus_arr[0].price), 12) / 1000);
        $("#sku_original_price_in_usd").html("<i>{{ get_global_symbol() }}</i> " + old_price);
        //根据三个select的值进行数组查询
        function map_search(search_size, search_colour, search_density) {
            return skus_arr.map(function (item, index) {
                if (item.base_size_en == search_size
                        && item.hair_colour_en == search_colour
                        && item.hair_density_en == search_density
                ) {
                    // return skus_arr[index];
                    var search_result = skus_arr[index];
                    if (search_result.length != 0) {
                        $("#sku_price_in_usd").html("<i>" + global_symbol + "</i> " + search_result.price);
                        var old_price = js_number_format(Math.imul(float_multiply_by_100(search_result.price), 12) / 1000);
                        $("#sku_original_price_in_usd").html("<i>" + global_symbol + "</i> " + old_price);
                        sku_price = get_current_price(search_result.price);
                        sku_original_price = get_current_price(old_price);
                        sku_id = search_result.id;
                        sku_stock = search_result.stock;
                        var dataStock = $(".availableSold .defalutavailableSold").attr("data-stock"),
                                dataSales = $(".availableSold .defalutavailableSold").attr("data-sales");
                        var stock = search_result.stock || dataStock,
                                sales = search_result.sales || dataSales;
                        $(".availableSold").find(".changeavailableSold").remove();
                        $(".defalutavailableSold").addClass('dis_ni');
                        $(".availableSold").append("<span class='changeavailableSold'>" + stock + " Available / <i>" + sales + " Sold</i></span>");
                        var sku_photo = search_result.photo_url;
                        if (sku_photo != "") {
                            $("#mediumContainer img").attr("src", sku_photo);
                            $("#img_u img").attr("src", sku_photo);
                        } else {
                            var active_src = $("#img_x .active").find("img").attr("src");
                            $("#mediumContainer img").attr("src", active_src);
                            $("#img_u img").attr("src", active_src);
                        }
                    } else {
                        layer.msg("Current specifications do not exist. Please re-select the selected items!");
                    }
                }
            }).filter(function (item) {
                return item != undefined;
            });
        }
        //数据选择器
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
        //数组去重
        function unique(arr) {
            var new_arr = arr.filter(function (element, index, self) {
                return self.indexOf(element) === index;
            });
            return new_arr;
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
        //每次切换select的时候根据当前的三个select已选中的默认进行多值查询，查找到的商品id与价格进行变换,
        //切换其中一个select时对size、colour、density的值进行判断，根据这三个参数的值判断哪个select已进行切换过
        //然后对当前选中的值在skus的数组中进行找到，找出当前值对应的商品分类，然后其他两个规格的内容进行数组查找，判断已选值是否存在
        //如果存在默认显示已选值内容，如不存在默认显示最新查询出的数组中的第一个值，避免出现三种select切换后出现商品不存在的情况

        //切换
        $(".priceOfpro").on("change", "select", function () {
            var current_val = $(this).val();
            var current_name = $(this).attr("name");
            var html_colour = '',
                    html_size = '',
                    html_density = '';
            var search_result = [],
                    judge_arr = [],
                    colour_arr = [],
                    size_arr = [],
                    density_arr = [];
            switch (current_name) {
                case '{{ __('product.product_details.base_size') }}':
                    size = current_val;
                    search_result = _findItemByValue(skus_arr, 'base_size_en', current_val);
                    $.each(search_result, function (i, n) {
                        colour_arr.push(n.hair_colour_en);
                        density_arr.push(n.hair_density_en)
                    });
                    colour_arr = unique(colour_arr);
                    density_arr = unique(density_arr);
                    $.each(colour_arr, function (i, n) {
                        html_colour += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(density_arr, function (i, n) {
                        html_density += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}']").append(html_colour);
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}']").append(html_density);
                    if (colour != null) {
                        if ($.inArray(colour, colour_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.hair_colour') }}']").find("option[value='" + colour + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("The selected colour is not available. Please re-select it!");
                        }
                    }
                    if (density != null) {
                        if ($.inArray(denisty, density_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.hair_density') }}']").find("option[value='" + denisty +"']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected density no goods, please re-select!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                case '{{ __('product.product_details.hair_colour') }}':
                    colour = current_val;
                    search_result = _findItemByValue(skus_arr, 'hair_colour_en', current_val);
                    $.each(search_result, function (i, n) {
                        size_arr.push(n.base_size_en);
                        density_arr.push(n.hair_density_en)
                    });
                    size_arr = unique(size_arr);
                    density_arr = unique(density_arr);
                    $.each(size_arr, function (i, n) {
                        html_size += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(density_arr, function (i, n) {
                        html_density += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}']").append(html_size);
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}']").append(html_density);
                    if (size != null) {
                        if ($.inArray(size, size_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.base_size') }}']").find("option[value='" + size + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected dimensions are not available. Please re-select them!");
                        }
                    }
                    if (density != null) {
                        if ($.inArray(denisty, density_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.hair_density') }}']").find("option[value='" + denisty +"']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected density no goods, please re-select!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                case '{{ __('product.product_details.hair_density') }}':
                    density = current_val;
                    search_result = _findItemByValue(skus_arr, 'hair_density_en', current_val);
                    $.each(search_result, function (i, n) {
                        size_arr.push(n.base_size_en);
                        colour_arr.push(n.hair_colour_en)
                    });
                    size_arr = unique(size_arr);
                    colour_arr = unique(colour_arr);
                    $.each(size_arr, function (i, n) {
                        html_size += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $.each(colour_arr, function (i, n) {
                        html_colour += "<option value='" + n + "'>" + n + "</option>"
                    });
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}'] option").remove();
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}']").append(html_size);
                    $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}']").append(html_colour);
                    if (size != null) {
                        if ($.inArray(size, size_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.base_size') }}']").find("option[value='" + size + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("Selected dimensions are not available. Please re-select them!");
                        }
                    }
                    if (colour != null) {
                        if ($.inArray(colour, colour_arr) >= 0) {
                            //已选select值存在
                            $(".priceOfpro select[name='{{ __('product.product_details.hair_colour') }}']").find("option[value='" + colour + "']").attr("selected", true);
                        } else {
                            //已选select不存在，需将对应条件重置，默认显示第一个
                            layer.msg("The selected colour is not available. Please re-select it!");
                        }
                    }
                    //每次选择后对当前三种规格的内容在数组中进行查找，改变价格及库存
                    var search_size = $(".priceOfpro").find("select[name='{{ __('product.product_details.base_size') }}']").val(),
                            search_colour = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_colour') }}']").val(),
                            search_density = $(".priceOfpro").find("select[name='{{ __('product.product_details.hair_density') }}']").val();
                    map_search(search_size, search_colour, search_density);
                    break;
                default:
                    size = null;
                    colour = null;
                    density = null;
                    break;
            }
        });

        // 页面加载时将商品信息存储到localstorage中，方便之后进行调取
        // 判断浏览器是否支持 localStorage 属性
        var hisProductOld = [],
                hisProductNew = [],
                trimArray = [];  //用于数组去重
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
        
        //商品详情iframe
        var iframe_content = $('.iframe_content').html();
        $('.iframe_content').html("");
        $('#cmsCon').contents().find('body').html(iframe_content);
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
