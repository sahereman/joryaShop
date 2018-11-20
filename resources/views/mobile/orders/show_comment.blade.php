@extends('layouts.mobile')
@section('title', '查看评价')
@section('content')
    <div class="headerBar fixHeader">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>查看评价</span>
	</div>
	<div class="showCommentBox commentBox">
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
		<div class="commentDetail">
			<div class="comUser">
				<img src="{{ asset('static_m/img/icon_Headportrait3.png') }}" class="userHead"/>
				<span>谭某某</span>
				<div class="starBox">
				  <img src="{{ asset('static_m/img/icon_Starsup.png') }}" />
				  <img src="{{ asset('static_m/img/icon_Starsup.png') }}" />
				  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
				  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" />
				  <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}" /> 
				</div>
			</div>
			<div class="comSku">
				<span>尺寸:1.8cm</span>
				<span>颜色:深棕色</span>
			</div>
			<div class="comCon">
				送货快，包装好，品质好，喜欢的妹子可以下单了~
			</div>
			<div class="comPicture">
				<img src="{{ asset('static_m/img/Advancedcustomization_02.png') }}"/>
				<img src="{{ asset('static_m/img/Advancedcustomization_02.png') }}"/>
			</div>
			<div class="comDate">
				2018-10-11
			</div>
		</div>
	</div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
