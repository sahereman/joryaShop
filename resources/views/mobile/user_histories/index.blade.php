@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Browse History' : '浏览记录')
@section('content')
    <div class="headerBar fixHeader" data-url="{{ route('mobile.user_histories.more') }}" code="{{ App::isLocale('en') ? 'en' : 'zh' }}">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('basic.users.Browse_history')</span>
    </div>
    <!--暂无浏览历史-->
    <div class="notFav dis_ni">
        <img src="{{ asset('static_m/img/Nohistory.png') }}"/>
        <span>@lang('product.No footprint yet')</span>
        <a href="{{ route('mobile.root') }}">@lang('product.shop_now')</a>
    </div>
    <div class="favBox histories_box">
    	<div class="lists"></div>
    </div>
    <div class="editFav histories_box">
    	<div class="lists"></div>
    </div>
    <div class="browseFixt">
        <div class="browseTotalDiv">
            <input type="checkbox" name="" id="totalIpt" value=""/>
            <span class="bagLbl"></span>
            <label for="totalIpt" class="totalIpt">@lang('product.shopping_cart.all_selected')</label>
        </div>
        <div class="editBtns">
            <span class="editBtn">@lang('product.Edit')</span>
            <span class="cancelBtn" name="isClick" data-url="{{ route('user_histories.multi_delete') }}">@lang('product.Deletes the selected')</span>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript" src="{{ asset('static_m/js/dropload/dropload.min.js') }}"></script>
    <script type="text/javascript">
        //页面单独JS写这里
        $(".editBtn").on("click", function () {
            if ($(this).html() == "@lang('product.Edit')") {
                $(this).html("@lang('product.Return')");
                $(".favBox").css("display", "none");
                $(".editFav").css("display", "block");
                $(".browseTotalDiv").css("display", "block");
                $(".favItemLab").removeClass("dis_ni");
            } else if ($(this).html() == "@lang('product.Return')") {
                $(this).html("@lang('product.Edit')");
                $(".favBox").css("display", "block");
                $(".editFav").css("display", "none");
                $(".browseTotalDiv").css("display", "none");
                $(".favItemLab").addClass("dis_ni");
            }

        });
        $(".cancelBtn").on("click", function () {
            if ($(this).attr("name") == "isClick") {
                layer.open({
                    anim: 'up',
                    content: "@lang('product.Are you sure you want to delete this item')",
                    btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                    yes: function(index){
                      	var history_ids = "";
                    	var choose_history = $(".editFav").find("input[type='checkbox']:checked");
                    	if(choose_history.length>0){
                    		$.each(choose_history,function(i,n){
                    			history_ids+= $(n).val()+","
                    		})
                    		history_ids = history_ids.substring(0,history_ids.length-1);
                    	}
                    	var data = {
			                _method: "DELETE",
			                _token: "{{ csrf_token() }}",
			                history_ids: history_ids
			            }
			            $.ajax({
			                type: "post",
			                url: $(".cancelBtn").attr("data-url"),
			                data: data,
			                success: function (data) {
			                	layer.close(index);
			                    window.location.reload();
			                },
			                error: function (err) {
			                    console.log(err);
			                    if (err.status == 403) {
			                         layer.open({
									    content: "@lang('app.Unable to complete operation')"
									    ,skin: 'msg'
									    ,time: 2 //2秒后自动关闭
									  });
			                    }
			                }
			            });
                    }
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
        $(".editFav").on("click",".favItemLab",function () {
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
        window.onload = function () {
        	getResults();
        }
        
        //获取历史记录
        function getResults() {
            // 页数
            var page = 1;
            $('.histories_box').dropload({
                scrollArea: window,
                domDown: { // 下方DOM
                    domClass: 'dropload-down',
                    domRefresh: "<div class='dropload-refresh'>↑@lang('product.product_details.Pull up load more')</div>",
                    domLoad: "<div class='dropload-load'><span class='loading'></span>@lang('product.product_details.Loading in')...</div>",
                    domNoData: "<div class='dropload-noData'>@lang('product.product_details.over the end')</div>"
                },
                loadDownFn: function (me) {
                    // 拼接HTML
                    var html = '';
                    var data = {
                        page: page,
                    };
                    $.ajax({
                        type: "get",
                        url: $(".headerBar").attr("data-url"),
                        data: data,
                        dataType: 'json',
                        success: function (data) {
//                      	alert("111111");
                        	var dataobj = data.data.histories;
                            var html = "";
                            var name, symbol, price,sku_name,total_shipping_fee,total_shipping;
                            if (dataobj.length != 0) {
                            	$(".notFav").addClass("dis_ni")
                                $.each(dataobj, function (i, n) {
                                    html+="<div class='timeTitle'>"
						            html+="<span>"+ n[0].browsed_at.split(" ")[0] +"</span>"
						            html+="<div></div>"
						            html+="</div>"
                                    $.each(n,function(a,b){
                                    	name = ($(".headerBar").attr("code") == "en") ? b.product.name_en : b.product.name_zh;
                                    	price = ($(".headerBar").attr("code") == "en") ? b.product.price_in_usd : b.product.price;
                                    	symbol = ($(".headerBar").attr("code") == "en") ? '&#36;' : '&#165;';
                                    	html+="<div class='favItem'>"
	                                    html+="<label class='favItemLab dis_ni'>"
	                                    html+="<input type='checkbox' name='checkitem' value='"+ b.id +"'/>"
	                                    html+="<span></span>"
	                                    html+="</label>"
	                                    html+="<img src='"+ b.product.thumb_url +"'/>"
	                                    html+="<div class='favDetail'>"
	                                    html+="<div class='goodsName'>"+ name +"</div>"
	                                    html+="<div class='goodsPri'>"
	                                    html+="<div>"
	                                    html+="<span class='realPri'>"+ symbol +" "+ price +"</span>"
	                                    html+="<s>"+ symbol +" "+ js_number_format(Math.imul(float_multiply_by_100(price), 12) / 1000)  +"</s>"
	                                    html+="</div>"
	                                    html+="</div>"
	                                    html+="</div>"
	                                    html+="</div>"
                                    })
                                });
                                // 如果没有数据
                            } else {
                                // 锁定
                                me.lock();
                                // 无数据
                                me.noData();
                                if(page==1){
                                	$(".notFav").removeClass("dis_ni");
                                	$(".dropload-down").remove();
                                }
                            }
                            $(".histories_box .lists").append(html);
                            page++;
                            // 每次数据插入，必须重置
                            me.resetload();
                        },
                        error: function (xhr, type) {
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                }
            });
        }
        
        
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
            // number = number.toString();
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
        //删除历史记录
        function delete_his(history_ids){
        	var data = {
                _method: "DELETE",
                _token: "{{ csrf_token() }}",
                history_ids: history_ids
           }
            $.ajax({
                type: "post",
                url: $(".cancelBtn").attr("data-url"),
                data: data,
                success: function (data) {
                    window.location.reload();
                },
                error: function (err) {
                    console.log(err);
                    if (err.status == 403) {
                        layer.open({
                            title: "@lang('app.Prompt')",
                            content: "@lang('app.Unable to complete operation')",
                            btn: ["@lang('app.determine')", "@lang('app.cancel')"],
                        });
                    }
                }
            });
        }
        
    </script>
@endsection
