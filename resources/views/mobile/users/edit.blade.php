@extends('layouts.mobile')
@section('title', '信息修改')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <div class="headerBar" style="border: none;">
		<img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
		<span>个人信息</span>
	</div>
	<div class="editUser">
		<div class="editUserHead">
			<div class="editUserHeadBox">
				<img src="{{ asset('static_m/img/icon_Headportrait3.png') }}"/>
			</div>
			<p>点击修改头像</p>
		</div>
		<div class="editUserMain">
			<div class="editUserItem">
				<label>用户名</label>
				<input type="text" name="" id="" value="胡巴" />
			</div>
			<div class="editUserItem">
				<label>真实姓名</label>
				<input type="text" name="" id="" value="谭某某" />
			</div>
			<div class="editUserItem">
				<label>性别</label>
				<div class="radioBox">
					<input type="radio" name="sex" id="male" value=""/>
					<span></span>
					<label for="male">男</label>
				</div>
				<div class="radioBox">
					<input type="radio" name="sex" id="female" value="" />
					<span></span>
					<label for="female">女</label>
				</div>
				
			</div>
			<div class="editUserItem">
				<label>QQ</label>
				<input type="text" name="" id="" value="15254544554" />
			</div>
			<div class="editUserItem editUserItemLast">
				<label>微信</label>
				<input type="text" name="" id="" value="454545545" />
			</div>
		</div>
		<button class="doneBtn">保存</button>
	</div>

@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
