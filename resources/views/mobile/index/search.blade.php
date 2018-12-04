@extends('layouts.mobile')
@section('title', '商品搜索')
@section('content')
    <div class="seaBox">
        <div class="headerBar">
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>搜索</span>
        </div>
        <div class="searchHead">
            <div class="searchHeadMain">
                <img src="{{ asset('static_m/img/icon_search3.png') }}" class="seaImg"/>
                <input type="text" name="" id="ipt" value="" placeholder="@lang('product.you want to search')"/>
                <img src="{{ asset('static_m/img/icon_closed4.png') }}" class="seaClosed"/>
            </div>
            <span id="search">搜索</span>
        </div>
        <div class="searchMain">
            <div class="searchNow">
                <h5>最近搜索 <span class="delete"></span></h5>
                <div class="searchNowBox search_history">
                    <!--暂无搜索历史-->
                    <div class="Storage">暂无搜索历史</div>
                </div>
            </div>
            <div class="searchNow">
                <h5>热门搜索</h5>
                <div class="searchNowBox">
                    <a href="{{ route('mobile.products.search') . '?query=精品' }}"><span>精品</span></a>
                    <a href="{{ route('mobile.products.search') . '?query=黄色假发' }}"><span>黄色假发</span></a>
                    <a href="{{ route('mobile.products.search') . '?query=中长假发' }}"><span>中长假发</span></a>
                </div>
            </div>
        </div>
        <div class="searchResult">
            {{-- TODO ... search_hint | search_history --}}
            @for($i = 0;$i<4; $i++)
                <div class="searchResultItem">
                    黄色中长假发片
                </div>
            @endfor
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".seaClosed").on("click", function () {
            $("#ipt").val("");
        });
        $("#ipt").on("focus", function () {
            $(".searchMain").css("display", "none");
            $(".searchResult").css("display", "block");
            $(".searchHead span").html("取消");
        });
        $(".searchHead span").on("click", function () {
            if ($(this).html() == "取消") {
                $(".searchMain").css("display", "block");
                $(".searchResult").css("display", "none");
                $(this).html("搜索");
            } else {
                //点击搜索跳转商品列表页面TODO
            }
        });
        $("#ipt").change(function () {
            var val = $(this).val();
            //将输入框的值传入后台，将接口返回的模糊搜索数据渲染到页面TODO
        });
        $(".searchResultItem").on("click", function () {
            window.location.href = "{{route('mobile.products.search')}}";
        });
        
        //设置LocalStorage读写
        var hisTime; //获取搜索时间数组
        var hisItem; //获取搜索内容数组
        var firstKey; //获取最早的1个搜索时间
        function init() {
            hisTime = []; //时间数组置空
            hisItem = []; //内容数组置空
            for(var i = 0; i < localStorage.length; i++) { //数据去重
                if(!isNaN(localStorage.key(i))) { //判断数据是否合法
                    hisTime.push(localStorage.key(i));
                }
            }
            if(hisTime.length > 0) {
                hisTime.sort(); //排序
                for(var y = 0; y < hisTime.length; y++) {
                    localStorage.getItem(hisTime[y]).trim() && hisItem.push(localStorage.getItem(hisTime[y]));
                }
            }
            $(".search_history .Storage").prevAll().remove(); //执行init(),每次清空之前添加的节点
            $(".Storage").show();
            for(var i = 0; i < hisItem.length; i++) {
                $(".search_history").prepend('<span class="word-break">' + hisItem[i] + '</span>');
                if(hisItem[i] != '') {
                    $(".Storage").hide();
                }
            }
        }
        init(); //调用
        //点击搜索按钮是将搜索内容存入到local storage
        $("#search").click(function() {
                var value = $("#ipt").val();
                var time = (new Date()).getTime();
                if(!value) {
                    return false;
                }
                //输入的内容localStorage有记录
                if($.inArray(value, hisItem) >= 0) {
                    for(var j = 0; j < localStorage.length; j++) {
                        if(value == localStorage.getItem(localStorage.key(j))) {
                            localStorage.removeItem(localStorage.key(j));
                        }
                    }
                    localStorage.setItem(time, value);

                } else {
                    localStorage.setItem(time, value);
                }
                init();

            });
        //清空浏览历史
        $(".delete").on("click",function(){
        	var f = 0;
            for(; f < hisTime.length; f++) {
                localStorage.removeItem(hisTime[f]);
            }
            init();
        })
        //历史记录搜索
         $(".search_history").on("click", ".word-break", function() {
                var div = $(this).text();
                $('#ipt').val(div);
            });
    </script>
@endsection
