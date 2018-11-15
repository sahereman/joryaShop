@extends('layouts.app')
@section('title', '个人中心-我的收藏')
@section('content')
    <div class="User_collection">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('user_favourites.index') }}">我的收藏</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="user_collection_content">
                @if($favourites->isEmpty())
                        <!--当没有收藏列表时显示,如需显示当前内容需要调整一下样式-->
                <div class="no_collectionList">
                    <img src="{{ asset('img/no_collection.png') }}">
                    <p>还没有任何收藏哦~</p>
                    <a class="new_address" href="{{ route('root') }}">去逛逛</a>
                </div>
                @else
                        <!--存在收藏列表-->
                <div class="receive_collection">
                    <!--收藏列表-->
                    <div class="address_list">
                        <ul>
                            @foreach($favourites as $favourite)
                                <li>
                                    <div class="collection_shop_img">
                                        <img src="{{ $favourite->product->thumb_url }}">
                                    </div>
                                    <p class="commodity_title">{{ $favourite->product->name_zh }}</p>
                                    <p class="collection_price">
                                        <span class="new_price">@lang('basic.currency.symbol') {{ $favourite->product->price }}</span>
                                        <span class="old_price">@lang('basic.currency.symbol') {{ bcmul($favourite->product->price, 1.2, 2) }}</span>
                                    </p>
                                    <a class="add_to_cart" href="{{ route('products.show', $favourite->id) }}">加入购物车</a>
                                    <a class="delete_mark" code="{{ route('user_favourites.destroy', $favourite->id) }}"
                                       title="点击删除该商品"></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
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
                        <span>确定要删除此商品？</span>
                    </p>
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
            $(".navigation_left ul li").removeClass("active");
            $(".my_collection").addClass("active");
            //点击表格中的删除
            $(".address_list ul").on("click", ".delete_mark", function () {
                $(".confirm_delete .textarea_content").find("span").attr("code", $(this).attr("code"));
                $(".confirm_delete").show();
            });
            $(".confirm_delete").on("click", ".success", function () {
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                };
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
                    }
                });
            });
        });
    </script>
@endsection
