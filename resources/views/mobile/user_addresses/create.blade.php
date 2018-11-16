@extends('layouts.mobile')
@section('title', '新增地址')
@section('content')
	<div class="headerBar">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
		<span>新增收货地址</span>
	</div>
	<div class="addAdsBox">
		<form action="" method="post" class="addAdsForm">
			<div class="addAdsItem">
				<label class="must">收货人</label>
				<input type="text" name="" id="" value="" placeholder="请填写收货人"/>
			</div>
			<div class="addAdsItem">
				<label class="must">手机号码</label>
				<input type="text" name="" id="" value="" placeholder="请填写手机号"/>
			</div>
			<div class="addAdsItem" style="border:none;">
				<label class="must">详细地址</label>
				<input type="text" name="" id="" value="" placeholder="请填写详细地址，街道，门牌号等"/>
			</div>
			<button type="submit" class="doneBtn">保存</button>
		</form>
		<div class="defaultBox">
			<label>设为默认地址</label>
			<img src="{{ asset('static_m/img/icon_OFF.png') }}" class="switchBtn"/>
		</div>
	</div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".switchBtn").on("click",function(){
        	if($(this).attr("src") == "{{ asset('static_m/img/icon_OFF.png') }}"){
        		$(this).attr("src","{{ asset('static_m/img/icon_ON.png') }}");
        	}else{
        		$(this).attr("src","{{ asset('static_m/img/icon_OFF.png') }}");
        	}
        });
    </script>
@endsection
