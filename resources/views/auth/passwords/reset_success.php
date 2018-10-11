@extends('layouts.app')
@section('title', '找回密码')

@section('content')
<div class="reset_psw">
	<div class="m-wrapper">
		<div class="reset_content">
			<p class="reset_title">
				<img src="{{ asset('img/reset_psw.png') }}">
				找回密码
			</p>
            <div class="success_content">
            	<img src="{{ asset('img/reset_success.png') }}">
            	<p>新密码已设置成功</p>
            	<a href="{{ route('root') }}">立即登录</a>
            </div>
	    </div>
    </div>
</div>
@endsection