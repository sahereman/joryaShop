@extends('layouts.mobile')
@section('title', '商品详情')
@section('content')
	<div class="goodsDetailBox">
		<div class="goodsSwiper swiper-container">
		    <div class="swiper-wrapper">
		        @for($i = 0; $i < 5; $i++)
			    <div class="swiper-slide">
			    	<img src="{{ asset('static_m/img/blockImg.png') }}">
			    </div>
			    @endfor
		    </div>
		    <!-- 如果需要分页器 -->
		    <div class="swiper-pagination"></div>
		</div>
	</div>


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        var mySwiper = new Swiper ('.swiper-container', {
//		    loop: true,
		    // 如果需要分页器
		    pagination: '.swiper-pagination',
//		    autoplay:3000,
//		    stopOnLastSlide:true,
		    
		  }) 
    </script>
@endsection
