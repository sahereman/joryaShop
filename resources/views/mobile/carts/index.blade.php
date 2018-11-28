@extends('layouts.mobile')
@section('title', '购物车')
@section('content')
   <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>购物车</span>
	</div>
	<div class="cartsBox">
		<div class="cartsCon">
			@for($i = 0; $i < 5; $i++)
				<div class="cartItem">
					123
				</div>
			@endfor
		</div>
		<div class="cartsTotle">
			123
		</div>
	</div>


    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
		$(".itemsG").addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Select_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
    </script>
@endsection
