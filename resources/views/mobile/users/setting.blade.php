@extends('layouts.mobile')
@section('title', '设置')
@section('content')
    {{--填充页面内容--}}
	<div class="headerBar">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"/>
		<span>设置</span>
	</div>
	<div class="setBox">
		<div class="setMain">
			<a href="{{ route('mobile.users.edit',Auth::id()) }}">
				<div class="setItem">
					<span>个人信息</span>
					<div>
						<img src="{{ asset('static_m/img/icon_Headportrait3.png') }}" class="userImg"/>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
			<a href="">
				<div class="setItem">
					<span>邮箱更改</span>
					<div>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
			<a href="">
				<div class="setItem">
					<span>手机号</span>
					<div>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
			<a href="{{ route('mobile.users.password',Auth::id()) }}">
				<div class="setItem">
					<span>修改密码</span>
					<div>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
			<a href="{{ route('mobile.user_addresses.index') }}">
				<div class="setItem">
					<span>收货地址</span>
					<div>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
			<a href="">
				<div class="setItem setItemF">
					<span>facebook</span>
					<div>
						<img src="{{ asset('static_m/img/icon_more.png') }}" class="moreImg"/>
					</div>
				</div>
			</a>
		</div>
		<button class="exitLog">退出登录</button>
	</div>
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".backImg").on("click",function(){
        	window.location.href = "{{route('mobile.users.home')}}";
        });
    </script>
@endsection
