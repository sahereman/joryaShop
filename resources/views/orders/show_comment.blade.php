@extends('layouts.app')
@section('title', '个人中心-我的订单')
@section('content')
    @include('common.error')
    <div class="evaluate_commont">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">我的订单</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">订单详情</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">评价</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
            <!--右侧内容-->
            <div class="comment_content">
            	@for ($j = 0; $j <3; $j++)
            		<div class="evaluation_order">
            			<table>
            				<thead>
            					<th></th>
            					<th>商品</th>
            					<th>规格</th>
            					<th>单价</th> 
            					<th>数量</th>
            					<th>小计</th>
            				</thead>
            				<tbody>
	            				<tr>
		                			<td class="col-pro-img">
		                				<a href="">
	                                        <img src="{{ asset('img/order-pro.png') }}">
	                                    </a>
		                			</td>
	                                <td class="col-pro-info">
	                                    <p class="p-info">
	                                        <a class="commodity_description"  href="">卓页美业长直假发片</a>
	                                    </p>
	                                </td>
	                                <td class="col-pro-speci">
	                                    <p class="p-info">
	                                        <a class="specifications"  href="">蓝色</a>
	                                    </p>
	                                </td>
	                                <td class="col-price">
	                                    <p class="p-price">
	                                        <em>¥</em>
	                                        <span>50.00</span>
	                                    </p>
	                                </td>
	                                <td class="col-quty">
	                                	<p>1</p>
	                                </td>
	                                <td class="col-pay">
	                                    <p>
	                                        <em>¥</em>
	                                        <span>120.00</span>
	                                    </p>
	                                </td>
		                		</tr>
	            			</tbody>
            			</table>
            			<div class="evaluation_results">
            				<div class="evaluation_results_left">
            					<div class="eva_user_img">
            						<img src="{{ asset('img/eva_user.png') }}">
            					</div>
            					<span>用户昵称</span>
            				</div>
            				<div class="evaluation_results_right">
            					<div class="five_star_evaluation">
	            					<div class="five_star_one star_area">
	            						<div class="starability-basic">
	            							<img src="{{ asset('img/star-4.png') }}">
	            						</div>
	            					</div>
								</div>
								<p class="product_parameters">
									<span>尺寸1.8cm</span>
									<span>颜色：深棕色</span>
								</p>
								<p class="eva_text">送貨快、包裝好、希望產品品質也一樣好，等下水洗過就知道囉</p>
								<div class="tm-m-photos">
									<ul class="evaluation_img">
										@for ($a = 0; $a <3; $a++)
										<li class="eva_img" data-src="{{ asset('img/eva_img.png') }}">
											<img src="{{ asset('img/eva_img.png') }}">
										    <b class="tm-photos-arrow"></b>
										</li>
										@endfor
									</ul>
									<!--<div class="evaluation_img_viewer"> 
									    <img src="{{ asset('img/eva_img.png') }}"> -->
										  <!--<a class="tm-m-photo-viewer-navleft" style="cursor: default;"> <i class="tm-m-photo-viewer-navicon arrow-left">&lt;</i> </a> 
										  <a class="tm-m-photo-viewer-navright" style="cursor: pointer;"> <i class="tm-m-photo-viewer-navicon arrow-right">&gt;</i> </a> -->
									<!--</div>-->
								</div>
								<p class="eva_time">2018-09-18 13:44</p>
            				</div>
            			</div>
            	    </div>
				@endfor
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
//		    var obj = new commentMove('.tm-m-photos', '.evaluation_img_viewer');
//		    obj.init()
        });
        /* 
			parentcontent  //父容器
			boxcontent   // 评论区图片展示区域
			*/
//			function commentMove(parentcontent, boxcontent) {
//			    this.obj = {
//			        activeClass: 'tm-current',
//			        nextButton: '.tm-m-photo-viewer-navright',
//			        prevButton: '.tm-m-photo-viewer-navleft',
//			    }
//			    this.parentcontent = parentcontent;
//			    this.boxcontent = boxcontent;
//			
//			}
//			commentMove.prototype = {
//			    init: function () {
//			        var that = this;
//			        that.start();
//			        this.lefthover();
//			        this.righthover();
//			        this.leftclick();
//			        this.rightclick();
//			    },
//			    start: function () {
//			        var that = this;
//			        $(that.parentcontent + ' li').click(function () {
//			
//			            $(this).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
//			            var src = $('.' + that.obj.activeClass).attr('data-src');
//			
//			            var img = new Image();
//			            img.src = src;
//			            img.onload = function () {
//			                var imageWidth = img.width;
//			                var imageHeight = img.height;
//			                $(that.boxcontent).css({ "width": imageWidth, "height": imageHeight })
//			//                $(that.obj.prevButton).css({ "width": imageWidth / 3, "height": imageHeight })
//			                $(that.obj.prevButton).children().css({ "top": imageHeight / 2 - 10 + 'px' })
//			                $(that.obj.nextButton).children().css({ "top": imageHeight / 2 - 10 + 'px' })
//			
//			            }
//			            if (!src) {
//			                $(that.boxcontent).css({ "width": 0, "height": 0 });
//			            } else {
//			                $(that.boxcontent + " img").attr('src', src);
//			            }
//			        })
//			    },
//			    lefthover: function () {
//			        var that = this;
//			        $(that.obj.prevButton).hover(function () {
//			            var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
//			            if (index < 1) {
//			                $(this).children().css("display", "none");
//			            } else {
//			                $(this).children().css({ "display": "inline" });
//			            }
//			        }, function () {
//			            $(this).children().css({ "display": "none" });
//			        })
//			    },
//			    righthover: function () {
//			        var that = this;
//			        $(that.obj.nextButton).hover(function () {
//			            var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
//			            if (index >= $(that.parentcontent + ' li').length - 1) {
//			                $(this).children().css("display", "none");
//			            } else {
//			                $(this).children().css({ "display": "inline" });
//			            }
//			        }, function () {
//			            $(this).children().css({ "display": "none" });
//			        })
//			    },
//			    leftclick: function () {
//			        var that = this;
//			        $(that.obj.prevButton).click(function () {
//			            var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
//			
//			            index--;
//			            if (index >= 0) {
//						    $(that.boxcontent + " img").attr("src", $(that.parentcontent + ' li').eq(index).attr('data-src'))
//			   	            $(that.parentcontent + ' li').eq(index).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
//						}
//			            if (index < 1) {
//							index = 0;
//			                $(this).children().css({ "display": "none" });
//							return;
//			            }
//			        })
//			    },
//			    rightclick: function () {
//			        var that = this;
//			        $(that.obj.nextButton).click(function () {
//			            var index = $(that.parentcontent + ' li').index($(that.parentcontent + ' li.' + that.obj.activeClass));
//			            index++;
//			            $(that.boxcontent + " img").attr("src", $(that.parentcontent + ' li').eq(index).attr('data-src'))
//			
//			            $(that.parentcontent + ' li').eq(index).toggleClass(that.obj.activeClass).siblings().removeClass(that.obj.activeClass);
//			            if (index >= $(that.parentcontent + ' li').length - 1) {
//			                $(this).children().css({ "display": "none" });
//			            }
//			        })
//			    }
//			}
    </script>
@endsection