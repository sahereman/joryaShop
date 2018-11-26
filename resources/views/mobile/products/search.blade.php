@extends('layouts.mobile')
@section('title', '商品列表')
@section('content')
	<div class="goodsListBox">
		<div class="goodsListHead">
			<div class="goodsListSearch">
				<img src="{{ asset('static_m/img/icon_backtop.png') }}"  onclick="javascript:history.back(-1);"/>
				<div class="goodsListHeadBox">
					<img src="{{ asset('static_m/img/icon_search3.png') }}" class="searchImg"/>
					<input type="text" name="" id="ipt" value="" />
					<div class="searchCon">
						<span>卷发</span>
						<img src="{{ asset('static_m/img/icon_searchclosed.png') }}"/>
					</div>
				</div>
			</div>
			<div class="goodsListFillter">
				<div class="zonghe fillterItem">
					综合
					<div class="liftingBox">
						<span class="up">▴</span>
						<span class="down">▾</span>
					</div>
				</div>
				<div class="fillterItem">
					销量
					<span></span>
				</div>
				<div class="fillterItem">
					价格
					<div class="liftingBox">
						<span class="up">▴</span>
						<span class="down">▾</span>
					</div>
				</div>
				<div class="fillterItem">
					人气
					<div class="liftingBox">
						<span class="up">▴</span>
						<span class="down">▾</span>
					</div>
				</div>
				<div class="dropDownBox" name="isPull">
					<div>
						综合排序
					</div>
					<div>
						新品优先
					</div>
					<div>
						评论由高到低
					</div>
				</div>
			</div>
		</div>
		<div class="goodsListMain">
			@for($i = 0; $i< 14; $i++)
				<div class="goodsListItem">
					<img src="{{ asset('static_m/img/blockImg.png') }}" alt="" />
					<div class="goodsItemBlock">
						<div class="goodsBlockName">
							糖果色片染十足立体感
						</div>
						<div class="goodsBlockPrice">
							￥129
						</div>
					</div>
				</div>
			@endfor
			
		</div>
	</div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".zonghe").on("click",function(){
        	if($(".dropDownBox").attr("name")=="isPull"){
        		$(".dropDownBox").attr("name","pull");
        		$(".dropDownBox").slideDown();
        		$(".zonghe .liftingBox .up").css("display","block");
        		$(".zonghe .liftingBox .down").css("display","none");
        	}else if($(".dropDownBox").attr("name")=="pull"){
        		$(".dropDownBox").attr("name","isPull");
        		$(".dropDownBox").slideUp();
        		$(".zonghe .liftingBox .up").css("display","none");
        		$(".zonghe .liftingBox .down").css("display","block");
        	}
        });
        $(".goodsListFillter .fillterItem").on("click",function(){
        	$(".goodsListFillter div").removeClass("goodsFillterActive");
        	$(this).addClass("goodsFillterActive");
        });
        $(".dropDownBox div").on("click",function(){
        	$('.dropDownBox div').removeClass("goodsFillterActive");
        	$(this).addClass("goodsFillterActive");
        });
        $("#ipt").on("focus",function(){
        	window.location.href = "{{route('mobile.locale.search')}}";
        });
        $(".goodsListItem").on('click',function(){
        	window.location.href = "{{route('mobile.products.show',60)}}";
        });
        
    </script>
@endsection
