@extends('layouts.app')
@section('content')
    <div class="custom">
        {{-- 价格显示 --}}
        <div class="custom-top">
            <div class="custom-price" data-price="199"><span>US$</span><span class="custom-price-num">199.00</span></div>
        </div>
        {{--标题--}}
        <div class="custom-title">
            <div class="custom-title-left">
                <a href="#" class="back-to-product"><i class="iconfont">&#xe603;</i> BACK TO PRODUCT:<span>Custom a new system</span></a>
            </div>
            <div class="custom-title-center">
                <ul>
                    <?php $_ii=0; while ($_ii++ < 5): ?>
                    @if($_ii == 1)
                        <li class="active">
                    @else
                        <li>
                    @endif
                          {{--不同的href值对应相同id值得模块--}}
                            {{--这个标号用序号表示，方便js用来计数--}}
                          <a data-href="#tab{{ $_ii }}" href="javascript:void (0)">
                              {{-- 这个标号无意义，仅是用来页面显示区分用 --}}
                              <span>BASE{{ $_ii }}</span>
                          </a>
                      </li>
                    <?php endwhile; ?>
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
            {{--背景--}}
            <div class="customizations-bg"></div>
            {{--内容列表--}}
            <div class="customizations-slide">
                <h3 class="product-title">CUSTOMIZE</h3>
                <?php $_ii=0; while ($_ii++ < 5): ?>
                {{--不同的href值对应相同id值得模块--}}
                @if($_ii == 1)
                    <div class="customizations-slide-content active" id="tab{{ $_ii }}">
                @else
                    <div class="customizations-slide-content" id="tab{{ $_ii }}">
                @endif
                        <ul>
                            <?php $_ic=0; while ($_ic++ < 3): ?>
                              <li class="top-level">
                                {{-- 是否为必选项 --}}
                                @if(true)
                                  <h6 class="block-title required">
                                @else
                                  <h6 class="block-title">
                                @endif
                                    {{--判断该选择项是否为必填,必填为true显示星号--}}
                                    @if(true)
                                      <span class="red iconfont">&#xe613;</span>
                                    @endif
                                    {{--后面的标号为了区分没有实际意义--}}
                                    Base size-{{ $_ii }}-{{ $_ic }}
                                    {{-- 显示用户已选择额内容 --}}
                                    <span class="selected-option"></span>
                                    <span class="opener iconfont">&#xe60f;</span>
                                </h6>
                                <div class="block-content">
                                    <ul class="block-list">
                                        <?php $_ia=0; while ($_ia++ < 2): ?>
                                            <li class="block-list-level">
                                                <label>
                                                    <input type="radio" value="size and curvature reference-{{ $_ii }}-{{ $_ic }}-{{ $_ia }}" name="Base size-{{ $_ii }}-{{ $_ic }}">
                                                    {{--后面的标号为了区分没有实际意义--}}
                                                    <span>size and curvature reference-{{ $_ii }}-{{ $_ic }}-{{ $_ia }}</span>
                                                    <span class="price red" data-price="-1{{ $_ic }}{{ $_ia }}"><i>US$</i><i class="price_num">-1{{ $_ic }}{{ $_ia }}</i></span>
                                                </label>
                                                {{-- 判断是否存在二级分类 --}}
                                                @if(true)
                                                    <ul class="block-list-level-2">
                                                        <?php $_ib=0; while ($_ib++ < 3): ?>
                                                            <li class="block-list-level">
                                                                <label data-code="level-2">
                                                                    <input type="radio" value="size and curvature reference-{{ $_ii }}-{{ $_ic }}-{{ $_ia }}-{{ $_ia }}"
                                                                           name=" Base size-{{ $_ii }}-{{ $_ic }}-2">
                                                                    {{--后面的标号为了区分没有实际意义--}}
                                                                    <span>size and curvature reference-{{ $_ii }}-{{ $_ic }}-{{ $_ia }}-{{ $_ia }}</span>
                                                                    <span class="price red" data-price="-1{{ $_ii }}{{ $_ic }}{{ $_ia }}">
                                                                        <i>US$</i><i class="price_num">-1{{ $_ii }}{{ $_ic }}{{ $_ia }}</i>
                                                                    </span>
                                                                </label>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                @endif
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        {{-- 验证当前选项卡的必选项是否已经全部被选中 --}}
        function isALLChoosed(domId){
            // domId是当前活跃的选项卡的ID
            // 当前dom节点面板下的所有必填项的集合
            var requiredAll = $(domId).find(".required");
            $.each(requiredAll,function (i,n) {
                if($(n).find(".selected-option").html() == ""){
                    return false
                }
            });
            return false
        }
        {{-- 点击选项卡切换对应的页面内容 --}}
         $(".custom-title-center").on("click","a",function () {
            //  页面切换的时候进行验证，验证用户是否已选择了所有的必选项,如果已经选择了则进行下一步，如果不可以则提示
            var activeDom = $(this).attr("data-href");
            var getResult = isALLChoosed(activeDom);
            if(getResult == false) {
                alert("请选择必要选项");
                return
            }
            var total_tabs =  $(".custom-title-center").find("li").length;
            var active_num =  $(this).parent("li").index();
            $(".custom-title-center").find("li").removeClass("active");
            $(this).parents("li").addClass("active");
            $(".customizations-slide").find(".customizations-slide-content").removeClass("active")
            $(activeDom).addClass("active");
            // 判断当前页是否是第一页，如果不是第一页则上一页按钮不显示
            if(active_num != 0){
                $(".previous").css("display","inline-block");
                $(".next").css("display","inline-block");
                $(".addtocart").css("display","none")
            }
            if(active_num == total_tabs-1){
            //    添加购物车按钮显示
                $(".addtocart").css("display","inline-block");
                $(".next").css("display","none");
            }
        });
        // 点击下一页按钮
        $(".next").on("click",function () {
            var index_active = $(".custom-title-center").find("li.active").index()+1;
            var choose_index_arr = $(".custom-title-center").find("li");
            var activeDom = $(".custom-title-center").find("li.active").find("a").attr("data-href");
            var getResult = isALLChoosed(activeDom);
            if(getResult == false) {
                alert("请选择必要选项");
                return
            }
            $(".custom-title-center").find("li").removeClass("active");
            if(index_active == 1) {
                $(".previous").css("display","inline-block");
                $(".next").css("display","inline-block");
                $(".addtocart").css("display","none")
            }
            if(index_active == choose_index_arr.length-1) {
                $(this).css("display","none");
                $(".addtocart").css("display","inline-block");
            }
            $.each(choose_index_arr,function (i,n) {
                if(i == index_active) {
                    $(n).addClass("active");
                    var activeDomNext = $(n).find("a").attr("data-href");
                    $(".customizations-slide").find(".customizations-slide-content").removeClass("active")
                    $(activeDomNext).addClass("active");
                }
            })
        });
        // 点击上一页按钮
        $(".previous").on("click",function () {
            var index_active_pre = $(".custom-title-center").find("li.active").index()-1;
            var choose_index_arr_pre = $(".custom-title-center").find("li");
            $(".custom-title-center").find("li").removeClass("active");
            if(index_active_pre == 0) {
                $(".previous").css("display","none");
                $(".next").css("display","inline-block");
                $(".addtocart").css("display","none")
            }
            if(index_active_pre != choose_index_arr_pre.length-3) {
                $(".addtocart").css("display","none");
                $(".next").css("display","inline-block");
            }
            $.each(choose_index_arr_pre,function (i,n) {
                if(i == index_active_pre) {
                    $(n).addClass("active");
                    var activeDomNext_pre = $(n).find("a").attr("data-href");
                    $(".customizations-slide").find(".customizations-slide-content").removeClass("active")
                    $(activeDomNext_pre).addClass("active");
                }
            })
        });
        {{--点击title出现一级列表--}}
        $(".customizations-slide").on("click",".block-title",function () {
          var isOpened = $(this).hasClass("opened");
          if(isOpened){
              $(this).removeClass("opened");
              $(".customizations-slide").find(".block-content").slideUp();
          }else {
              $(".customizations-slide").find(".block-title").removeClass("opened");
              $(".customizations-slide").find(".block-content").slideUp();
              $(this).addClass("opened");
              $(this).parents("li").find(".block-content").slideDown();
          }
        });
    //    用于价格记录的计算变量参数
        var _CHOOSEPRICE = 0,
            _INITIALPRICE = Number($(".custom-price").attr("data-price")),  // 页面的初始价格
            _NEWPRICE = Number($(".custom-price").attr("data-price")),    // 新的价格数
            _PRECHOOSENAME = "";  // 记录上一次选择的

    //    点击一级分类出现二级分类内容
        $(".customizations-slide").on("click","input[type=radio]",function () {
            var chil_ul = $(this).parent("label").parent(".block-list-level").find("ul"),
                chooseText = '';
            // 判断是否有二级选项存在
            if(chil_ul.length != 0) {
                // 如果有二级选项存在，选中一级选项时出现二级选项列表
                if($(this).prop("checked")){
                    $(".customizations-slide").find(".block-list-level-2").slideUp();
                    $(this).parents(".block-list-level").find(".block-list-level-2").slideDown();
                }else {
                    $(".customizations-slide").find(".block-list-level-2").slideDown();
                    $(this).parents(".block-list-level").find(".block-list-level-2").slideUp();
                }
                // 如果二级选项存在，则将选中的选项的value值赋值给每个大类中的option中
                if($(this).parent("label").attr("data-code") == "level-2") {
                    chooseText = $(this).val();
                }
            }else {
                // 如果二级选项不存在，则将选中的选项的value值赋值给每个大类中的option中
                chooseText = $(this).val();
            }
            // 将选中的选项的值赋值给option
            $(this).parents(".top-level").find(".selected-option").text(chooseText);
        //    判断是否有价格参数
            if($(this).parent("label").find(".price").length != 0) {
                if(_PRECHOOSENAME ==  $(this).prop("name") ) {
                    console.log("相同的name值")
                    _NEWPRICE = _CHOOSEPRICE - _INITIALPRICE;
                    _CHOOSEPRICE = Number($(this).parent("label").find(".price").attr("data-price"))
                    _NEWPRICE = _CHOOSEPRICE + _INITIALPRICE;
                }else {
                    _PRECHOOSENAME = $(this).prop("name");
                    _CHOOSEPRICE = Number($(this).parent("label").find(".price").attr("data-price"))
                    _NEWPRICE = _CHOOSEPRICE + _INITIALPRICE;
                }
            }
            $(".custom-price-num").text(_NEWPRICE);
            $(".custom-price").attr("data-price",_NEWPRICE);
        })
    </script>
@endsection
