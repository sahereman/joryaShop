@extends('layouts.app')
@section('title', '商品名称对应的详情')
@section('content')
    @include('common.error')
    <div class="commodity-details">
        <div class="m-wrapper">
        	<!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">商品分类</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">商品名称</a>
                </p>
            </div>
            <!--详情上半部分-->
            <div class="commodity_parameters">
            	<!--商品放大镜效果-->
				<div class="magnifierContainer">
			        <div class="imgLeft">
			            <!-- 中号图片 -->
			            <div class="imgMedium" id="imgMedium">
			                <!-- 放大镜 -->
			                <div class="magnifier" id="magnifier">
			                    <img src="{{ asset('img/zoom_pup.png') }}">
			                </div>
			                <!-- 图片 -->
			                <div class="mediumContainer" id="mediumContainer">
			                    <img src="{{ asset('img/detail-1.png') }}">
			                </div>    
			                <div id="zhezhao"></div>            
			            </div>
			            <!-- 缩略图 -->
			            <ul class="img_x" id="img_x">
			                @for ($a = 0; $a < 8; $a++)
				        		<li><img code="{{ asset('img/detail-1.png') }}" code="{{ asset('img/detailbig-1.png') }}" src="{{ asset('img/detailbd-1.png') }}"></li>
				        	@endfor
			            </ul>
			        </div>
			        <div class="imgRight">
			            <!-- 大图 -->
			            <div class="img_u" id="img_u">
			                <img src="{{ asset('img/detailbig-1.png') }}">
			            </div>
			        </div>        
			    </div>
			    <!--商品参数-->
			    <div class="parameters_content">
			    	<h4>假发女长发长卷发大波浪中长发蓬松自然网红可爱真发八字刘海自然网红 可爱真发八字刘海</h4>
			    	<p class="small_title">机打发票，假一赔十</p>
			    	<div class="price_service">
			    		<p class="original_price">
			    			<span>原价</span>
			    			<span><i>￥</i>840.00</span>
			    		</p>
			    		<p class="present_price">
			    			<span>现价</span>
			    			<span><i>￥</i>840.00</span>
			    		</p>
			    		<p class="service">
			    			<span>服务</span>
			    			<span class="service-kind"><i>•</i>7天无理由退款</span>
			    			<span class="service-kind"><i>•</i>48小时快速退款</span>
			    		</p>
			    	</div>
		    		<div class="priceOfpro">
		    			<span>运费</span>
		    			<span><i>￥</i>10.00</span>
		    		</div>
		    		<div class="priceOfpro">
		    			<span>分类</span>
		    			<ul>
		    				@for ($i = 0; $i < 8; $i++)
				        		<li>
				        			<span>局部手织-自然黑</span>
				        		</li>
				        	@endfor
		    			</ul>
		    		</div>
		    		<div class="priceOfpro">
		    			<span class="buy_numbers">数量</span>
		    			<div class="quantity_control">
		    				<span class="reduce no_allow"><i>-</i></span>
		    				<input id="pro_num" type="number" value="1" min="1" max="99">
		    				<span class="add"><i>+</i></span>
		    			</div>
		    		</div>
		    		<!--添加购物车与立即购买-->
				    <div class="addCart_buyNow">
				    	<a class="buy_now">立即购买</a>
				    	<a class="add_carts">加入购物车</a>
				    	<a class="add_favourites">
				    		<span class="favourites_img"></span>
				    		<span>收藏</span>
				    	</a>
				    </div>
			    </div>
			    <!--猜你喜欢-->
			    <div class="guess_like">
			    	<p>
			    		<img src="{{ asset('img/guess-like-title.png') }}">
			    	</p>
			    	<ul>
			    		@for ($b = 0; $b < 3; $b++)
			        		<li>
			        			<a>
			        				<div>
				        				<img src="{{ asset('img/detail-guess-like.png') }}">
				        			</div>
				        			<p>
				        				<span class="present_price"><i>￥</i>840.00</span>
				        				<span class="original_price"><i>￥</i>840.00</span>
				        			</p>
			        			</a>
			        		</li>
			        	@endfor
			    	</ul>
			    </div>
            </div>
            <!--详情下半部分-->
            <div class="comments_details">
            	<div class="comments_details_left pull-left" id="list">
            		<ul class="tab">
            			<li onclick="tabs('#list',0)" class="curr">热销榜</li>
                        <li onclick="tabs('#list',1)">人气榜</li>
            		</ul>
            		<div class="mc tabcon">
				        <ul class="pro-lists">
				        	@for ($b = 0; $b < 4; $b++)
				        		<li>
				        			<a>
				        				<div>
					        				<img src="{{ asset('img/list-1.png') }}">
					        			</div>
					        			<p>
					        				<span class="present_price"><i>￥</i>840.00</span>
					        			</p>
				        			</a>
				        		</li>
				        	@endfor
				        </ul>
				    </div>
				    <div class="mc tabcon dis_n">
				    	<ul class="pro-lists">
				    		@for ($c = 0; $c < 4; $c++)
				        		<li>
				        			<a>
				        				<div>
					        				<img src="{{ asset('img/list-1.png') }}">
					        			</div>
					        			<p>
					        				<span class="present_price"><i>￥</i>840.00</span>
					        			</p>
				        			</a>
				        		</li>
					        @endfor
				    	</ul>
				    </div>
            	</div>
            	<div class="comments_details_right pull-left" id="comments_details">
            		<ul class="tab">
            			<li onclick="tabs('#comments_details',0)" class="curr">商品详情</li>
                        <li onclick="tabs('#comments_details',1)">商品评价<strong>(900+)</strong></li>
            		</ul>
            		<div class="mc tabcon">
				        <ul class="pro-detail-lists">
				        	<li>
				        		<span>商品名称：</span>
				        		<span>玫瑰雨 假发 中老年真发假发短发女</span>
				        	</li>
				        	<li>
				        		<span>货号：</span>
				        		<span>1018发丝</span>
				        	</li>
				        	<li>
				        		<span>性别：</span>
				        		<span>女士</span>
				        	</li>
				        	<li>
				        		<span>长度：</span>
				        		<span>短发</span>
				        	</li>
				        	<li>
				        		<span>商品编号：</span>
				        		<span>1411628197</span>
				        	</li>
				        	<li>
				        		<span>材质：</span>
				        		<span>真人发丝</span>
				        	</li>
				        	<li>
				        		<span>品牌：</span>
				        		<span>玫瑰雨</span>
				        	</li>
				        	<li>
				        		<span>颜色：</span>
				        		<span>其它</span>
				        	</li>
				        	<li>
				        		<span>商品毛重：</span>
				        		<span>100.00g  </span>
				        	</li>
				        </ul>
				        <div class="pro-detail-imgs">
				        	@for ($d = 0; $d < 4; $d++)
				        		<img class="lazy" src="{{ asset('img/detail-img.png') }}">
					        @endfor
				        </div>
				    </div>
            		<div class="mc tabcon dis_n">
            			<ul class="comment-score">
            				<li>
            					<span>综合评分</span>
            					<h3>4.8</h3>
            					<img src="{{ asset('img/star-5.png') }}">
            				</li>
            				<li>
            					<span>描述相符</span>
            					<h3>4.8</h3>
            					<img src="{{ asset('img/star-5.png') }}">
            				</li>
            				<li>
            					<span>物流服务</span>
            					<h3>4.8</h3>
            					<img src="{{ asset('img/star-5.png') }}">
            				</li>
            			</ul>
            			<div class="comment-items">
            				<div class="items-title">
            					<a class="active">商品评价<strong>(900+)</strong></a>
            					<a>图片评价</a>
            				</div>
            				@for ($e = 0; $e < 4; $e++)
				        		<div class="item">
				        			<div class="evaluation_results_left">
		                                <div class="eva_user_img">
		                                    <img src="{{ asset('img/eva_user.png') }}">
		                                </div>
		                                <span>用户昵称</span>
		                            </div>
		                            <div class="evaluation_results_right">
		                                <div class="five_star_evaluation">
		                                    <img src="{{ asset('img/star-5.png') }}">
		                                </div>
		                                <p class="product_parameters">
		                                    <span>尺寸1.8cm   颜色：深棕色</span>
		                                </p>
		                                <p class="eva_text">送貨快、包裝好、希望產品品質也一樣好，等下水洗過就知道囉 140*140</p>
	                                    <ul class="evaluation_img">
	                                        @for ($d = 0; $d < 4; $d++)
								        		<li class="eva_img">
	                                                <img src="{{ asset('img/ad_5.png') }}">
	                                            </li>
									        @endfor
	                                   </ul>
	                                   <p class="eva_date">2018-09-18 13:44</p>
		                            </div>
				        		</div>
					        @endfor
            			</div>
            			<!--分页-->
	                    <div class="paging_box">
	                        <a class="pre_page" href="{{ route('users.home') }}">上一页</a>
	                        <a class="next_page" href="{{ route('users.home') }}">下一页</a>
	                    </div>
				    </div>
            	</div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
	<script type="text/javascript">
	    $('#img_x li').eq(0).css('border', '2px solid coral');
	    $('#zhezhao').mousemove(function(e){
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
	        var bigTop =  topRate * parseInt($('#img_u img').outerHeight());
	        $('#img_u img').css('margin-top', -bigTop + 'px');
	    })
	    $('#zhezhao').mouseleave(function(){
	        $('#img_u').hide();
	        $('#magnifier').hide();
	    })
	    $('#img_x li').mouseover(function(){
	        $(this).css('border', '2px solid #bc8c61').siblings().css('border', '2px solid transparent');
	        $('#mediumContainer img').eq(0).attr('src', $(this).attr('code'));
	        $('#img_u img').eq(0).attr('src', $(this).attr('code-1'));
	    })
	    //控制商品下单的数量显示
	    $(".add").on("click",function(){
	    	$(".reduce").removeClass('no_allow');
	    	var num = parseInt($("#pro_num").val())  + 1;
	    	$("#pro_num").val(num);
	    })
	    $(".reduce").on("click",function(){
	    	if($(this).hasClass('no_allow')!=true&&$("#pro_num").val()>1) {
	    		var num = parseInt($("#pro_num").val())  - 1;
	    		if(num ==1) {
	    			$("#pro_num").val(1);	
	    			$(this).addClass('no_allow');
	    		} else {
	    			$("#pro_num").val(num);
	    		}
	    	}
	    })
	    //点击添加收藏
	    $(".add_favourites").on("click",function(){
	    	if($(this).hasClass('active')!=true) {
	    		$(this).addClass('active');	
	    	}else {
	    		$(this).removeClass('active');
	    	}
	    })
	    //Tab控制函数
		function tabs(tabId, tabNum){
			//设置点击后的切换样式
			$(tabId + " .tab li").removeClass("curr");
			$(tabId + " .tab li").eq(tabNum).addClass("curr");
			//根据参数决定显示内容
			$(tabId + " .tabcon").hide();
			$(tabId + " .tabcon").eq(tabNum).show();
		}
	</script>
@endsection
