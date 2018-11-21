@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    <div class="evaluate_commont">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.The_order_details')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.feedback')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="comment_content">
                @foreach ($order->snapshot as $order_item)
                    <div class="evaluation_order">
                        <table>
                            <thead>
                            <th></th>
                            <th>@lang('product.comments.commodity')</th>
                            <th>@lang('product.comments.specification')</th>
                            <th>@lang('product.comments.Unit Price')</th>
                            <th>@lang('product.comments.Quantity')</th>
                            <th>@lang('product.comments.Subtotal')</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-pro-img">
                                    <a href="">
                                        <img class="lazy" data-src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                    </a>
                                </td>
                                <td class="col-pro-info">
                                    <p class="p-info">
                                        <a class="commodity_description"
                                           href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</a>
                                    </p>
                                </td>
                                <td class="col-pro-speci">
                                    <p class="p-info">
                                        <a class="specifications"
                                           href="{{ route('products.show', $order_item['sku']['product']['id']) }}">{{ App::isLocale('en') ? $order_item['sku']['product']['description_en'] : $order_item['sku']['product']['description_zh'] }}</a>
                                    </p>
                                </td>
                                <td class="col-price">
                                    <p class="p-price">
                                        <em>{{ App::isLocale('en') ? '&#36;' : '&yen;' }}</em>
                                        <span>{{ $order_item['price'] }}</span>
                                    </p>
                                </td>
                                <td class="col-quty">
                                    <p>{{ $order_item['number'] }}</p>
                                </td>
                                <td class="col-pay">
                                    <p>
                                        <em>{{ App::isLocale('en') ? '&#36;' : '&yen;' }}</em>
                                        <span>{{ $order_item['price'] * $order_item['number'] }}</span>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="evaluation_results">
                            <div class="evaluation_results_left">
                                <div class="eva_user_img">
                                    <img src="{{ $user->avatar_url }}">
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                            <div class="evaluation_results_right">
                                <div class="five_star_evaluation">
                                    <div class="five_star_one star_area">
                                        <div class="starability-basic">
                                            <img src="{{ asset('img/star-' . $comments[$order_item['id']][0]['composite_index'] . '.png') }}">
                                        </div>
                                    </div>
                                </div>
                                <p class="product_parameters">
                                    <span>{{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}</span>
                                </p>
                                <p class="eva_text">{{ $comments[$order_item['id']][0]->content }}</p>
                                <div class="tm-m-photos">
                                    <ul class="evaluation_img">
                                        @foreach($comments[$order_item['id']][0]->photo_urls as $photo_url)
                                            <li class="eva_img" data-src="{{ $photo_url }}">
                                                <img class="lazy" data-src="{{ $photo_url }}">
                                                <b class="tm-photos-arrow"></b>
                                            </li>
                                        @endforeach
                                    </ul>
                                    {{--<div class="evaluation_img_viewer">
                                        <img src="{{ asset('img/eva_img.png') }}">
                                        <a class="tm-m-photo-viewer-navleft" style="cursor: default;"> <i class="tm-m-photo-viewer-navicon arrow-left">&lt;</i> </a>
                                        <a class="tm-m-photo-viewer-navright" style="cursor: pointer;"> <i class="tm-m-photo-viewer-navicon arrow-right">&gt;</i> </a>
                                    </div>--}}
                                </div>
                                <p class="eva_time">{{ $comments[$order_item['id']][0]->created_at }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete").show();
            });
//            var obj = new commentMove('.tm-m-photos', '.evaluation_img_viewer');
//            obj.init()
        });
        /* 
         parentcontent  //父容器
         boxcontent   // 评论区图片展示区域
         */
        //            function commentMove(parentcontent, boxcontent) {
        //                this.obj = {
        //                    activeClass: 'tm-current',
        //                    nextButton: '.tm-m-photo-viewer-navright',
        //                    prevButton: '.tm-m-photo-viewer-navleft',
        //                }
        //                this.parentcontent = parentcontent;
        //                this.boxcontent = boxcontent;
        //            
        //            }
        //            commentMove.prototype = {
        //                init: function () {
        //                    var that = this;
        //                    that.start();
        //                    this.lefthover();
        //                    this.righthover();
        //                    this.leftclick();
        //                    this.rightclick();
        //                },
        //                start: function () {
        //                    var that = this;
        //                    $(that.parentcontent + ' li').click(function () {
        //            
        //                        $(this).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
        //                        var src = $('.' + that.obj.activeClass).attr('data-src');
        //            
        //                        var img = new Image();
        //                        img.src = src;
        //                        img.onload = function () {
        //                            var imageWidth = img.width;
        //                            var imageHeight = img.height;
        //                            $(that.boxcontent).css({ "width": imageWidth, "height": imageHeight })
        //            //                $(that.obj.prevButton).css({ "width": imageWidth / 3, "height": imageHeight })
        //                            $(that.obj.prevButton).children().css({ "top": imageHeight / 2 - 10 + 'px' })
        //                            $(that.obj.nextButton).children().css({ "top": imageHeight / 2 - 10 + 'px' })
        //            
        //                        }
        //                        if (!src) {
        //                            $(that.boxcontent).css({ "width": 0, "height": 0 });
        //                        } else {
        //                            $(that.boxcontent + " img").attr('src', src);
        //                        }
        //                    })
        //                },
        //                lefthover: function () {
        //                    var that = this;
        //                    $(that.obj.prevButton).hover(function () {
        //                        var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
        //                        if (index < 1) {
        //                            $(this).children().css("display", "none");
        //                        } else {
        //                            $(this).children().css({ "display": "inline" });
        //                        }
        //                    }, function () {
        //                        $(this).children().css({ "display": "none" });
        //                    })
        //                },
        //                righthover: function () {
        //                    var that = this;
        //                    $(that.obj.nextButton).hover(function () {
        //                        var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
        //                        if (index >= $(that.parentcontent + ' li').length - 1) {
        //                            $(this).children().css("display", "none");
        //                        } else {
        //                            $(this).children().css({ "display": "inline" });
        //                        }
        //                    }, function () {
        //                        $(this).children().css({ "display": "none" });
        //                    })
        //                },
        //                leftclick: function () {
        //                    var that = this;
        //                    $(that.obj.prevButton).click(function () {
        //                        var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
        //            
        //                        index--;
        //                        if (index >= 0) {
        //                            $(that.boxcontent + " img").attr("src", $(that.parentcontent + ' li').eq(index).attr('data-src'))
        //                               $(that.parentcontent + ' li').eq(index).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
        //                        }
        //                        if (index < 1) {
        //                            index = 0;
        //                            $(this).children().css({ "display": "none" });
        //                            return;
        //                        }
        //                    })
        //                },
        //                rightclick: function () {
        //                    var that = this;
        //                    $(that.obj.nextButton).click(function () {
        //                        var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
        //                        index++;
        //                        $(that.boxcontent + " img").attr("src", $(that.parentcontent + ' li').eq(index).attr('data-src'))
        //            
        //                        $(that.parentcontent + ' li').eq(index).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
        //                        if (index >= $(that.parentcontent + ' li').length - 1) {
        //                            $(this).children().css({ "display": "none" });
        //                        }
        //                    })
        //                }
        //            }
    </script>
@endsection
