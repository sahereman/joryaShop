@extends('layouts.app')
@section('title', '个人中心-浏览历史')
@section('content')
    <div class="User_history">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('user_histories.index') }}">浏览历史</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="user_collection_content">
                @if($histories->isEmpty())
                        <!--当没有收藏列表时显示,如需显示当前内容需要调整一下样式-->
                <div class="no_collectionList">
                    <img src="{{ asset('img/no_history.png') }}">
                    <p>还没有任何足迹哦~</p>
                    <a class="new_address" href="{{ route('root') }}">去逛逛</a>
                </div>
                @else
                        <!--浏览历史列表-->
                <div class="receive_collection">
                    <div class="history_operation_area">
                        <a class="history_empty pull-right" data-url="{{ route('user_histories.flush') }}">
                            <img src="{{ asset('img/empty_history.png') }}">
                            <span>清空所有浏览历史</span>
                        </a>
                    </div>
                    <!--浏览历史列表-->
                    <div class="address_list">
                        @foreach($histories as $key => $historySet)
                            <div class="Timeline">
                                <!--<img  src="{{ asset('img/timeline.png') }}">-->
                                <span>{{ $key }}</span>
                            </div>
                            <ul>
                                @foreach($historySet as $history)
                                    <li>
                                        <div class="collection_shop_img">
                                            <img class="lazy" data-src="{{ $history->product->thumb_url }}">
                                        </div>
                                        <p class="commodity_title">{{ App::isLocale('en') ? $history->product->name_en : $history->product->name_zh }}</p>
                                        <p class="collection_price">
                                            <span class="new_price">@lang('basic.currency.symbol') {{ $history->product->price }}</span>
                                            <span class="old_price">@lang('basic.currency.symbol') {{ bcmul($history->product->price, 1.2, 2) }}</span>
                                        </p>
                                        <a class="add_to_cart" href="{{ route('products.show', $history->id) }}">查看详情</a>
                                        <a class="delete_mark" code="{{ route('user_histories.destroy', $history->id) }}" title="点击删除该商品"></a>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
                <div class="paging_box">
                    <!--自定义分页-->
                    @if($previous_page)
                        <a class="pre_page"
                           href="{{ route('user_histories.index') . '?page=' . $previous_page }}">上一页</a>
                    @endif
                    @if($next_page)
                        <a class="next_page"
                           href="{{ route('user_histories.index') . '?page=' . $next_page }}">下一页</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    <!--是否确认删除弹出层-->
    <div class="dialog_popup confirm_delete">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>提示</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>确定要删除此记录？</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
    <!--是否清空-->
    <div class="dialog_popup empty_history_dia">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>提示</span>
                </div>
                <div class="textarea_content">
                    <form>
                        <p>
                            <img src="{{ asset('img/warning.png') }}">
                            <span>清空后将无法恢复，是否继续？？</span>
                        </p>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
        	var loading_animation;
            $(".navigation_left ul li").removeClass("active");
            $(".browse_history").addClass("active");
            //点击表格中的删除
            $(".address_list ul").on("click", ".delete_mark", function () {
            	$(".confirm_delete .textarea_content").find("span").attr("code",$(this).attr("code"));
                $(".confirm_delete").show();
            });
            $(".confirm_delete").on("click",".success",function(){
            	var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                }
                var url = $(".textarea_content span").attr('code');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                    	window.location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status==403) {
                        	layer.open({
							  title: '提示'
							  ,content: '无法完成操作'
							});     
                        }
                    }
                });
            })
            $(".history_empty").on("click", function () {
            	var data_url = $(this).attr("data-url");
            	$(".empty_history_dia form").attr("data-url",data_url);
                $(".empty_history_dia").show();
            });
            $(".empty_history_dia").on("click",".success",function(){
            	var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
              }
                var url = $(".empty_history_dia form").attr("data-url");
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    beforeSend:function(){
	        			loading_animation = layer.msg('请稍候', {
			                icon: 16,
			                shade: 0.4,
			                time:false //取消自动关闭
						});
	        		},
                    success: function (data) {
                    	window.location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                        if (err.status==403) {
                        	layer.open({
							  title: '提示'
							  ,content: '无法完成操作'
							});     
                        }
                    },
                    complete:function(){
	        	    	layer.close(loading_animation);
	        	    }
                });
            })
        });
    </script>
@endsection
