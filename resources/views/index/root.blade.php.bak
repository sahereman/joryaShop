@extends('layouts.app')
@section('title', '卓雅美业')

@section('content')
<div class="home-page">
	<div class="swiper-container banner" id="banner">
		<div class="swiper-wrapper">
	        <div class="swiper-slide">
	        	<a>
	        		<img  src="{{ asset('img/banner/banner_1.png') }}">
	        	</a>
	        </div>
	        <div class="swiper-slide">
	        	<a>
	        		<img  src="{{ asset('img/banner/banner_1.png') }}">
	        	</a>
	        </div>
	    </div>
	    <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
	</div>
	<!--新品首发-->
	<div class="new_product product-part">
		<div class="m-wrapper">
			<h3>新品首发</h3>
		    <div class="new_product_left pull-left">
		    	<div class="product_left_top">
		    		<img src="{{ asset('img/new_pro_1.png') }}">
		    			<div>
		    				<h2>糖果色片染</h2>
		    				<p>修颜减龄，风格前卫</p>
		    				<a class="info_more" href="{{ route('root') }}">查看更多</a>
		    			</div>
		    	</div>
		    	<div class="product_left_bottom">
		    		<img src="{{ asset('img/new_pro_3.png') }}">
	    			<div>
	    				<h2>欧式BOBO紫灰</h2>
	    				<p>修颜减龄，风格前卫</p>
	    				<a class="info_more" href="{{ route('root') }}">查看更多</a>
	    			</div>
		    	</div>
		    </div>
		    <div class="new_product_right pull-left">
		    	<img src="{{ asset('img/new_pro_2.png') }}">
	    		<div>
					<h2>时尚渐变色 风格前卫</h2>
					<a class="info_more" href="{{ route('root') }}">查看更多</a>
				</div>
		    </div>
		</div>
	</div>
	<!--时尚趋势-->
	<div class="fashion_trend product-part">
		<div class="m-wrapper">
			<div class="part_title">
				<h3>时尚趋势</h3>
				<ul class="pull-right">
					<li>
						<a href="{{ route('root') }}">直发</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">卷发</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">头套</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">刘海</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">发块</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">佩件</a>
						<span>/</span>
					</li>
				</ul>
			</div>
			<div class="content">
				<ul>
					@for ($i = 0; $i < 8; $i++)
						<li>
							<a href="{{ route('root') }}">
								<img src="{{ asset('img/trend_2.png') }}">
								<h5>时尚渐变色</h5>
								<span>修颜减龄，风格前卫	</span>
								<p class="product_price">￥2556.00</p>
							</a>
						</li>
					@endfor
				</ul>
			</div>
		</div>
	</div>
<!--高级定制-->
    <div  class="customization product-part">
    	<div class="m-wrapper">
    		<div class="part_title">
				<h3>高级定制</h3>
				<ul class="pull-right">
					<li>
						<a href="{{ route('root') }}">直发</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">卷发</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">头套</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">刘海</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">发块</a>
						<span>/</span>
					</li>
					<li>
						<a href="{{ route('root') }}">佩件</a>
						<span>/</span>
					</li>
				</ul>
			</div>
			<div class="customization_banner">
				<img src="{{ asset('img/ad_1.png') }}">
				<a class="buy_now" href="{{ route('root') }}">立即购买</a>
			</div>
			<div class="customization_list">
				<ul>
					@for ($i = 0; $i < 4; $i++)
						<li>
							<div>
								<img src="{{ asset('img/ad_5.png') }}">
								<a href="{{ route('root') }}">
									<div class="list_mask"></div>
									<img src="{{ asset('img/mask_search.png') }}">
								</a>
							</div>
							<h5>时尚渐变色</h5>
							<span>修颜减龄，风格前卫	</span>
							<p class="product_price">￥2556.00</p>
						</li>
					@endfor
				</ul>
			</div>
    	</div>
    </div>
    <!--猜你喜欢-->
    <div class="guess_like product-part">
    	<div class="m-wrapper">
    		<h3>猜你喜欢</h3>
    		<ul class="guess_lists">
    			@for ($i = 0; $i < 8; $i++)
					<li>
	    				<div class="guess_list_img">
	    					<div class="guess_list_tips">
	    						<img src="{{ asset('img/guess_tips.png') }}">
	    					</div>
	    					<img src="{{ asset('img/guess_2.png') }}">
	    				</div>
	    				<h5>时尚渐变色</h5>
						<p class="guess_price">
							<span class="new_price">255.00</span>
							<span class="old_price">588.00</span>
						</p>
						<a class="buy_now_guess" href="{{ route('root') }}">立即购买</a>
	    			</li>
				@endfor
    		</ul>
    		<!--服务承诺-->
    		<div class="service_commitment">
    			<ul>
    				<li>
    					<img src="{{ asset('img/thumb.png') }}">
    					<p>365正品承诺</p>
    					<p>Cenuine purchasing</p>
    				</li>
    				<li>
    					<img src="{{ asset('img/thumb.png') }}">
    					<p>7天无理由退款</p>
    					<p>7 days no reason to refund</p>
    				</li>
    				<li>
    					<img src="{{ asset('img/thumb.png') }}">
    					<p>满88元免邮费</p>
    					<p>Free postage </p>
    				</li>
    				<li>
    					<img src="{{ asset('img/thumb.png') }}">
    					<p>金牌服务</p>
    					<p>Great service</p>
    				</li>
    			</ul>
    		</div>
    	</div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script src="{{ asset('js/swiper/js/swiper.js') }}"></script>
    <script type="text/javascript">
    	$(function() {
			var swiper = new Swiper('.swiper-container', {
				  centeredSlides: true,
				  loop: true,
				  speed:1500,
//				  effect : 'cube',
				  fadeEffect: {
				    crossFade: true,
				  },
				  autoplay: {
				    delay: 3000,
				  },
				  navigation: {
				      nextEl: '.swiper-button-next',
				      prevEl: '.swiper-button-prev',
				  },
			});  
		});
    </script>
@endsection