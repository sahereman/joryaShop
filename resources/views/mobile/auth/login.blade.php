@extends('layouts.mobile')
@section('title', '登录')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
	<div class="logMain">
		<div class="logoImgBox">
			<img src="{{ asset('static_m/img/logo.png') }}" />
		</div>
	    <form method="POST" action="{{ route('mobile.login.store') }}" class="formBox">
	        {{ csrf_field() }}
			<div class="nameBox">
				<img src="{{ asset('static_m/img/icon_name.png') }}" class="fImg"/>
				<input id="username" type="text" name="username" value="{{ old('username') }}" placeholder="请输入用户名">
				<div class="tipBox">
					@if ($errors->has('name'))
	                    <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
				        <span> {{ $errors->first('name') }}</span>
	                @endif
				</div>	
			</div>
	        <div class="psdBox">
	        	<img src="{{ asset('static_m/img/icon_password.png') }}" class="fImg" />
	        	<input id="password" type="password" name="password" placeholder="请输入密码" >
	        		<div class="tipBox">
					@if ($errors->has('password'))
	                    <img src="{{ asset('static_m/img/icon_tip.png') }}"/>
				        <span> {{ $errors->first('password') }}</span>
	                @endif
				</div>	
	        </div>
	        <button type="submit" class="subBtn">
	            	登录
	        </button>
	    </form>
	    <div class="logJump">
        	<a href="{{ route('mobile.register.show') }}">账号注册></a>
        	<span>|</span>
        	<a href="{{ route('mobile.reset.sms.show') }}">忘记密码></a>
        </div>
        <div class="downBox">
        	——— 卓雅美业有限公司  ———
        </div>
	</div>	

   



    {{--如果需要引入子视图--}}
    {{--@include('layouts._footer')--}}
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
