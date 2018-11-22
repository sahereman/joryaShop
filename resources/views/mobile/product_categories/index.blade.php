@extends('layouts.mobile')
@section('title', '商品分类')
@section('content')
	<div class="cgeBox">
		<div class="cgeHead">
			<a href="{{route('mobile.locale.search')}}" class="cgeHeadSearch">
				<img src="{{ asset('static_m/img/icon_search3.png') }}" />
				<input type="text" name="" id="" value="" placeholder="搜你想搜" readonly="readonly"/>
			</a>
		</div>
		<div class="cgeMain">
			<div class="cgeMainLeft">
				<div class="cgeActive">直发</div>
				@for($i = 0;$i<19; $i++)
					<div>卷发</div>
				@endfor	
			</div>
			<div class="cgeMainRight">
				@for($j = 0;$j<4; $j++)
					<div class="cgeMainRightItem">
						<div class="cgeMainRightItemTitle">
							<span class="line"></span>
						   	<span class="txt">假发</span>
						   	<span class="line"></span>
						</div>
						<div class="cgeItemProBox">
							@for($i = 0;$i<9; $i++)
								<div class="cgeItemPro">
									<img src="{{ asset('static_m/img/blockImg.png') }}" />
									<p>齐刘海女生假发齐刘海女生假发</p>
								</div>
							@endfor	
							
						</div>
					</div>
				@endfor
			</div>
		</div>
	</div>

    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
		$(".itemsL").addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Select_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Unchecked_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
		
		$(".cgeMainLeft div").on("click",function(){
			$(".cgeMainLeft div").removeClass("cgeActive");
			$(this).addClass("cgeActive");
		});
    </script>
@endsection
