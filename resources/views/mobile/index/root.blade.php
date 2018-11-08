@extends('layouts.mobile')
@section('title', '卓雅美业')
@section('content')
	<div class="searchBox">
		<div class="searchCon">
			<img src="{{ asset('static_m/img/Unchecked_search.png') }}"/>
			<input type="text" name="" id="" value="" placeholder="搜索商品，供12351款好货"/>
		</div>
	</div>
	<!-- Swiper -->
  <div class="swiper-container" >		
	  <div class="swiper-wrapper">
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	    <div class="swiper-slide"><img src="{{ asset('static_m/img/banner.png') }}" class="main-img"></div>
	  </div>
	  <div class="swiper-pagination" id="pagination"></div>
	</div>
	 
    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobil')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    var mySwiper = new Swiper('.swiper-container',{
	  slidesPerView : 'auto',
	  centeredSlides : true,
	  watchSlidesProgress: true,
	  pagination : '.swiper-pagination',
	  paginationClickable: true,
      paginationBulletRender: function (index, className) {
			return '<span class="' + className + '"><i></i></span>';
      },
	  onProgress: function(swiper){
        for (var i = 0; i < swiper.slides.length; i++){
          var slide = swiper.slides[i];
          var progress = slide.progress;
		  scale = 1 - Math.min(Math.abs(progress * 0.2), 1);
        
         es = slide.style;
		 es.opacity = 1 - Math.min(Math.abs(progress/2),1);
				es.webkitTransform = es.MsTransform = es.msTransform = es.MozTransform = es.OTransform = es.transform = 'translate3d(0px,0,'+(-Math.abs(progress*150))+'px)';

        }
      },

     onSetTransition: function(swiper, speed) {
      	for (var i = 0; i < swiper.slides.length; i++) {
				es = swiper.slides[i].style;
				es.webkitTransitionDuration = es.MsTransitionDuration = es.msTransitionDuration = es.MozTransitionDuration = es.OTransitionDuration = es.transitionDuration = speed + 'ms';
		}

      }
  });

        
        
    </script>
@endsection