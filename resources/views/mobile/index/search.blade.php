@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Product Search' : '商品搜索')
@section('content')
    <div class="seaBox">
        <div class="headerBar">
            <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg"
                 onclick="javascript:history.back(-1);"/>
            <span>@lang('product.Search')</span>
        </div>
        <div class="searchHead">
            <div class="searchHeadMain" code="{{ App::isLocale('en') ? 'en' : 'zh' }}">
                <img src="{{ asset('static_m/img/icon_search3.png') }}" class="seaImg"/>
                <input type="text" name="" id="ipt" value="" data-url="{{ route('products.search_hint') }}"
                       placeholder="@lang('product.you want to search')"/>
                <img src="{{ asset('static_m/img/icon_closed4.png') }}" class="seaClosed"/>
            </div>
            <span id="search" data-url="{{ route('mobile.products.search') }}">@lang('product.Search')</span>
        </div>
        <div class="searchMain">
            <div class="searchNow">
                <h5>@lang('product.Recent Searches')<span class="delete"></span></h5>
                <div class="searchNowBox search_history">
                    <!--暂无搜索历史-->
                    <div class="Storage">@lang('product.No search history')</div>
                </div>
            </div>
            <div class="searchNow">
                <h5>@lang('product.Top Searches')</h5>
                <div class="searchNowBox search_by_heart">
                    @if($categories)
                        @foreach($categories as $category)
                            <a href="{{ route('mobile.products.search') . '?query=' . (\Illuminate\Support\Facades\App::isLocale('en')? $category->name_en : $category->name_zh) }}">
                                <span>{{ \Illuminate\Support\Facades\App::isLocale('en')? $category->name_en : $category->name_zh }}</span>
                            </a>
                        @endforeach
                    @else
                        <a href="javascript:void(0);"><span>精品</span></a>
                        <a href="javascript:void(0);"><span>黄色假发</span></a>
                        <a href="javascript:void(0);"><span>中长假发</span></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="searchResult"></div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".seaClosed").on("click", function () {
            $("#ipt").val("");
            $(".searchMain").css("display", "block");
            $(".searchResult").css("display", "none");
        });
        //设置LocalStorage读写
        var hisTime; //获取搜索时间数组
        var hisItem; //获取搜索内容数组
        var firstKey; //获取最早的1个搜索时间
        function init() {
            hisTime = []; //时间数组置空
            hisItem = []; //内容数组置空
            for (var i = 0; i < localStorage.length; i++) { //数据去重
                if (!isNaN(localStorage.key(i))) { //判断数据是否合法
                    hisTime.push(localStorage.key(i));
                }
            }
            if (hisTime.length > 0) {
                hisTime.sort(); //排序
                for (var y = 0; y < hisTime.length; y++) {
                    localStorage.getItem(hisTime[y]).trim() && hisItem.push(localStorage.getItem(hisTime[y]));
                }
            }
            $(".search_history .Storage").prevAll().remove(); //执行init(),每次清空之前添加的节点
            $(".Storage").show();
            for (var i = 0; i < hisItem.length; i++) {
                $(".search_history").prepend('<span class="word-break">' + hisItem[i] + '</span>');
                if (hisItem[i] != '') {
                    $(".Storage").hide();
                }
            }
        }
        init(); //调用
        //点击搜索按钮是将搜索内容存入到local storage
        $("#search").click(function () {
            var value = $("#ipt").val();
            var time = (new Date()).getTime();
            if (!value) {
                return false;
            }
            //输入的内容localStorage有记录
            if ($.inArray(value, hisItem) >= 0) {
                for (var j = 0; j < localStorage.length; j++) {
                    if (value == localStorage.getItem(localStorage.key(j))) {
                        localStorage.removeItem(localStorage.key(j));
                    }
                }
                localStorage.setItem(time, value);
            } else {
                localStorage.setItem(time, value);
            }
            init();
            window.location.href = $(this).attr("data-url") + "?query=" + $('#ipt').val();
        });
        //清空浏览历史
        $(".delete").on("click", function () {
            var f = 0;
            for (; f < hisTime.length; f++) {
                localStorage.removeItem(hisTime[f]);
            }
            init();
        });
        //历史记录搜索
        $(".search_history").on("click", ".word-break", function () {
            var div = $(this).text();
            $('#ipt').val(div);
            $("#search").trigger("click");
        });
        //模糊查询
        var lastTime;
        $("#ipt").bind("input propertychange", function (event) {
            $(".searchMain").css("display", "none");
            $(".searchResult").css("display", "block");
            lastTime = event.timeStamp;
            var clickDom = $(this);
            setTimeout(function () {
                if (lastTime - event.timeStamp == 0) {
                    $.ajax({
                        type: "get",
                        url: clickDom.attr("data-url"),
                        data: {
                            "query": $("#ipt").val()
                        },
                        success: function (json) {
                            var html = "";
                            var name;
                            $.each(json.data.products, function (i, n) {
                                name = ($(".searchHeadMain").attr('code') == 'en') ? n.name_en : n.name_zh;
                                html += "<div class='searchResultItem' code='" + n.id + "' >" +
                                        "<a href='javascript:void(0);' data-href='{{ route('mobile.products.search') . '?query="+ name +"' }}'>" + name + "</a>" +
                                        "</div>";
                            });
                            $(".searchResult").html("");
                            $(".searchResult").append(html);
                        },
                        error: function (e) {
                            console.log(e);
                            if (e.status == 422) {
                            }
                        }
                    });
                }
            }, 200);
        });
        //点击查询结果进行跳转
        $(".searchResult").on("click", 'a', function () {
            $('#ipt').val($(this).html());
            $("#search").trigger("click");
        });
        //回车键事件函数
        $(document).keyup(function (event) {
            if (event.keyCode == 13) {
                $("#search").trigger("click");
            }
        });
        //热门搜索
        $(".search_by_heart").on("click", 'a', function () {
            $('#ipt').val($(this).find("span").html());
            $("#search").trigger("click");
        });
    </script>
@endsection
