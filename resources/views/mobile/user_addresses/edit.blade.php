@extends('layouts.mobile')
@section('title', '地址编辑')
@section('content')
    <div class="headerBar">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
		<span>编辑收货地址</span>
	</div>
	<div class="addAdsBox">
		<form action="" method="post" class="addAdsForm">
			<div class="addAdsItem">
				<label class="must">收货人</label>
				<input type="text" name="" id="" value="谭某某"/>
			</div>
			<div class="addAdsItem">
				<label class="must">手机号码</label>
				<input type="text" name="" id="" value="14545454545"/>
			</div>
			<div class="addAdsItem" style="border:none;">
				<label class="must">详细地址</label>
				<input type="text" name="" id="" value="青岛市市北区"/>
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
