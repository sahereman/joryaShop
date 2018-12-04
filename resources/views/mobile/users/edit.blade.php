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
        <div class="editUserMain">
            <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}"
                  enctype="multipart/form-data" id="img_form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="editUserHead">
                    <div class="editUserHeadBox">
                        <img src="{{ $user->avatar_url }}"/>
                        <input type="file" name="avatar" value="{{ $user->avatar_url }}"
                               data-url="{{ route('image.preview') }}" id="upload_head"
                               onchange="imgChange(this)">
                    </div>
                    <p>点击修改头像</p>
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Username')</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                           placeholder="@lang('basic.users.Username')" readonly
                           required>
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Real_name')</label>
                    <input type="text" name="real_name" value="{{ $user->real_name }}"
                           placeholder="@lang('basic.users.Real_name')">
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Gender')</label>
                    @if($user->gender == null || $user->gender == 'male')
                        <div class="radioBox">
                            <input type="radio" name="gender" value="male" id="male" class="radioclass" checked>
                            <span></span>
                            <label for="male">@lang('basic.users.Male')</label>
                        </div>
                        <div class="radioBox">
                            <input type="radio" name="gender" value="female" id="female" class="radioclass"/>
                            <span></span>
                            <label for="female">@lang('basic.users.Female')</label>
                        </div>
                    @else
                        <div class="radioBox">
                            <input type="radio" name="gender" value="male" id="male" class="radioclass">
                            <span></span>
                            <label for="male">@lang('basic.users.Male')</label>
                        </div>
                        <div class="radioBox">
                            <input type="radio" name="gender" value="female" id="female" class="radioclass" checked/>
                            <span></span>
                            <label for="female">@lang('basic.users.Female')</label>
                        </div>
                    @endif
                </div>
                <div class="editUserItem">
                    <label>QQ</label>
                    <input type="text" name="qq" value="{{ $user->qq }}"
                           placeholder="@lang('basic.users.Enter_QQ_account')">
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Wechat')</label>
                    <input type="text" name="wechat" value="{{ $user->wechat }}"
                           placeholder="@lang('basic.users.Enter_WeChat_account')">
                </div>
                <div class="editUserItem">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="{{ $user->facebook }}"
                           placeholder="@lang('basic.users.Enter_your_Facebook_account')">
                </div>
                <div class="editUserItem editUserItemLast">
                    <label>@lang('basic.users.email_address')</label>
                    <input type="text" name="email" value="{{ $user->email }}"
                           placeholder="@lang('basic.users.Enter_email_address')">
                </div>
                <button type="submit" class="doneBtn">@lang('basic.users.Save')</button>
            </form>
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
            });
        });
        // 图片上传入口按钮 input[type=file]值发生改变时触发
        function imgChange(obj) {
            var filePath = $(obj).val();
            var url = $(obj).attr("data-url")
            if (filePath.indexOf("jpg") != -1 || filePath.indexOf("png") != -1 || filePath.indexOf("jpeg") != -1 || filePath.indexOf("gif") != -1 || filePath.indexOf("bmp") != -1) {
                $(".fileerrorTip").html("").hide();
                var arr = filePath.split('\\');
                var fileName = arr[arr.length - 1];
                $(".showFileName").html(fileName);
                upLoadBtnSwitch = 1;
                UpLoadImg(url);
            } else {
                $(".showFileName").html("");
                layer.open({
                    title: "@lang('app.Prompt')",
                    content: "@lang('app.picture_type_error')",
                    btn: "@lang('app.determine')"
                });
                upLoadBtnSwitch = 0;
                return false;
            }
        }

        // 本地图片上传 按钮
        function UpLoadImg(url) {
            var formData = new FormData();
            formData.append('image', document.getElementById("upload_head").files[0]);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                url: url,
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
                processData: false,//必须false才会自动加上正确的Content-Type
                type: 'post',
                success: function (data) {
                    $(".user_Avatar img").attr('src', data.preview);
                }, error: function (e) {
                    console.log(e);
                }
            });
        }
    </script>
@endsection
