@extends('layouts.mobile')
@section('title', '创建评价')
@section('content')
	<div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>发布评价</span>
	</div>
	<div class="commentBox">
		<div class="ordDetail">
			<img src="{{ asset('static_m/img/blockImg.png') }}"/>
			<div>
				<div class="ordDetailName">卓业美业长直假发片卓业美业长直假发片卓业美业长直假发片卓业美业长直假发片</div>
				<div>
					<span>数量：2 &nbsp;&nbsp;</span>
					
					<span>颜色：黄</span>
				</div>
				<div class="ordDetailPri">￥500.00</div>
			</div>
		</div>
		<div class="commentCon">
			<textarea name="" rows="3" cols="" placeholder="请输入您的评价"></textarea>
			<div class="goodspicture">
				<div class="goodsItem">
					<img src="{{ asset('static_m/img/blockImg.png') }}" class="goodsItemPicImg"/>
					<img src="{{ asset('static_m/img/icon_Closed.png') }}" class="closeImg"/>
				</div>
				<div class="goodsChoice">
					<img src="{{ asset('static_m/img/icon_Additive.png') }}" />
					<span>5/1</span>
				</div>
			</div>
		</div>
		<div class="commentScore">
			<div class="commentScoreTitle">商品评分</div>
			<div class="commentScoreMain">
				<div class="commentScoreItem">
					<span>质量满意</span>
					<div class="star starOne"> 
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" /> 
					</div>
				</div>
				<div class="commentScoreItem">
					<span class="must">服务态度</span>
					<div class="star starTwo"> 
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />  
					</div>
				</div>
				<div class="commentScoreItem">
					<span>物流服务</span>
					<div class="star starS"> 
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
					  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />  
					</div>
				</div>
			</div>
		</div>
		<div class="fixedBtn">
			<button>发布</button>
		</div>
	</div>
@endsection


@section('scriptsAfterJs')
	<script type="text/javascript">
        //页面单独JS写这里
        $(function () { 
		    var wjx_k = "{{ asset('static_m/img/icon_starsExtinguish.png') }}"; 
		    var wjx_s = "{{ asset('static_m/img/icon_Starsup.png') }}"; 
		    //prevAll获取元素前面的兄弟节点，nextAll获取元素后面的所有兄弟节点 
		    //end 方法；返回上一层 
		    //siblings 其它的兄弟节点 
		    //绑定事件 
		    $(".star img").on("mouseenter", function () { 
		      $(this).attr("src",wjx_s).prevAll().attr("src",wjx_s).end().nextAll().attr("src",wjx_k); 
		    }).on("click", function () { 
		      $(this).addClass("active").siblings().removeClass("active") 
		    }); 
		  });
	</script>
@endsection
