@extends('layouts.mobile')
@section('title', '创建评价')
@section('content')
    <div class="headerBar fixHeader">
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>发布评价</span>
    </div>
    <div class="commentBox">
        @foreach($order->snapshot as $order_item)
            <div class="ordDetail">
                <a href="{{ route('products.show', $order_item['sku']['product']['id']) }}">
                    <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                </a>
                <div>
                    <div class="ordDetailName">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</div>
                    <div>
                        <span>
                            数量：{{ $order_item['number'] }}
                            &nbsp;&nbsp;
                        </span>
                        <span>
                            <a href="{{ route('mobile.products.show', ['product' => $order_item['sku']['product']['id']]) }}">
                                {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                            </a>
                        </span>
                    </div>
                    <div class="ordDetailPri">
                        <span>{{ ($order->currency == 'USD') ? '&#36;' : '&#165;' }}</span>
                        <span>{{ $order_item['price'] }}</span>
                    </div>
                </div>
            </div>
            <div class="commentCon">
                <textarea name="content[{{ $order_item['id'] }}]" maxlength="200" rows="3" cols=""
                          placeholder="@lang('product.comments.Please enter a product evaluation of less than 200 words')">
                </textarea>
                <div class="goodspicture">
                    <div class="goodsItem">
                        <img src="{{ asset('static_m/img/blockImg.png') }}" class="goodsItemPicImg"/>
                        <img src="{{ asset('static_m/img/icon_Closed.png') }}" class="closeImg"/>
                    </div>
                    <div class="goodsChoice">
                        <img src="{{ asset('static_m/img/icon_Additive.png') }}"/>
                        <span>1/5</span>
                    </div>
                </div>
            </div>
            <div class="commentScore">
                <div class="commentScoreTitle">商品评分</div>
                <div class="commentScoreMain">
                    <div class="commentScoreItem">
                        <span>质量满意</span>
                        <div class="star starOne">
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                        </div>
                    </div>
                    <div class="commentScoreItem">
                        <span class="must">服务态度</span>
                        <div class="star starTwo">
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                        </div>
                    </div>
                    <div class="commentScoreItem">
                        <span>物流服务</span>
                        <div class="star starS">
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fixedBtn">
                <button>发布</button>
            </div>
        @endforeach
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(function () {
            var wjx_k = "{{ asset('static_m/img/icon_starsExtinguish.png') }}";
            var wjx_s = "{{ asset('static_m/img/icon_Starsup.png') }}";
            //prevAll获取元素前面的兄弟节点，nextAll获取元素后面的所有兄弟节点
            //end 方法；返回上一层
            //siblings 其它的兄弟节点
            //绑定事件
            $(".star img").on("mouseenter", function () {
                $(this).attr("src", wjx_s).prevAll().attr("src", wjx_s).end().nextAll().attr("src", wjx_k);
            }).on("click", function () {
                $(this).addClass("active").siblings().removeClass("active")
            });
        });
    </script>
@endsection
