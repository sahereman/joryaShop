@extends('layouts.app')
@section('title', (App::isLocale('en') ? $product->name_en : $product->name_zh) . ' - ' . \App\Models\Config::config('title'))
@section('description', App::isLocale('en') ? $product->description_en : $product->description_zh)
@section('content')
    <div class="commodity-details">
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    @if($category->parent)
                        <span>></span>
                        <a href="{{ route('product_categories.index', ['category' => $category->parent->id]) }}">{{ App::isLocale('en') ? $category->parent->name_en : $category->parent->name_zh }}</a>
                    @endif
                    <span>></span>
                    <a href="{{ route('product_categories.index', ['category' => $category->id]) }}">{{ App::isLocale('en') ? $category->name_en : $category->name_zh }}</a>
                    <span>></span>
                    <a href="javascript:void(0);">{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</a>
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
                    <h4>{{ App::isLocale('en') ? $product->name_en : $product->name_zh }}</h4>
                    <p class="small_title">{!! App::isLocale('en') ? $product->description_en : $product->description_zh !!}</p>
                    <div class="price_service">
                        <p class="original_price">
                            <span>@lang('product.product_details.the original price')</span>
                            <span><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? bcmul($product->price_in_usd, 1.2, 2) : bcmul($product->price, 1.2, 2) }}</span>
                        </p>
                        <p class="present_price">
                            <span>@lang('product.product_details.the current price')</span>
                            <span class="changePrice_num"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->price_in_usd : $product->price }}</span>
                        </p>
                        <p class="service">
                            <span>@lang('product.product_details.service')</span>
                            <span class="service-kind"><i>•</i>@lang('product.product_details.no reason for a refund within seven days')</span>
                            <span class="service-kind"><i>•</i>@lang('product.product_details.Quick refund in 48 hours')</span>
                        </p>
                    </div>
                    <div class="priceOfpro">
                        <span>@lang('product.product_details.freight')</span>
                        <span><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $product->shipping_fee_in_usd : $product->shipping_fee }}</span>
                    </div>
                    <div class="priceOfpro kindOfPro">
                        <span>@lang('product.product_details.classification')</span>
                        <ul>
                            @foreach($skus as $sku)
                                <li code_num="{{ $sku->stock }}" code_price='{{ App::isLocale('en') ? $sku->price_in_usd : $sku->price }}'>
                                    <span>{{ App::isLocale('en') ? $sku->name_en : $sku->name_zh }}</span>
                                    <input type="hidden" name="sku_id" value="{{ $sku->id }}">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="priceOfpro">
                        <span class="buy_numbers">@lang('product.product_details.Quantity')</span>
                        <div class="quantity_control">
                            <span class="reduce no_allow"><i>-</i></span>
                            <input name="number" id="pro_num" type="number" value="1" min="1" max="99">
                            <span class="add"><i>+</i></span>
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
                                        <span class="present_price"><i>@lang('basic.currency.symbol')</i>{{ App::isLocale('en') ? $guess->price_in_usd : $guess->price }}</span>
                                        <span class="original_price"><i>@lang('basic.currency.symbol')</i>{{ App::isLocale('en') ? bcmul($guess->price_in_usd, 1.2, 2) : bcmul($guess->price, 1.2, 2) }}</span>
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
                        <li onclick="tabs('#list',0)" class="curr">@lang('product.product_details.Hot sales')</li>
                        <li onclick="tabs('#list',1)">@lang('product.product_details.Popular sales')</li>
                    </ul>
                    <div class="mc tabcon">
                        <ul class="pro-lists">
                            @foreach($hot_sales as $hot_sale)
                                <li>
                                    <a href="{{ route('products.show', ['product' => $hot_sale->id]) }}">
                                        <div>
                                            <img class="lazy" data-src="{{ $hot_sale->thumb_url }}">
                                        </div>
                                        <p>
                                            <span class="present_price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $hot_sale->price_in_usd : $hot_sale->price }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endforeach
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
                                            <span class="present_price"><i>@lang('basic.currency.symbol') </i>{{ App::isLocale('en') ? $best_seller->price_in_usd : $best_seller->price }}</span>
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
                        {!! App::isLocale('en') ? $product->content_en : $product->content_zh !!}
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var loading_animation;  //loading动画的全局name
        var current_page;  //评价的当前页
        var next_page;   //下一页的页码
        var pre_page;   //上一页的页码
        var country = $("#dLabel").find("span").html();
        $('#img_x li').eq(0).css('border', '2px solid #bc8c61');
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
            var bigLeft = leftRate * parseInt($('#img_u img').outerWidth());
            $('#img_u img').css('margin-left', -bigLeft + 'px');

            var topRate = top / parseInt($('#zhezhao').outerHeight());
            var bigTop = topRate * parseInt($('#img_u img').outerHeight());
            $('#img_u img').css('margin-top', -bigTop + 'px');
        });
        $('#zhezhao').mouseleave(function () {
            $('#img_u').hide();
            $('#magnifier').hide();
        });
        $('#img_x li').mouseover(function () {
            $(this).css('border', '2px solid #bc8c61').siblings().css('border', '2px solid transparent');
            $('#mediumContainer img').eq(0).attr('src', $(this).attr('code'));
            $('#img_u img').eq(0).attr('src', $(this).attr('code'));
        });
        //控制商品下单的数量显示
        $(".add").on("click", function () {
        	if ($(".kindOfPro").find("li").hasClass('active') != true) {
                layer.msg("@lang('product.product_details.Please select specifications')");
            } else {
	            $(".reduce").removeClass('no_allow');
	            if(parseInt($("#pro_num").val())<parseInt($(".kindOfPro").find("li.active").attr('code_num'))){
	            	var num = parseInt($("#pro_num").val()) + 1;
	                $("#pro_num").val(num);	
	            }else{
	            	layer.msg("@lang('order.Cannot add more quantities')");
	            }
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
        //点击添加收藏
        $(".add_favourites").on("click", function () {
            var clickDom = $(this);
            if (clickDom.hasClass('active') != true && clickDom.attr('data-url_2') == '') {
                var data = {
                    _token: "{{ csrf_token() }}",
                    product_id: clickDom.attr("code")
                };
                var url = clickDom.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        clickDom.attr('data-url_2', "{{ config('app.url') }}" + '/user_favourites/' + data.data.favourite.id);
                        clickDom.addClass('active');
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status == 422) {
                            layer.msg($.parseJSON(err.responseText).errors.product_id[0]);
                        }
                    }
                });
            } else {
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                };
                var url = clickDom.attr('data-url_2');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        clickDom.attr('data-url_2', '');
                        clickDom.removeClass('active');
                    },
                    error: function (err) {
                        console.log(err);
                    }
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
                getEva(1);
            }
        }
        //切换
        $(".kindOfPro").on("click", "li", function () {
            $(".kindOfPro").find('li').removeClass("active");
            $(this).addClass('active');
            $(".changePrice_num").html("@lang('basic.currency.symbol')" + $(this).attr('code_price'));
            $("#pro_num").val("1");
        });
        //加入购物车
        $(".add_carts").on("click", function () {
            var clickDom = $(this);
            if ($(".kindOfPro").find("li").hasClass('active') != true) {
                layer.msg("@lang('product.product_details.Please select specifications')");
            } else {
                if ($(this).hasClass('for_show_login') == true) {
                    $(".login").click();
                } else {
                    var data = {
                        _token: "{{ csrf_token() }}",
                        sku_id: $(".kindOfPro ul").find("li.active").find("input").val(),
                        number: $("#pro_num").val()
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
                            console.log(err);
                        }
                    });
                }
            }
        });
        //立即购买
        $(".buy_now").on("click", function () {
            var clickDom = $(this);
            if ($(".kindOfPro").find("li").hasClass('active') != true) {
                layer.msg("@lang('product.product_details.Please select specifications')");
            } else {
                if ($(this).hasClass('for_show_login') == true) {
                    $(".login").click();
                } else {
                    var url = clickDom.attr('data-url');
                    window.location.href = url + "?sku_id=" + $(".kindOfPro ul").find("li.active").find("input").val() + "&number=" + $("#pro_num").val() + "&sendWay=1";
                }
            }
        });
        //获取评价内容
        function getEva(page) {
            var data = {
                page: page
            };
            var url = $(".shopping_eva").attr("data-url");
            $.ajax({
                type: "get",
                url: url,
                beforeSend: function () {
                    loading_animation = layer.msg("@lang('app.Please wait')", {
                        icon: 16,
                        shade: 0.4,
                        time: false //取消自动关闭
                    });
                },
                success: function (json) {
                    console.log(json);
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
                        var name;
                        $(".composite_index").text((json.data.composite_index).toFixed(1));
                        $(".description_index").text((json.data.description_index).toFixed(1));
                        $(".shipment_index").text((json.data.shipment_index).toFixed(1));
                        $.each(dataObj, function (i, n) {
                            name = (country == "中文") ? n.order_item.sku.name_zh : n.order_item.sku.name_en;
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
                            html += "<span>" + name + "</span>";
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
                    console.log(e);
                },
                complete: function () {
                    layer.close(loading_animation);
                }
            });
            //放大镜的缩略图的上一页与下一页
        }
        //点击分页
        //上一页
        $(".pre_page").on("click", function () {
            getEva($(this).attr("code"));
        });
        //下一页
        $(".next_page").on("click", function () {
            getEva($(this).attr("code"));
        });
        //图片预览小图移动效果,页面加载时触发
        $(function () {
            var tempLength = 0; //临时变量,当前移动的长度
            var viewNum = 5; //设置每次显示图片的个数量
            var moveNum = 2; //每次移动的数量
            var moveTime = 300; //移动速度,毫秒
            var scrollDiv = $(".spec-scroll .img_items ul"); //进行移动动画的容器
            var scrollItems = $(".spec-scroll .img_items ul li"); //移动容器里的集合
            var moveLength = scrollItems.eq(0).width() * moveNum; //计算每次移动的长度
            var countLength = (scrollItems.length - viewNum) * scrollItems.eq(0).width(); //计算总长度,总个数*单个长度

            //下一张
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
            //上一张
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
    </script>
@endsection
