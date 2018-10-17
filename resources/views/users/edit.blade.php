@extends('layouts.app')
@section('title', '个人中心-账户信息')
@section('content')
@include('common.error')
<div class="User_center_edit User_center">
	<div class="m-wrapper">
		<div>
	 		<p class="Crumbs">
	 			<a href="{{ route('root') }}">首页</a>
	 			<span>></span>
	 			<a href="{{ route('users.home') }}">个人中心</a>
	 			<span>></span>
	 			<a href="{{ route('users.edit',['user' => $user->id]) }}">账户信息</a>
	 		</p>
	 	</div>
	 	<!--左侧导航栏-->
	 	@include('users._left_navigation')
	 	<!--右侧内容-->
	 	<div class="UserInfo_content">
	 		<div class="UserInfo_content_title">
	 			<p>编辑账户信息</p>
	 		</div>
	 		<div class="edit_content">
	 			<form method="POST" action="{{ route('users.update',$user->id) }}" enctype="multipart/form-data">
			        {{ csrf_field() }}
			        <input type="hidden" name="_method" value="PUT">
			
			        <ul>
			        	<li class="user_header_img">
			        		<span>头像</span>
			        		<div class="user_Avatar">
			        			<img src="{{$user->avatar_url}}" width="80">
			                    <input type="file" name="avatar" value="{{$user->avatar}}" id="upload_head">
			        		</div>
			        		<img src="{{ asset('img/photograph.png') }}" class="photograph">
			        	</li>
			        	<li>
			        		<span>用户名</span>
			        		<input type="text" name="name" value="{{ $user->name }}" placeholder="用户名用于登录" required>
			        	</li>
			        	<li>
			        		<span>真实姓名</span>
			        		<input type="text" name="real_name" placeholder="输入真实姓名">
			        	</li>
			        	<li class="sexChoose">
			        		<span>性别</span>
			        		<div>
			        			<label>
			        			    <input type="radio" name="sex" class="radioclass" checked>男
			        			</label>
			        			<label>
			        				<input type="radio" name="sex" class="radioclass">女
			        			</label>
			        		</div>
			        	</li>
			        	<li>
			        		<span>QQ</span>
			        		<input type="text" name="qq" placeholder="输入QQ账号">
			        	</li>
			        	<li>
			        		<span>微信</span>
			        		<input type="text" name="weixin" placeholder="输入微信账号">
			        	</li>
			        	<!--<li>
			        		<span>手机号</span>
			        		<input type="text" name="tel" value="13061295254">
			        	</li>-->
			        	<li>
			        		<span>Facebook</span>
			        		<input type="text" name="facebook" placeholder="输入Facebook账号">
			        	</li>
			        	<li>
			        		<span>邮箱</span>
			        		<input type="email" name="email" value="{{ $user->email }}" placeholder="邮箱可用于登录" required>
			        	</li>
			        	<!--<li>
			        		<span>密码</span>
			        		<input type="password" name="password" value="{{ $user->password }}">
			        	</li>
			        	<li>
			        		<label>确认密码</label>
			                <input type="password" name="password_confirmation" value="{{ $user->password }}">
			        	</li>-->
			        </ul>
			        <button type="submit">保存</button>
			    </form>
	 		</div>
	 	</div>
	</div>
</div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	$(".navigation_left ul li").removeClass("active");
            $(".account_info").addClass("active");
            $('.user_Avatar img').on('click',function(){
            	 $('#upload_head').click();
		    });
		    $(".photograph").on('click',function(){
		    	$('#upload_head').click();
		    })
        });
    </script>
@endsection