@extends('layouts.mobile')
@section('title', '商品列表')
@section('content')
	<div class="goodsListBox">
		<div class="goodsListHead">
			<img src="{{ asset('static_m/img/icon_backtop.png') }}"  onclick="javascript:history.back(-1);"/>
			<div class="goodsListHeadBox">
				<img src="{{ asset('static_m/img/icon_search3.png') }}" />
				<input type="text" name="" id="ipt" value="" />
			</div>
		</div>
	</div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
