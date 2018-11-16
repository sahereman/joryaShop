@extends('layouts.mobile')
@section('title', '我的收藏')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
		<span>我的收藏</span>
	</div>
	<div class="favBox">
		@for($i = 0;$i < 3;$i++)
			<div class="favItem">
				<img src="{{ asset('static_m/img/blockImg.png') }}"/>
				<div class="favDetail">
					<div class="goodsName">
						卓业美业长直假发片卓业美业长直假发片
					</div>
					<div class="goodsPri">
						<div>
							<span class="realPri">￥520.00</span>
							<s>￥1800.00</s>
						</div>
						<img src="{{ asset('static_m/img/icon_ShoppingCart2.png') }}"/>
					</div>
				</div>
			</div>
		@endfor	
	</div>
	<div class="editFav">
		@for($i = 0;$i < 3;$i++)
			<div class="favItem">
				<label class="favItemLab">
					<input type="checkbox" name="" id="" value="" />
					<span></span>
				</label>
				<img src="{{ asset('static_m/img/blockImg.png') }}"/>
				<div class="favDetail">
					<div class="goodsName">
						卓业美业长直假发片卓业美业长直假发片
					</div>
					<div class="goodsPri">
						<div>
							<span class="realPri">￥520.00</span>
							<s>￥1800.00</s>
						</div>
					</div>
				</div>
			</div>
		@endfor	
	</div>
	<div class="editFixt">
		<span class="editBtn">编辑</span>
		<span class="cancelBtn">取消关注</span>
	</div>

    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".editBtn").on("click",function(){
        	if($(this).html() == "编辑"){
        		$(this).html("返回");	
	        	$(".favBox").css("display","none");
	        	$(".editFav").css("display","block");
        	}else if($(this).html() == "返回"){
        		$(this).html("编辑");	
	        	$(".favBox").css("display","block");
	        	$(".editFav").css("display","none");
        	}
        	
        });
        $(".cancelBtn").on("click",function(){
        	if($(this).css("background") == "#bc8c61"){
        		$(this).css("background","#dcdcdc");
	        	$(".editBtn").css("background","#bc8c61");	
	        	$(".favBox").css("display","block");
	        	$(".editFav").css("display","none");
        	}
        	
        });
        $(".favItemLab").on("click",function(){
        	if($(this).children("input").prop("checked") == true){
        		$(".cancelBtn").css("background","#bc8c61");
        		$(".cancelBtn").on("click",function(){
        			layer.open({
					  anim: 'up'
					  ,content: '确定要取消关注此商品吗？'
					  ,btn: ['确认', '取消']
					});
        		});
        	}else{
        		var iptArr = $(".favItemLab input");
        		var eqArr = [];
        		for(var i = 0;i<iptArr.length;i++){
        			var iptItem = iptArr[i].checked;
        			eqArr.push(iptItem);
        			var index = $.inArray(true, eqArr);   //结果：index=1
        		}
        		if(index == -1){
        			$(".cancelBtn").css("background","#dcdcdc");
        		}
        	}
        	
        });
    </script>
@endsection
