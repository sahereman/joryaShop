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
                <input type="text" name="" id="ipt" value="" placeholder="搜你想搜"/>
                <img src="{{ asset('static_m/img/icon_closed4.png') }}" class="seaClosed"/>
            </div>
            <span>搜索</span>
        </div>
        <div class="searchMain">
            <div class="searchNow">
                <h5>最近搜索</h5>
                <div class="searchNowBox">
                    {{-- TODO ... LocalStorage --}}
                    @if(true)
                            <!--暂无搜索历史-->
                    <div>暂无搜索历史</div>
                    @else
                        {{-- TODO ... search_history --}}
                        @for($i = 0;$i<3; $i++)
                            <span>网页</span>
                        @endfor
                    @endif
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
    </script>
@endsection
