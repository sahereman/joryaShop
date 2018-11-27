@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Personal Center-account information' : '个人中心-账户信息')
@section('content')
    <div class="User_center_edit User_center">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('users.edit', ['user' => $user->id]) }}">@lang('basic.users.Account_information')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="UserInfo_content">
                <div class="UserInfo_content_title">
                    <p>@lang('basic.users.Edit_account_information')</p>
                </div>
                <div class="edit_content">
                    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}" enctype="multipart/form-data" id="img_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">

                        <ul>
                            <li class="user_header_img">
                                <span>@lang('basic.users.User_profile_picture')</span>
                                <div class="user_Avatar">
                                    <img src="{{ $user->avatar_url }}" width="80">
                                    <input type="file" name="avatar" value="{{ $user->avatar_url }}" data-url="{{ route('image.preview') }}" id="upload_head" onchange="imgChange(this)">
                                </div>
                                <img src="{{ asset('img/photograph.png') }}" class="photograph">
                            </li>
                            <li>
                                <span>@lang('basic.users.Username')</span>
                                <input type="text" name="name" value="{{ $user->name }}" placeholder="@lang('basic.users.Username')" readonly
                                       required>
                            </li>
                            <li>
                                <span>@lang('basic.users.Real_name')</span>
                                <input type="text" name="real_name" value="{{ $user->real_name }}" placeholder="@lang('basic.users.Real_name')">
                            </li>
                            <li class="sexChoose">
                                <span>@lang('basic.users.Gender')</span>
                                <div>
                                    @if($user->gender == null || $user->gender == 'male')
                                        <label>
                                            <input type="radio" name="gender" value="male" class="radioclass" checked>@lang('basic.users.Male')
                                        </label>
                                        <label>
                                            <input type="radio" name="gender" value="female" class="radioclass">@lang('basic.users.Female')
                                        </label>
                                    @else
                                        <label>
                                            <input type="radio" name="gender" value="male" class="radioclass">@lang('basic.users.Male')
                                        </label>
                                        <label>
                                            <input type="radio" name="gender" value="female" class="radioclass" checked>@lang('basic.users.Female')
                                        </label>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <span>QQ</span>
                                <input type="text" name="qq" value="{{ $user->qq }}" placeholder="@lang('basic.users.Enter_QQ_account')">
                            </li>
                            <li>
                                <span>@lang('basic.users.Wechat')</span>
                                <input type="text" name="wechat" value="{{ $user->wechat }}" placeholder="@lang('basic.users.Enter_WeChat_account')">
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
                                <input type="text" name="facebook" value="{{ $user->facebook }}" placeholder="@lang('basic.users.Enter_your_Facebook_account')">
                            </li>
                            <li>
                                <span>@lang('basic.users.email_address')</span>
                                <input type="email" name="email" value="{{ $user->email }}" placeholder="@lang('basic.users.Enter_email_address')"
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
                        <button type="submit">@lang('basic.users.Save')</button>
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
	            var url = $(obj).attr("data-url")
	            if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1 || filePath.indexOf("jpeg")!=-1 || filePath.indexOf("gif")!=-1 || filePath.indexOf("bmp")!=-1){
	                $(".fileerrorTip").html("").hide();
	                var arr=filePath.split('\\');
	                var fileName=arr[arr.length-1];
	                $(".showFileName").html(fileName);
	                upLoadBtnSwitch = 1;
                    UpLoadImg(url);
	            }else{
	                $(".showFileName").html("");
	                layer.open({
					  title: "@lang('app.Prompt')",
					  content: "@lang('app.picture_type_error')",
					  btn: "@lang('app.determine')"
					});     
	                upLoadBtnSwitch = 0;
	                return false 
	            }
	        }
	        
	         // 本地图片上传 按钮
	        function UpLoadImg(url){
	            var formData = new FormData();
	            formData.append('image',document.getElementById("upload_head").files[0]);
	            formData.append('_token',"{{ csrf_token() }}");
	            $.ajax({
	                url:url,
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
