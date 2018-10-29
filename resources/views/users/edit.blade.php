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
                    <a href="{{ route('users.edit', $user->id) }}">账户信息</a>
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
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" id="img_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">

                        <ul>
                            <li class="user_header_img">
                                <span>头像</span>
                                <div class="user_Avatar">
                                    <img src="{{ $user->avatar_url }}" width="80">
                                    <input type="file" name="avatar" value="{{ $user->avatar_url }}" id="upload_head" onchange="imgChange(this)">

                                </div>
                                <img src="{{ asset('img/photograph.png') }}" class="photograph">
                            </li>
                            <li>
                                <span>用户名</span>
                                <input type="text" name="name" value="{{ $user->name }}" placeholder="用户名用于登录" readonly
                                       required>
                            </li>
                            <li>
                                <span>真实姓名</span>
                                <input type="text" name="real_name" value="{{ $user->real_name }}" placeholder="输入真实姓名">
                            </li>
                            <li class="sexChoose">
                                <span>性别</span>
                                <div>
                                    @if($user->gender == null || $user->gender == 'male')
                                        <label>
                                            <input type="radio" name="gender" value="male" class="radioclass" checked>男
                                        </label>
                                        <label>
                                            <input type="radio" name="gender" value="female" class="radioclass">女
                                        </label>
                                    @else
                                        <label>
                                            <input type="radio" name="gender" value="male" class="radioclass">男
                                        </label>
                                        <label>
                                            <input type="radio" name="gender" value="female" class="radioclass" checked>女
                                        </label>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <span>QQ</span>
                                <input type="text" name="qq" value="{{ $user->qq }}" placeholder="输入QQ账号">
                            </li>
                            <li>
                                <span>微信</span>
                                <input type="text" name="wechat" value="{{ $user->wechat }}" placeholder="输入微信账号">
                            </li>
                            <!--<li>
                                <span>国家|地区码</span>
                                <input type="text" name="country_code" value="86">
                            </li>-->
                            <!--<li>
                                <span>手机号</span>
                                <input type="text" name="phone" value="13061295254">
                            </li>-->
                            <li>
                                <span>Facebook</span>
                                <input type="text" name="facebook" value="{{ $user->facebook }}" placeholder="输入Facebook账号">
                            </li>
                            <li>
                                <span>邮箱</span>
                                <input type="email" name="email" value="{{ $user->email }}" placeholder="邮箱可用于登录"
                                       required>
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
            $('.user_Avatar img').on('click', function () {
                $("#upload_head").click();
            });
            $(".photograph").on('click', function () {
                $("#upload_head").click();
            })
        });
            // 图片上传入口按钮 input[type=file]值发生改变时触发
	        function imgChange(obj){
	            var filePath=$(obj).val();
	            if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("jpeg")!=-1 || filePath.indexOf("gif")!=-1 || filePath.indexOf("bmp")!=-1){
	                $(".fileerrorTip").html("").hide();
	                var arr=filePath.split('\\');
	                var fileName=arr[arr.length-1];
	                $(".showFileName").html(fileName);
	                upLoadBtnSwitch = 1;
                    UpLoadImg();
	            }else{
	                $(".showFileName").html("");
	                $(".fileerrorTip").html("您未选择图片，或者您上传文件格式有误！（当前支持图片格式：jpg，png，jpeg，gif，bmp）").show();
	                upLoadBtnSwitch = 0;
	                return false 
	            }
	        }
	        
	         // 本地图片上传 按钮
	        function UpLoadImg(){
	            var formData = new FormData();
	            formData.append('image',document.getElementById("upload_head").files[0]);
	            $.ajax({
	                url:"{{ route('image.preview') }}",
	                data:formData,
	                dataType:'json',
	                cache: false,  
	                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
	                processData: false,//必须false才会自动加上正确的Content-Type
	                type:'post',            
	                success:function(data){
	                   $(".user_Avatar img").attr('src',data.preview);
	                },error:function(e){
	                    console.log(e);
	                }
	            });
	        }
    </script>
@endsection
