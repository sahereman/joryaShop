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
	<div class="new_product product-part">
		<div class="m-wrapper">
			<h3>新品首发</h3>
		    <div class="">
		    	
		    </div>
		</div>
	</div>
	<div class="fashion_trend product-part">
		<div class="m-wrapper">
			<h3>时尚趋势</h3>
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