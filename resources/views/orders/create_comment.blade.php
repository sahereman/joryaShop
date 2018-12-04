@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Personal Center-my order' : '个人中心-我的订单')
@section('content')
    <div class="evaluate_commont">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
                    <span>></span>
                    <a href="{{ route('orders.show', ['order' => $order->id]) }}">@lang('basic.users.The_order_details')</a>
                    <span>></span>
                    <a href="javascript:void(0)">@lang('basic.users.feedback')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="comment_content">
                <form method="POST" action="{{ route('orders.store_comment', ['order' => $order->id]) }}"
                      enctype="multipart/form-data" id="creat_comment_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    @foreach($order->snapshot as $order_item)
                        <div class="evaluation_order">
                            <table>
                                <thead>
                                <th></th>
                                <th>@lang('product.comments.commodity')</th>
                                <th>@lang('product.comments.specification')</th>
                                <th>@lang('product.comments.Unit Price')</th>
                                <th>@lang('product.comments.Quantity')</th>
                                <th>@lang('product.comments.Subtotal')</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-pro-img">
                                        <a href="">
                                            <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                                        </a>
                                    </td>
                                    <td class="col-pro-info">
                                        <p class="p-info">
                                            <a class="commodity_description"
                                               href="{{ route('products.show', ['product' => $order_item['sku']['product']['id']]) }}">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</a>
                                        </p>
                                    </td>
                                    <td class="col-pro-speci">
                                        <p class="p-info">
                                            <a class="specifications"
                                               href="{{ route('products.show', ['product' => $order_item['sku']['product']['id']]) }}">{{App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}</a>
                                        </p>
                                    </td>
                                    <td class="col-price">
                                        <p class="p-price">
                                            <span>{{ $order->currency == "USD" ? '&#36;' : '&#165;' }}</span>
                                            <span>{{ $order_item['price'] }}</span>
                                        </p>
                                    </td>
                                    <td class="col-quty">
                                        <p>{{ $order_item['number'] }}</p>
                                    </td>
                                    <td class="col-pay">
                                        <p>
                                            <span>{{ $order->currency == "USD" ? '&#36;' : '&#165;' }}</span>
                                            <span>{{ bcmul($order_item['price'], $order_item['number'], 2) }}</span>
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="evaluation_content">
                                <p class="evaluat_title">@lang('product.comments.Please fill in your valuable suggestions')</p>
                                {{--**
                                    * 注：循环是请把下面的所有的{{ $order_item->id }}切换成对应循环的下标值，即第几个否则评价的五星会失效
                                    * 切记！！！！
                                    * --}}
                                <div class="five_star_evaluation">
                                    <div class="five_star_one star_area">
                                        <p>
                                            <i>*</i>
                                            <span>@lang('product.composite_index')</span>
                                        </p>
                                        <div class="starability-basic">

                                            <input type="radio" id="rate5-1_{{ $order_item['id'] }}"
                                                   name="composite_index[{{ $order_item['id'] }}]" value="5"/>
                                            <label for="rate5-1_{{ $order_item['id'] }}" title="Amazing"></label>

                                            <input type="radio" id="rate4-1_{{ $order_item['id'] }}"
                                                   name="composite_index[{{ $order_item['id'] }}]" value="4"/>
                                            <label for="rate4-1_{{ $order_item['id'] }}" title="Very good"></label>

                                            <input type="radio" id="rate3-1_{{ $order_item['id'] }}"
                                                   name="composite_index[{{ $order_item['id'] }}]" value="3"/>
                                            <label for="rate3-1_{{ $order_item['id'] }}" title="Average"></label>

                                            <input type="radio" id="rate2-1_{{ $order_item['id'] }}"
                                                   name="composite_index[{{ $order_item['id'] }}]" value="2"/>
                                            <label for="rate2-1_{{ $order_item['id'] }}" title="Not good"></label>

                                            <input type="radio" id="rate1-1_{{ $order_item['id'] }}"
                                                   name="composite_index[{{ $order_item['id'] }}]" value="1"/>
                                            <label for="rate1-1_{{ $order_item['id'] }}" title="Terrible"></label>
                                        </div>
                                    </div>
                                    <div class="five_star_two star_area">
                                        <p>
                                            <i>*</i>
                                            <span>@lang('product.description_index')</span>
                                        </p>
                                        <div class="starability-basic">
                                            <input type="radio" id="rate5-2_{{ $order_item['id'] }}"
                                                   name="description_index[{{ $order_item['id'] }}]" value="5"/>
                                            <label for="rate5-2_{{ $order_item['id'] }}" title="Amazing"></label>

                                            <input type="radio" id="rate4-2_{{ $order_item['id'] }}"
                                                   name="description_index[{{ $order_item['id'] }}]" value="4"/>
                                            <label for="rate4-2_{{ $order_item['id'] }}" title="Very good"></label>

                                            <input type="radio" id="rate3-2_{{ $order_item['id'] }}"
                                                   name="description_index[{{ $order_item['id'] }}]" value="3"/>
                                            <label for="rate3-2_{{ $order_item['id'] }}" title="Average"></label>

                                            <input type="radio" id="rate2-2_{{ $order_item['id'] }}"
                                                   name="description_index[{{ $order_item['id'] }}]" value="2"/>
                                            <label for="rate2-2_{{ $order_item['id'] }}" title="Not good"></label>

                                            <input type="radio" id="rate1-2_{{ $order_item['id'] }}"
                                                   name="description_index[{{ $order_item['id'] }}]" value="1"/>
                                            <label for="rate1-2_{{ $order_item['id'] }}" title="Terrible"></label>
                                        </div>
                                    </div>
                                    <div class="five_star_three star_area">
                                        <p>
                                            <i>*</i>
                                            <span>@lang('product.shipping_index')</span>
                                        </p>
                                        <div class="starability-basic">
                                            <input type="radio" id="rate5-3_{{ $order_item['id'] }}"
                                                   name="shipment_index[{{ $order_item['id'] }}]" value="5"/>
                                            <label for="rate5-3_{{ $order_item['id'] }}" title="Amazing"></label>

                                            <input type="radio" id="rate4-3_{{ $order_item['id'] }}"
                                                   name="shipment_index[{{ $order_item['id'] }}]" value="4"/>
                                            <label for="rate4-3_{{ $order_item['id'] }}" title="Very good"></label>

                                            <input type="radio" id="rate3-3_{{ $order_item['id'] }}"
                                                   name="shipment_index[{{ $order_item['id'] }}]" value="3"/>
                                            <label for="rate3-3_{{ $order_item['id'] }}" title="Average"></label>

                                            <input type="radio" id="rate2-3_{{ $order_item['id'] }}"
                                                   name="shipment_index[{{ $order_item['id'] }}]" value="2"/>
                                            <label for="rate2-3_{{ $order_item['id'] }}" title="Not good"></label>

                                            <input type="radio" id="rate1-3_{{ $order_item['id'] }}"
                                                   name="shipment_index[{{ $order_item['id'] }}]" value="1"/>
                                            <label for="rate1-3_{{ $order_item['id'] }}" title="Terrible"></label>
                                        </div>
                                    </div>
                                </div>
                                <textarea name="content[{{ $order_item['id'] }}]" maxlength="200"
                                          placeholder="@lang('product.comments.Please enter a product evaluation of less than 200 words')"></textarea>
                                <!--<input id="imgPath-[{{ $order_item['id'] }}]" type="hidden" name="photos[{{ $order_item['id'] }}]"-->

                                <div class="picture_area">
                                    <input id="imgPath-[{{ $order_item['id'] }}]" type="hidden"
                                           name="photos[{{ $order_item['id'] }}]" value="">
                                    <p>
                                        <i>*</i>
                                        <span>@lang('app.upload image')</span>
                                    </p>
                                    <div class="pictures" code="{{ $order_item['id'] }}">
                                        <div class="pictures_btn" code="{{ $order_item['id'] }}">
                                            <img src="{{ asset('img/pic_upload.png') }}">
                                            <input type="file" name="image" value=""
                                                   data-url="{{ route('comment_image.upload') }}" id="{{ $order_item['id'] }}"
                                                   onchange="imgChange(this)"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="sub_evaluation_area">
                        <a class="sub_evaluation">@lang('app.submit')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var which_click = 0;
        var set_finish = false;
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".my_order").addClass("active");
            $(".order-group").on('click', '.col-delete', function () {
                $(".order_delete").show();
            });
            $(".pictures_btn").on("click", function () {
                which_click = $(this).attr("code");
                $(document).on("click", ".pictures_btn input", function () {
                })
            })
        });
        // 图片上传入口按钮 input[type=file]值发生改变时触发
        function imgChange(obj) {
            var filePath = $(obj).val();
            var url = $(obj).attr("data-url");
            if ($(obj).parents('.pictures').find('.img_path').length >= 5) {
                layer.msg("@lang('product.Upload up to 5 image')!");
                return false;
            }
            if (filePath.indexOf("jpg") != -1 || filePath.indexOf("png") != -1 || filePath.indexOf("jpeg") != -1 || filePath.indexOf("gif") != -1 || filePath.indexOf("bmp") != -1) {
                var arr = filePath.split('\\');
                var fileName = arr[arr.length - 1];
                upLoadBtnSwitch = 1;
                UpLoadImg(obj, url);
            } else {
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
        function UpLoadImg(obj, url) {
            var formData = new FormData();
            formData.append('image', $(obj)[0].files[0]);
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
                    var html = "<div class='img_path' data-path='" + data.path + "'>" +
                            "<img src='" + data.preview + "' data-path='" + data.path + "'>" +
                            "<img class='close_btn' src='{{ asset('img/error_fork.png') }}' >" +
                            "</div>";
                    $(".pictures[code='" + which_click + "']").append(html);
                }, error: function (e) {
                    console.log(e);
                }
            });
        }


        //表单提交
        $(".sub_evaluation").on("click", function () {
            var five_star_one, five_star_two, five_star_three, star_status, textarea_status;
            $.each($(".evaluation_content"), function (i, n) {
                five_star_one = $(n).find(".five_star_one").find("input:checked");
                five_star_two = $(n).find(".five_star_two").find("input:checked");
                five_star_three = $(n).find(".five_star_three").find("input:checked");
                if (five_star_one.length == 1 && five_star_two.length == 1 && five_star_three.length == 1) {
                    star_status = true;
                } else {
                    layer.msg("@lang('product.Please select a Star')！");
                    star_status = false;
                    return star_status;
                }
                if ($(n).find("textarea").val() == "" || $(n).find("textarea").val() == null) {
                    textarea_status = false;
                    layer.msg("@lang('product.Please fill in the evaluation content')！");
                    return textarea_status;
                } else {
                    console.log();
                    if ($(n).find("textarea").val().length < 3) {
                        textarea_status = false;
                        layer.msg("@lang('product.Evaluation content is not less than 15 words')！");
                    } else if ($(n).find("textarea").val().length >= 199) {
                        textarea_status = false;
                        layer.msg("@lang('product.The content of the evaluation should not exceed 200 words')！");
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
        })
        function set_path() {
            var order_list = $(".comment_content").find(".evaluation_order .pictures");
            $.each(order_list, function (i, n) {
                var img_list = $(n).find(".img_path");
                var path_url = "";
                $.each(img_list, function (a, b) {
                    path_url += $(b).attr("data-path") + ","
                });
                path_url = path_url.substring(0, path_url.length - 1);
                $(n).parents(".picture_area").find("input[name='photos[" + $(n).attr("code") + "]']").val(path_url);
            });
            set_finish = true;
            return set_finish;
        }
        //删除
        $(document).on("click", ".close_btn", function () {
            $(this).parents('.img_path').remove();
        })
    </script>
@endsection
