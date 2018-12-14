@extends('layouts.mobile')
@section('title', '查看评价')
@section('title', App::isLocale('en') ? 'View comments' : '查看评价')
@section('content')
    <div class="headerBar fixHeader">
    	@if(!is_wechat_browser())
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('order.View comments')</span>
        @endif
    </div>
    <div class="showCommentBox commentBox">
        @foreach($order->snapshot as $order_item)
            <div class="ordDetail">
                <a href="{{ route('products.show', $order_item['sku']['product']['id']) }}">
                    <img src="{{ $order_item['sku']['product']['thumb_url'] }}">
                </a>
                <div>
                    <div class="ordDetailName">{{ App::isLocale('en') ? $order_item['sku']['product']['name_en'] : $order_item['sku']['product']['name_zh'] }}</div>
                    <div>
                        <span>
                            @lang('order.Quantity')：{{ $order_item['number'] }}
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
            <div class="commentDetail">
                <div class="comUser">
                    <img src="{{ $user->avatar_url }}" class="userHead"/>
                    <span>{{ $user->name }}</span>
                    <div class="starBox">
                        @for($i = 0; $i < $comments[$order_item['id']][0]['composite_index']; $i ++)
                            <img src="{{ asset('static_m/img/icon_Starsup.png') }}"/>
                        @endfor
                        @if($comments[$order_item['id']][0]['composite_index'] < 5)
                            @for($j = 0; $j < (5 - $comments[$order_item['id']][0]['composite_index']); $j ++)
                                <img src="{{ asset('static_m/img/icon_starsExtinguish.png') }}"/>
                            @endfor
                        @endif
                    </div>
                </div>
                <div class="comSku">
                    <span>
                        {{ App::isLocale('en') ? $order_item['sku']['name_en'] : $order_item['sku']['name_zh'] }}
                    </span>
                </div>
                <div class="comCon">
                    {{ $comments[$order_item['id']][0]->content }}
                </div>
                <div class="comPicture">
                    @foreach($comments[$order_item['id']][0]->photo_urls as $photo_url)
                        <img src="{{ $photo_url }}">
                    @endforeach
                </div>
                <div class="comDate">
                    {{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $comments[$order_item['id']][0]->created_at)->toDateString() }}
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
    </script>
@endsection
