@extends('layouts.mobile')
@section('title', '浏览记录')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>浏览记录</span>
    </div>
    {{-- TODO ... route('mobile.user_histories.more').'?page=1' --}}
    @if(false)
            <!--暂无浏览历史-->
    <div class="notFav">
        <img src="{{ asset('static_m/img/Nohistory.png') }}"/>
        <span>暂无浏览历史</span>
        <a href="{{ route('mobile.root') }}">去逛逛</a>
    </div>
    @else
        <div class="favBox">
            <div class="timeTitle">
                <span>今天</span>
                <div></div>
            </div>
            @for($i = 0;$i < 3;$i++)
                <div class="favItem">
                    <img src="{{ asset('static_m/img/blockImg.png') }}"/>
                    <div class="favDetail">
                        <div class="goodsName">
                            卓业美业长直假发片卓业美业长直假发片
                        </div>
                        <div class="goodsPri">
                            <div>
                                <span class="realPri">￥520.00</span>
                                <s>￥1800.00</s>
                            </div>
                            <img src="{{ asset('static_m/img/icon_ShoppingCart2.png') }}"/>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="editFav">
            <div class="timeTitle">
                <span>今天</span>
                <div></div>
            </div>
            @for($i = 0;$i < 3;$i++)
                <div class="favItem">
                    <label class="favItemLab">
                        <input type="checkbox" name="checkitem" id="" value=""/>
                        <span></span>
                    </label>
                    <img src="{{ asset('static_m/img/blockImg.png') }}"/>
                    <div class="favDetail">
                        <div class="goodsName">
                            卓业美业长直假发片卓业美业长直假发片
                        </div>
                        <div class="goodsPri">
                            <div>
                                <span class="realPri">￥520.00</span>
                                <s>￥1800.00</s>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="browseFixt">
            <div class="browseTotalDiv">
                <input type="checkbox" name="" id="totalIpt" value=""/>
                <span class="bagLbl"></span>
                <label for="totalIpt" class="totalIpt">全选</label>
            </div>
            <div class="editBtns">
                <span class="editBtn">编辑</span>
                <span class="cancelBtn" name="isClick">删除所选</span>
            </div>
        </div>
    @endif
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".editBtn").on("click", function () {
            if ($(this).html() == "编辑") {
                $(this).html("返回");
                $(".favBox").css("display", "none");
                $(".editFav").css("display", "block");
                $(".browseTotalDiv").css("display", "block");
            } else if ($(this).html() == "返回") {
                $(this).html("编辑");
                $(".favBox").css("display", "block");
                $(".editFav").css("display", "none");
                $(".browseTotalDiv").css("display", "none");
            }

        });
        $(".cancelBtn").on("click", function () {
            if ($(this).attr("name") == "isClick") {
                layer.open({
                    anim: 'up'
                    , content: '确定要删除此商品吗？'
                    , btn: ['确认', '取消']
                });
            }
        });
        //实现全选与反选
        $("#totalIpt").click(function () {
            if ($(this).prop("checked")) {
                $("input[name=checkitem]:checkbox").each(function () {
                    $(this).prop("checked", true);
                    $(".cancelBtn").css("background", "#bc8c61");
                });
            } else {
                $("input[name=checkitem]:checkbox").each(function () {
                    $(this).prop("checked", false);
                    $(".cancelBtn").css("background", "#dcdcdc");
                });
            }
        });
        $(".favItemLab").on("click", function () {
            var iptArr = $(".favItemLab input");
            var eqArr = [];
            for (var i = 0; i < iptArr.length; i++) {
                var iptItem = iptArr[i].checked;
                eqArr.push(iptItem);
            }
            var index = $.inArray(true, eqArr);
            var totalIpt = $.inArray(false, eqArr);
            if (index == -1) {
                $(".cancelBtn").css("background", "#dcdcdc").attr("name", "notClick");

            } else {
                $(".cancelBtn").css("background", "#bc8c61").attr("name", "isClick");
                $("#totalIpt").prop("checked", false);
            }
            if (totalIpt == -1) {
                $("#totalIpt").prop("checked", "checked");
            }
        });
    </script>
@endsection
