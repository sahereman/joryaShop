@extends('layouts.mobile')
@section('title', (App::isLocale('en') ? 'Information Modification' : '信息修改') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="headerBar fixHeader {{ is_wechat_browser() ? 'height_no' : '' }}" style="border: none;">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.users.Personal information')</span>
    </div>
    <div class="editUser {{ is_wechat_browser() ? 'margin-top_no' : '' }}">
        <div class="editUserMain">
            <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}"
                  enctype="multipart/form-data" id="img_form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="editUserHead">
                    <div class="editUserHeadBox">
                        <img class="user_image" src="{{ $user->avatar_url }}"/>
                        {{--<input type="file" name="avatar" value="{{ $user->avatar_url }}"
                               data-url="{{ route('image.avatar_preview') }}" id="upload_head"
                               onchange="imgChange(this)">--}}
                        <div class="img-box full photoList dis_n" id="imgupup">
                            <div class="input_content up_img clear_fix">
                                <div id="div_imglook">
                                    <div id="div_imgfile"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>@lang('basic.users.Click to modify the Avatar')</p>
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Username')</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                           placeholder="@lang('basic.users.Username')" readonly
                           required>
                </div>
                {{--<div class="editUserItem">
                    <label>@lang('basic.users.Real_name')</label>
                    <input type="text" name="real_name" value="{{ $user->real_name }}"
                           placeholder="@lang('basic.users.Real_name')">
                </div>--}}
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
                    @if($errors->has('qq'))
                        <span> {{ $errors->first('qq') }}</span>
                    @endif
                </div>
                <div class="editUserItem">
                    <label>@lang('basic.users.Wechat')</label>
                    <input type="text" name="wechat" value="{{ $user->wechat }}"
                           placeholder="@lang('basic.users.Enter_WeChat_account')">
                    @if($errors->has('wechat'))
                        <span> {{ $errors->first('wechat') }}</span>
                    @endif
                </div>
                <div class="editUserItem">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="{{ $user->facebook }}"
                           placeholder="@lang('basic.users.Enter_your_Facebook_account')">
                    @if($errors->has('facebook'))
                        <span> {{ $errors->first('facebook') }}</span>
                    @endif
                </div>
                <div class="editUserItem editUserItemLast">
                    <label>@lang('basic.users.email_address')</label>
                    <input type="text" name="email" value="{{ $user->email }}"
                           placeholder="@lang('basic.users.Enter_email_address')">
                    <div class="tipBox">
                        @if($errors->has('email'))
                            <span> {{ $errors->first('email') }}</span>
                        @endif
                    </div>
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
            //点击更换头像
            $(".user_image").on("click", function () {
                $("#div_imgfile").trigger("click");
            })
        });
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
                    $(".user_image").attr('src', data.preview);
                },
                error: function (e) {
                    console.log(e);
                },
            });
        }

        var IMG_LENGTH = 10;//图片最大1MB
        var IMG_MAXCOUNT = 5;//最多选中图片张数
        var UP_IMGCOUNT = 0;//上传图片张数记录
        //打开文件选择对话框
        $("#div_imgfile").click(function () {
            /*if ($(".lookimg").length >= IMG_MAXCOUNT) {
             layer.alert("一次最多上传" + IMG_MAXCOUNT + "张图片");
             return;
             }*/
            var sUserAgent = navigator.userAgent.toLowerCase();
            var _CRE_FILE = document.createElement("input");
            // if ($(".imgfile").length <= $(".lookimg").length) {//个数不足则新创建对象
            _CRE_FILE.setAttribute("type", "file");
            _CRE_FILE.setAttribute("name", "avatar");
            _CRE_FILE.setAttribute("class", "imgfile");
            if (sUserAgent.match(/Android/i) == "android") {
                _CRE_FILE.setAttribute("capture", "camera");
            }
            _CRE_FILE.setAttribute("accept", "image/png,image/jpg,image/jpeg");
            _CRE_FILE.setAttribute("id", "upload_head");
            _CRE_FILE.setAttribute("data-url", "{{ route('image.avatar_preview') }}");
            _CRE_FILE.setAttribute("num", UP_IMGCOUNT);//记录此对象对应的编号

            $("#div_imgfile").nextAll().remove();     //上传头像只能传一张照片

            $("#div_imgfile").after(_CRE_FILE);
            /*} else { //否则获取最后未使用对象
             _CRE_FILE = $(".imgfile").eq(0).get(0);
             }*/
            return $(_CRE_FILE).click();
            //打开对象选择框
        });

        //创建预览图，在动态创建的file元素onchange事件中处理
        $("#imgupup").on("change", ".imgfile", function () {
            if ($(this).val().length > 0) {//判断是否有选中图片
                //判断图片格式是否正确
                var FORMAT = $(this).val().substr($(this).val().length - 3, 3);
                if (FORMAT != "png" && FORMAT != "jpg" && FORMAT != "peg") {
                    layer.open({
                        content: "@lang('basic.users.File format is incorrect')！！！",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return;
                }

                //判断图片是否过大，当前设置1MB
                var file = this.files[0];//获取file文件对象
                if (file.size > (IMG_LENGTH * 1024 * 1024)) {
                    layer.open({
                        content: "@lang('basic.users.Picture size cannot exceed')" + IMG_LENGTH + "MB",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    $(this).val("");
                    return;
                }
                //创建预览外层
                var _prevdiv = document.createElement("div");
                _prevdiv.setAttribute("class", "lookimg");
                //创建内层img对象
                var preview = document.createElement("img");
                $(_prevdiv).append(preview);
                //创建删除按钮
                var IMG_DELBTN = document.createElement("div");
                IMG_DELBTN.setAttribute("class", "lookimg_delBtn");
                // IMG_DELBTN.innerHTML = "移除";
                $(_prevdiv).append(IMG_DELBTN);
                //记录此对象对应编号
                _prevdiv.setAttribute("num", $(this).attr("num"));
                //对象注入界面
                $("#div_imglook").children("div:last").before(_prevdiv);
                UP_IMGCOUNT++;//编号增长防重复
                //预览功能 start
                var reader = new FileReader();//创建读取对象
                reader.onloadend = function () {
                    preview.src = reader.result;//读取加载，将图片编码绑定到元素
                };
                if (file) {//如果对象正确
                    reader.readAsDataURL(file);//获取图片编码
                } else {
                    preview.src = "";//返回空值
                }
                //预览功能 end
                var url = $("#upload_head").attr("data-url");
                UpLoadImg(url);
            }
        });

        //删除选中图片
        $("#imgupup").on("click", ".lookimg_delBtn", function () {
            var that = $(this).attr("data-attid");
            var num = $(this).parents(".lookimg").attr("num");
            if (eidt == "eidt") {
                if (num == "") {
                    visitorsRegis.fn.delVisitrecordAttIpad(that);
                }
            }
            $(".imgfile[num=" + $(this).parent().attr("num") + "]").remove();//移除图片file
            $(this).parent().remove();//移除图片显示
        });
    </script>
@endsection
