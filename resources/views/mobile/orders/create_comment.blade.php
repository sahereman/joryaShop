@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Create an evaluation' : '创建评价')
@section('content')
    <div class="headerBar fixHeader">
        @if(!is_wechat_browser())
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('order.Publish an evaluation')</span>
        @endif
    </div>
    <div class="commentBox">
        <form method="POST" action="{{ route('orders.store_comment', ['order' => $order->id]) }}"
              enctype="multipart/form-data" id="creat_comment_form">
            {{ csrf_field() }}
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="img-box full photoList dis_n" id="imgupup">
                <div class="input_content up_img clear_fix">
                    <div id="div_imglook">
                        <div id="div_imgfile"></div>
                    </div>
                </div>
            </div>
            @foreach($order->snapshot as $order_item)
                <div class="ordDetail">
                    <a href="{{ route('products.show', $order_item['sku']['product']['id']) }}">
                        <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                    </a>
                    <div>
                        <div class="ordDetailName">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</div>
                        <div>
                        <span>
                            @lang('order.Quantity')：{{ $order_item['number'] }}
                            &nbsp;&nbsp;
                        </span>
                        <span>
                            <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                            </a>
                        </span>
                        </div>
                        <div class="ordDetailPri">
                            <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>
                            <span>{{ $order_item['price'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="commentCon">
                <textarea name="content[{{ $order_item['id'] }}]" maxlength="200" rows="3" cols=""
                          placeholder="@lang('product.comments.Please enter a product evaluation of less than 200 words')"></textarea>
                    <!--上传图片-->
                    <div class="goodspicture" code="{{ $order_item['id'] }}">
                        <!--<div class="goodsItem">
                        <img src="{{ asset('static_m/img/blockImg.png') }}" class="goodsItemPicImg"/>
                        <img src="{{ asset('static_m/img/icon_Closed.png') }}" class="closeImg"/>
                    </div>-->
                        <div class="goodsChoice" code="{{ $order_item['id'] }}">
                            <img src="{{ asset('static_m/img/icon_Additive.png') }}"/>
                            <span class="img_nums">@lang('order.No more than 5 sheets')</span>
                        </div>
                        <input id="imgPath-[{{ $order_item['id'] }}]" type="hidden"
                               name="photos[{{ $order_item['id'] }}]" value="">
                    </div>
                </div>
                <div class="commentScore">
                    <div class="commentScoreTitle"> @lang('order.Product Rating')</div>
                    <div class="commentScoreMain">
                        <div class="commentScoreItem">
                            <span class="must">@lang('product.composite_index')</span>
                            <div class="star starOne">
                                <img code="1" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input type="radio" class="dis_ni" code="1" id="rate1-1_{{ $order_item['id'] }}"
                                       name="composite_index[{{ $order_item['id'] }}]" value="1"/>
                                <img code="2" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input type="radio" class="dis_ni" code="2" id="rate2-1_{{ $order_item['id'] }}"
                                       name="composite_index[{{ $order_item['id'] }}]" value="2"/>
                                <img code="3" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input type="radio" class="dis_ni" code="3" id="rate3-1_{{ $order_item['id'] }}"
                                       name="composite_index[{{ $order_item['id'] }}]" value="3"/>
                                <img code="4" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input type="radio" class="dis_ni" code="4" id="rate4-1_{{ $order_item['id'] }}"
                                       name="composite_index[{{ $order_item['id'] }}]" value="4"/>
                                <img code="5" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input type="radio" class="dis_ni" code="5" id="rate5-1_{{ $order_item['id'] }}"
                                       name="composite_index[{{ $order_item['id'] }}]" value="5"/>
                            </div>
                        </div>
                        <div class="commentScoreItem">
                            <span class="must">@lang('product.description_index')</span>
                            <div class="star starTwo">
                                <img code="1" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="1" type="radio" class="dis_ni" id="rate1-2_{{ $order_item['id'] }}"
                                       name="description_index[{{ $order_item['id'] }}]" value="1"/>
                                <img code="2" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="2" type="radio" class="dis_ni" id="rate2-2_{{ $order_item['id'] }}"
                                       name="description_index[{{ $order_item['id'] }}]" value="2"/>
                                <img code="3" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="3" type="radio" class="dis_ni" id="rate3-2_{{ $order_item['id'] }}"
                                       name="description_index[{{ $order_item['id'] }}]" value="3"/>
                                <img code="4" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="4" type="radio" class="dis_ni" id="rate4-2_{{ $order_item['id'] }}"
                                       name="description_index[{{ $order_item['id'] }}]" value="4"/>
                                <img code="5" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="5" type="radio" class="dis_ni" id="rate5-2_{{ $order_item['id'] }}"
                                       name="description_index[{{ $order_item['id'] }}]" value="5"/>
                            </div>
                        </div>
                        <div class="commentScoreItem">
                            <span class="must">@lang('order.Logistics services')</span>
                            <div class="star starS">
                                <img code="1" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="1" type="radio" class="dis_ni" id="rate1-3_{{ $order_item['id'] }}"
                                       name="shipment_index[{{ $order_item['id'] }}]" value="1"/>
                                <img code="2" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="2" type="radio" class="dis_ni" id="rate2-3_{{ $order_item['id'] }}"
                                       name="shipment_index[{{ $order_item['id'] }}]" value="2"/>
                                <img code="3" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="3" type="radio" class="dis_ni" id="rate3-3_{{ $order_item['id'] }}"
                                       name="shipment_index[{{ $order_item['id'] }}]" value="3"/>
                                <img code="4" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="4" type="radio" class="dis_ni" id="rate4-3_{{ $order_item['id'] }}"
                                       name="shipment_index[{{ $order_item['id'] }}]" value="4"/>
                                <img code="5" src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                                <input code="5" type="radio" class="dis_ni" id="rate5-3_{{ $order_item['id'] }}"
                                       name="shipment_index[{{ $order_item['id'] }}]" value="5"/>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="fixedBtn">
                <button class="submint_comment">@lang('order.Release')</button>
            </div>
        </form>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(function () {
            var which_click = 0;
            var set_finish = false;
            var wjx_k = "{{ asset('static_m/img/icon_starsExtinguish.png') }}";
            var wjx_s = "{{ asset('static_m/img/icon_Starsup.png') }}";
            //prevAll获取元素前面的兄弟节点，nextAll获取元素后面的所有兄弟节点
            //end 方法；返回上一层
            //siblings 其它的兄弟节点
            //绑定事件
            $(".star img").on("mouseenter", function () {
                $(this).attr("src", wjx_s).prevAll().attr("src", wjx_s).end().nextAll().attr("src", wjx_k);
            }).on("click", function () {
                $(this).addClass("active").siblings().removeClass("active");
                $(this).parents(".star").find("input[code='" + $(this).attr("code") + "']").attr("checked", true);
            });
            //点击上传图片
            $(".creat_comment_form").on("click", ".goodsChoice",function (){
                var had_evaImg = $(this).parents(".goodspicture").find(".goodsItem");
                if (had_evaImg.length < 5) {
                    $("#div_imgfile").trigger("click");
                    which_click = $(this).attr("code");
                } else {
                    layer.open({
                        content: "@lang('product.Upload up to 5 image')",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                }
            });
            //删除图片
            $(document).on("click", ".closeImg", function () {
                var code = $(this).parents(".goodsItem").attr("code");
                $($(this).parents('.goodsItem')).remove();
                $("#div_imglook").find(".lookimg[num=" + code + "]").remove();
            });
            //点击提交
            $(".submint_comment").on("click", function () {
                event.preventDefault();
                var five_star_one, five_star_two, five_star_three, star_status, textarea_status;
                $.each($(".commentScoreMain"), function (i, n) {
                    five_star_one = $(n).find(".starOne").find("input:checked");
                    five_star_two = $(n).find(".starTwo").find("input:checked");
                    five_star_three = $(n).find(".starS").find("input:checked");
                    if (five_star_one.length == 1 && five_star_two.length == 1 && five_star_three.length == 1) {
                        star_status = true;
                    } else {
                        layer.open({
                            content: "@lang('product.Please select a Star')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        star_status = false;
                        return star_status;
                    }
                });
                $.each($(".commentCon"), function (i, n) {
                    if ($(n).find("textarea").val() == "" || $(n).find("textarea").val() == null) {
                        textarea_status = false;
                        layer.open({
                            content: "@lang('product.Please fill in the evaluation content')！",
                            skin: 'msg',
                            time: 2, //2秒后自动关闭
                        });
                        return textarea_status;
                    } else {
                        if ($(n).find("textarea").val().length < 3) {
                            textarea_status = false;
                            layer.open({
                                content: "@lang('product.Evaluation content is not less than 15 words')！",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                        } else if ($(n).find("textarea").val().length >= 199) {
                            textarea_status = false;
                            layer.open({
                                content: "@lang('product.The content of the evaluation should not exceed 200 words')！",
                                skin: 'msg',
                                time: 2, //2秒后自动关闭
                            });
                        }
                        else {
                            textarea_status = true;
                        }
                    }
                });
                set_path();
                if (set_finish == true && textarea_status == true && star_status == true) {
                    $("#creat_comment_form").submit();
                }
            });
            // 本地图片上传 按钮
            function UpLoadImg(obj, number) {
                var formData = new FormData();
                formData.append('image', obj);
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: "{{ route('comment_image.upload') }}",
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
                    processData: false,//必须false才会自动加上正确的Content-Type
                    type: 'post',
                    success: function (data) {
                        var html = "<div class='goodsItem' code='" + number + "' data-path='" + data.path + "'>" +
                                "<img src='" + data.preview + "' class='goodsItemPicImg'/>" +
                                "<img src='{{ asset('static_m/img/icon_Closed.png') }}' class='closeImg'/>" +
                                "</div>";
                        $(".goodsChoice[code='" + which_click + "']").before(html);
                    }, error: function (e) {
                        console.log(e);
                    }
                });
            }

            var IMG_LENGTH = 10;//图片最大1MB
            var IMG_MAXCOUNT = 5;//最多选中图片张数
            var UP_IMGCOUNT = 0;//上传图片张数记录
            //打开文件选择对话框
            $("#div_imgfile").click(function () {
                /*if ($(".lookimg").length >= IMG_MAXCOUNT) {
                    layer.open({
                        content: "一次最多上传" + IMG_MAXCOUNT + "张图片",
                        skin: 'msg',
                        time: 2, //2秒后自动关闭
                    });
                    return;
                }*/
                var sUserAgent = navigator.userAgent.toLowerCase();
                var _CRE_FILE = document.createElement("input");
                if ($(".imgfile").length <= $(".lookimg").length) {//个数不足则新创建对象
                    _CRE_FILE.setAttribute("type", "file");
                    _CRE_FILE.setAttribute("name", "image");
                    _CRE_FILE.setAttribute("class", "imgfile");
                    if (sUserAgent.match(/Android/i) == "android") {
                        _CRE_FILE.setAttribute("capture", "camera");
                    }
//                    _CRE_FILE.setAttribute("capture", "camera");
                    _CRE_FILE.setAttribute("accept", "image/png,image/jpg,image/jpeg");
                    _CRE_FILE.setAttribute("id", "{{ $order_item['id'] }}");
                    _CRE_FILE.setAttribute("data-url", "{{ route('comment_image.upload') }}");
                    _CRE_FILE.setAttribute("num", UP_IMGCOUNT);//记录此对象对应的编号

                    $("#div_imgfile").nextAll().remove();     //上传头像只能传一张照片

                    $("#div_imgfile").after(_CRE_FILE);
                }
                else { //否则获取最后未使用对象
                    _CRE_FILE = $(".imgfile").eq(0).get(0);
                }
                return $(_CRE_FILE).click();//打开对象选择框
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
                    //      IMG_DELBTN.innerHTML = "移除";
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
                    var number = UP_IMGCOUNT;
                    UpLoadImg(file, number);
                }
            });

            //删除选中图片
            $("#imgupup").on("click", ".lookimg_delBtn", function () {
                var that = $(this).attr("data-attid");
                var num = $(this).parents(".lookimg").attr("num")
                if (eidt == "eidt") {
                    if (num == "") {
                        visitorsRegis.fn.delVisitrecordAttIpad(that);
                    }
                }
                $(".imgfile[num=" + $(this).parent().attr("num") + "]").remove();//移除图片file
                $(this).parent().remove();//移除图片显示
            });
            function set_path() {
                var order_list = $(".commentCon");
                $.each(order_list, function (i, n) {
                    var img_list = $(n).find(".goodsItem");
                    var path_url = "";
                    $.each(img_list, function (a, b) {
                        path_url += $(b).attr("data-path") + ","
                    });
                    path_url = path_url.substring(0, path_url.length - 1);
                    $(n).find("input[name='photos[" + $(n).find(".goodspicture").attr("code") + "]']").val(path_url);
                });
                set_finish = true;
                return set_finish;
            }
        });
    </script>
@endsection
