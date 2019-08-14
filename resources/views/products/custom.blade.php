@extends('layouts.app')
@section('content')
    <div class="custom">
        {{-- 价格显示 --}}
        <div class="custom-top">
            <div class="custom-price" data-price="{{ get_current_price($product->price) }}">
                <span>{{ get_global_symbol() }}</span>
                <span class="custom-price-num">{{ get_current_price($product->price) }}</span>
            </div>
        </div>
        {{--标题--}}
        <div class="custom-title">
            <div class="custom-title-left">
                <a href="{{ route('root') }}" class="back-to-product">
                    <i class="iconfont">&#xe603;</i> BACK TO PRODUCT: <span> Custom a New System</span>
                </a>
            </div>
            <div class="custom-title-center">
                <ul>
                    @foreach($custom_attr_types as $key => $custom_attr_type)
                        <li class="{{ $key == 0 ? 'active' : '' }}" >
                            {{--不同的href值对应相同id值得模块--}}
                            {{--这个标号用序号表示，方便js用来计数--}}
                            <a data-href="#tab-{{ $custom_attr_type }}" href="javascript:void (0)">
                                {{-- 这个标号无意义，仅是用来页面显示区分用 --}}
                                <span>{{ $custom_attr_type }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="custom-title-right">
                <button class="previous">PREVIOUS</button>
                <button class="next">NEXT</button>
                <button class="addtocart">ADD TO CART</button>
            </div>
        </div>
        {{--内容--}}
        <div class="customizations-content">
            {{-- 商品默认图片 --}}
            <div class="customizations-img">
                <img src="{{ asset('img/new_pro_2.png') }}" alt="lyricalhair.com">
            </div>
            {{--背景--}}
            <div class="customizations-bg"></div>
            {{--内容列表--}}
            <div class="customizations-slide">
                <h3 class="product-title">CUSTOMIZE</h3>
                @foreach($grouped_custom_attrs as $type => $custom_attrs)
                    {{--不同的href值对应相同id值得模块--}}
                    <div class="customizations-slide-content {{ $type == $custom_attr_types[0] ? 'active' : '' }}"
                         id="tab-{{ $type }}">
                        @guest
                            <input type="hidden" class="addToCartSuccess" value="{{ route('login') }}">
                        @else
                            <input type="hidden" class="addToCartSuccess" value="{{ route('carts.index') }}">
                        @endguest
                        <input type="hidden" value="{{ route('products.custom.store', ['product' => $product->id]) }}" id="addToCartUrl">
                        <ul>
                            @foreach($custom_attrs as $key => $custom_attr)
                                <li class="top-level">
                                    {{-- 是否为必选项 --}}
                                    <h6 class="block-title {{ $custom_attr->is_required ? 'required' : '' }}">
                                        {{--判断该选择项是否为必填,必填为true显示星号--}}
                                        @if($custom_attr->is_required)
                                            <span class="red iconfont">&#xe613;</span>
                                        @endif
                                        {{--后面的标号为了区分没有实际意义--}}
                                        <span class="select-title">{{ $custom_attr->name }}</span>
                                        {{-- 显示用户已选择额内容 --}}
                                        <span class="selected-option"></span>
                                        <span class="opener iconfont">&#xe60f;</span>
                                    </h6>
                                    <div class="block-content">
                                        <ul class="block-list">
                                            @foreach($custom_attr->values as $custom_attr_value)
                                                <li class="block-list-level">
                                                    <label>
                                                        <input type="radio" value="{{ $custom_attr_value->id }}" name="{{ $custom_attr->name }}">
                                                        {{--后面的标号为了区分没有实际意义--}}
                                                        <span class="val-text">{{ $custom_attr_value->value }}</span>
                                                        <span class="price red"
                                                              data-price="{{ get_current_price($custom_attr_value->delta_price) }}">
                                                            <i>{{ get_global_currency() }}</i>
                                                            <i class="price_num">{{ get_current_price($custom_attr_value->delta_price) }}</i>
                                                        </span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        {{-- 验证当前选项卡的必选项是否已经全部被选中 --}}
        function isALLChoosed(domId) {
            var notSelect = true;
            // domId是当前活跃的选项卡的ID
            // 当前dom节点面板下的所有必填项的集合
            var requiredAll = $(domId).find(".required");
            $.each(requiredAll, function (i, n) {
                if ($(n).find(".selected-option").html() == "") {
                    notSelect = false
                    return notSelect;
                }
            });
            // 判断是否填写完成如果填写完成这再次点击此选项卡时不需要判断当前页面是否已经填写完成
            if (notSelect == true) {
                $(".custom-title-center").find("a[data-href='"+ domId +"']").addClass("Completed");
            }
            return notSelect;
        }
        {{-- 点击选项卡切换对应的页面内容 --}}
         $(".custom-title-center").on("click", "a", function () {
             var _that = $(this);
            //  页面切换的时候进行验证，验证用户是否已选择了所有的必选项,如果已经选择了则进行下一步，如果不可以则提示
            var nowactiveDom = $(".custom-title-center").find("li.active").find("a").attr("data-href"), // 当前活跃的选项卡的ID
                activeDom = $(this).attr("data-href"); // 即将要切换到的选项卡的ID
            var getResult = isALLChoosed(nowactiveDom);
            if($(this).hasClass("Completed")!=true){
            //    如果点击的选项卡不存在已完成的clas则需要判断当前页面是否已经填写完成，
            //    如果包含已经完成的标志，则直接跳转即可
                if (getResult == false) {
                    layer.alert("Please select the required option!!");
                    return;
                }
                // else {
                // //    如果当前页面的所有的必填选项都已经选择完成则所有的选择拼接为一个小字符串
                //     var getCheckedVal = $(nowactiveDom).find(".selected-option");
                //     $.each(getCheckedVal,function (i,n) {
                //         if($(n).text()!=""){
                //             console.log($(n).text());
                //         }
                //     })
                // };
            }
            var total_tabs = $(".custom-title-center").find("li").length;
            var active_num = $(this).parent("li").index();
            $(".custom-title-center").find("li").removeClass("active");
            $(this).parents("li").addClass("active");
            $(".customizations-slide").find(".customizations-slide-content").removeClass("active")
            $(activeDom).addClass("active");
            // 判断当前页是否是第一页，如果不是第一页则上一页按钮不显示
            if (active_num != 0) {
                $(".previous").css("display", "inline-block");
                $(".next").css("display", "inline-block");
                $(".addtocart").css("display", "none");
            }
            if (active_num == total_tabs - 1) {
                // 添加购物车按钮显示
                $(".addtocart").css("display", "inline-block");
                $(".next").css("display", "none");
            }
        });
        // 点击下一页按钮
        $(".next").on("click", function () {
            var index_active = $(".custom-title-center").find("li.active").index() + 1;
            var choose_index_arr = $(".custom-title-center").find("li");
            var activeDom = $(".custom-title-center").find("li.active").find("a").attr("data-href");
            var getResult = isALLChoosed(activeDom);
            if (getResult == false) {
                layer.alert("Please select the required option!!");
                return;
            }
            $(".custom-title-center").find("li").removeClass("active");
            if (index_active == 1) {
                $(".previous").css("display", "inline-block");
                $(".next").css("display", "inline-block");
                $(".addtocart").css("display", "none");
            }
            if (index_active == choose_index_arr.length - 1) {
                $(this).css("display", "none");
                $(".addtocart").css("display", "inline-block");
            }
            $.each(choose_index_arr, function (i, n) {
                if (i == index_active) {
                    $(n).addClass("active");
                    var activeDomNext = $(n).find("a").attr("data-href");
                    $(".customizations-slide").find(".customizations-slide-content").removeClass("active");
                    $(activeDomNext).addClass("active");
                }
            });
        });
        // 点击上一页按钮
        $(".previous").on("click", function () {
            var index_active_pre = $(".custom-title-center").find("li.active").index() - 1;
            var choose_index_arr_pre = $(".custom-title-center").find("li");
            $(".custom-title-center").find("li").removeClass("active");
            if (index_active_pre == 0) {
                $(".previous").css("display", "none");
                $(".next").css("display", "inline-block");
                $(".addtocart").css("display", "none");
            }
            if (index_active_pre != choose_index_arr_pre.length - 3) {
                $(".addtocart").css("display", "none");
                $(".next").css("display", "inline-block");
            }
            $.each(choose_index_arr_pre, function (i, n) {
                if (i == index_active_pre) {
                    $(n).addClass("active");
                    var activeDomNext_pre = $(n).find("a").attr("data-href");
                    $(".customizations-slide").find(".customizations-slide-content").removeClass("active");
                    $(activeDomNext_pre).addClass("active");
                }
            })
        });
        // 点击添加到购物车
        var dataString = "";  // 用于存储数据提交的字符串
        $(".addtocart").on("click",function () {
            // 点击添加到购物车同时判断最后一页的内容中的必选项是否已经选择完成
            var index_active = $(".custom-title-center").find("li.active").index() + 1;
            var choose_index_arr = $(".custom-title-center").find("li");
            var activeDom = $(".custom-title-center").find("li.active").find("a").attr("data-href");
            var getResult = isALLChoosed(activeDom);
            if (getResult == false) {
                layer.alert("Please select the required option!!");
                return;
            }
            var getCheckedVal = $(".customizations-slide").find(".selected-option");
                $.each(getCheckedVal,function (i,n) {
                    if($(n).text()!=""){
                        dataString+=$(n).attr("data-id") + ","
                    }
                });
            dataString = dataString.substring(0,dataString.length-1);
            var data = {
                _token: "{{ csrf_token() }}",
                custom_attr_value_ids: dataString
            };
            $.ajax({
                type: "post",
                url: $("#addToCartUrl").val(),
                data: data,
                success: function (data) {
                    window.location.href = $(".addToCartSuccess").val();
                },
                error: function (err) {
                    if (err.status == 422) {
                        var arr = [];
                        var dataobj = err.responseJSON.errors;
                        for (let i in dataobj) {
                            arr.push(dataobj[i]); //属性
                        }
                        layer.msg(arr[0][0]);
                    }
                },
            });
        });
        //数据计算方法
        function float_multiply_by_100(float) {
            float = String(float);
            // float = float.toString();
            var index_of_dec_point = float.indexOf('.');
            if (index_of_dec_point == -1) {
                float += '00';
            } else {
                var float_splitted = float.split('.');
                var dec_length = float_splitted[1].length;
                if (dec_length == 1) {
                    float_splitted[1] += '0';
                } else if (dec_length > 2) {
                    float_splitted[1] = float_splitted[1].substring(0, 1);
                }
                float = float_splitted.join('');
            }
            return Number(float);
        }
        function js_number_format(number) {
            number = String(number);
            var index_of_dec_point = number.indexOf('.');
            if (index_of_dec_point == -1) {
                number += '.00';
            } else {
                var number_splitted = number.split('.');
                var dec_length = number_splitted[1].length;
                if (dec_length == 1) {
                    number += '0';
                } else if (dec_length > 2) {
                    number_splitted[1] = number_splitted[1].substring(0, 2);
                    number = number_splitted.join('.');
                }
            }
            return number;
        }
        {{--点击title出现一级列表--}}
        $(".customizations-slide").on("click", ".block-title", function () {
            var isOpened = $(this).hasClass("opened");
            if (isOpened) {
                $(this).removeClass("opened");
                $(".customizations-slide").find(".block-content").slideUp();
            } else {
                $(".customizations-slide").find(".block-title").removeClass("opened");
                $(".customizations-slide").find(".block-content").slideUp();
                $(this).addClass("opened");
                $(this).parents("li").find(".block-content").slideDown();
            }
        });
        // 用于价格记录的计算变量参数
        var _CHOOSEPRICE = 0,
                _INITIALPRICE = float_multiply_by_100($(".custom-price").attr("data-price")),  // 页面的初始价格
                _NEWPRICE = float_multiply_by_100($(".custom-price").attr("data-price")),    // 新的价格数
                _CHOOSEPRICEARR = [],            // 用来存储所有选择的价格的数组
                _PRECHOOSENAME = "";  // 记录上一次选择的

        // 点击一级分类出现二级分类内容
        $(".customizations-slide").on("click", "input[type=radio]", function () {
            var chil_ul = $(this).parent("label").parent(".block-list-level").find("ul"),
                    chooseText = '';
            // 判断是否有二级选项存在
            if (chil_ul.length != 0) {
                // 如果有二级选项存在，选中一级选项时出现二级选项列表
                if ($(this).prop("checked")) {
                    $(".customizations-slide").find(".block-list-level-2").slideUp();
                    $(this).parents(".block-list-level").find(".block-list-level-2").slideDown();
                } else {
                    $(".customizations-slide").find(".block-list-level-2").slideDown();
                    $(this).parents(".block-list-level").find(".block-list-level-2").slideUp();
                }
                // 如果二级选项存在，则将选中的选项的value值赋值给每个大类中的option中
                if ($(this).parent("label").attr("data-code") == "level-2") {
                    chooseText = $(this).val();
                }
            } else {
                // 如果二级选项不存在，则将选中的选项的value值赋值给每个大类中的option中
                chooseText = $(this).parent("label").find(".val-text").text();
            }
            // 将选中的选项的值赋值给option
            $(this).parents(".top-level").find(".selected-option").text(chooseText);
            $(this).parents(".top-level").find(".selected-option").attr("data-id", $(this).val());
            // 判断是否有价格参数
            var isExist = false;
            if ($(this).parent("label").find(".price").length != 0) {
                var _inputThat = $(this);
                // var old_price = js_number_format(Math.imul(float_multiply_by_100(origin_price), 12) / 1000);
                if(_CHOOSEPRICEARR.length == 0) {
                    _PRECHOOSENAME = $(this).prop("name");
                    // _CHOOSEPRICE = Number($(this).parent("label").find(".price").attr("data-price"));
                    _CHOOSEPRICE = float_multiply_by_100($(this).parent("label").find(".price").attr("data-price"));
                    _NEWPRICE = _CHOOSEPRICE + _NEWPRICE;
                    _CHOOSEPRICEARR.push({"name": $(this).prop("name"),"price": _CHOOSEPRICE})
                }else {
                    for(var i in _CHOOSEPRICEARR){
                        if(_CHOOSEPRICEARR[i].name == _inputThat.prop("name")) {
                            isExist = true;
                            _NEWPRICE = _NEWPRICE - _CHOOSEPRICEARR[i].price;
                            // _CHOOSEPRICE = Number(_inputThat.parent("label").find(".price").attr("data-price"));
                            _CHOOSEPRICE = float_multiply_by_100(_inputThat.parent("label").find(".price").attr("data-price"));
                            _CHOOSEPRICEARR[i].price = _CHOOSEPRICE;
                            _NEWPRICE = _CHOOSEPRICE + _NEWPRICE;
                        }
                    }
                    if(!isExist) {
                        _PRECHOOSENAME = _inputThat.prop("name");
                        // _CHOOSEPRICE = Number(_inputThat.parent("label").find(".price").attr("data-price"));
                        _CHOOSEPRICE = float_multiply_by_100(_inputThat.parent("label").find(".price").attr("data-price"));
                        _NEWPRICE = _CHOOSEPRICE + _NEWPRICE;
                        _CHOOSEPRICEARR.push({"name": _inputThat.prop("name"),"price": _CHOOSEPRICE})
                    }
                }
            }
            $(".custom-price-num").text(js_number_format(_NEWPRICE/100));
            $(".custom-price").attr("data-price", js_number_format(_NEWPRICE/100));
        });
    </script>
@endsection
