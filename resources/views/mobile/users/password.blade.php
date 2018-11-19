@extends('layouts.mobile')
@section('title', '修改密码')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <div class="headerBar">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>修改密码</span>
	</div>
	<div class="editPsdBox">
		<div class="editPsdMain">
			<div class="editPsdItem">
				 <label>原密码</label>
				 <input type="text" name="" id="" value="" placeholder="请输入原密码"/>
			</div>
			<div class="editPsdItem">
				 <label>新密码</label>
				 <input type="text" name="" id="" value="" placeholder="请输入新密码" class="changePsd"/>
				 <img src="{{ asset('static_m/img/icon_eyesopen.png') }}" class="clickEye"/>
			</div>
			<div class="editPsdItem">
				 <label>确认密码</label>
				 <input type="text" name="" id="" value="" placeholder="请再次输入密码"/>
			</div>
		</div>
		<button class="doneBtn">完成</button>
	</div>



    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".clickEye").on("click",function(){
        	if($(this).attr("src") == "{{ asset('static_m/img/icon_eyesclose.png') }}"){
        		$(this).attr("src","{{ asset('static_m/img/icon_eyesopen.png') }}");
        		$(".changePsd").attr("type","text");
        	}else{
        		$(this).attr("src","{{ asset('static_m/img/icon_eyesclose.png') }}");
        		$(".changePsd").attr("type","password");
        	}
        });
        
    </script>
@endsection
