@extends('layouts.mobile')
@section('title', '卓雅美业')
@section('content')
	<div class="main">
		<div class="searchBox">
			<div class="searchCon">
				<img src="{{ asset('static_m/img/Unchecked_search.png') }}"/>
				<input type="text" name="" id="" value="" placeholder="搜索商品，供12351款好货"/>
			</div>
		</div>
		<!-- Swiper -->
	    <div class="swiper-container swiper-containerL" >		
		  <div class="swiper-wrapper">
		  	@for($i = 0; $i < 5; $i++)
		    <div class="swiper-slide swiper-slideL">
		    	<img src="{{ asset('static_m/img/banner.png') }}" class="main-img">
		    </div>
		    @endfor
		  </div>
		  <div class="swiper-pagination" id="pagination"></div>
		</div>
		<div class="proBox">
			<div class="new_pro">
				<div class="new_title">
					<img src="{{ asset('static_m/img/Title_New.png') }}"/>
					<span class="new_name">新品首发</span>
				</div>
				<div class="swiper-container swiper-containers">
				    <div class="swiper-wrapper">
				    @for($i = 0; $i < 6; $i++)
				      <div class="swiper-slide swiper-slides">
				      	<img src="{{ asset('static_m/img/new.png') }}"/>
				      	<div class="new_pro_name">糖果色片染 十足立体感糖果色片染 十足立体感</div>
				      	<span class="new_pro_price">￥129</span>
				      </div>
				     @endfor 
				    </div>
				  </div>
			</div>
			<div class="block_trend">
				<div class="block_title">
					<span>时尚趋势</span>
					<a href="#">更多></a>
				</div>
				<img src="{{ asset('static_m/img/theme.png') }}" class="block_theme"/>
				<div class="blockBox">
					@for($i = 0; $i < 6; $i++)
					<div class="blockItem">
						<img src="{{ asset('static_m/img/blockImg.png') }}"/>
						<div class="block_name">糖果色片染立体感十足,糖果色片</div>
						<span class="block_price">￥129</span>
					</div>
					@endfor
				</div>
			</div>
			<div class="block_trend">
				<div class="block_title">
					<span>高级定制</span>
					<a href="#">更多></a>
				</div>
				<img src="{{ asset('static_m/img/Advancedcustomization.png') }}" class="block_theme"/>
				<div class="cusBox">
					<div class="cusItemO">
						<img src="{{ asset('static_m/img/Advancedcustomization_02.png') }}"/>
					</div>
					<div class="cusItemF">
						<img src="{{ asset('static_m/img/Advancedcustomization_03.png') }}"  />
						<div class="cusItemBox">
							<img src="{{ asset('static_m/img/blockImg.png') }}"/>
							<img src="{{ asset('static_m/img/blockImg.png') }}"/>
						</div>
					</div>
				</div>
				<div class="blockBox">
					@for($i = 0; $i < 6; $i++)
					<div class="blockItem blockItemCus">
						<img src="{{ asset('static_m/img/blockImg.png') }}"/>
						<div class="block_name">糖果色片染</div>
						<span class="block_price">￥129</span>
					</div>
					@endfor
				</div>
			</div>
			<div class="pro_rec ">
				<div class="new_title">
					<img src="{{ asset('static_m/img/Title_Like.png') }}"/>
					<span class="new_name">商品推荐</span>
				</div>
				<div class="recBox">
					@for($i = 0; $i < 6; $i++)
						<div class="recItem">
							<img src="{{ asset('static_m/img/blockImg.png') }}"/>
							<div class="block_name">糖果色片染立体感十足</div>
							<span class="block_price">￥129</span>
						</div>
					@endfor
				</div>
			</div>
		</div>
		{{--如果需要引入子视图--}}
    @include('layouts._footer_mobil')
	</div>
	 
    
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
    var mySwiper = new Swiper('.swiper-containerL',{
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
var swiper = new Swiper('.swiper-containers', {
      slidesPerView: 2.7,
      spaceBetween: 0,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
        
        
    </script>
@endsection