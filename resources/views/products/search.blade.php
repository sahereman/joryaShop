@extends('layouts.app')
@section('title', '搜索结果')
@section('content')
    @include('common.error')
    <div class="products-search-level">
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">全部结果</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">商品分类</a>
                </p>
            </div>
            <div class="search-level">
                <ul>
                    <li class="active">
                        <a>综合</a>
                    </li>
                    <li>
                        <a>人气</a>
                    </li>
                    <li>
                        <a>新品</a>
                    </li>
                    <li>
                        <a>销量</a>
                    </li>
                    <li class="icon">
                        <a>
                            <span>价格</span>
                            <div>
                                <i class="w-icon-arrow arrow-up"></i>
                                <i class="w-icon-arrow arrow-down"></i>
                            </div>
                        </a>
                    </li>
                </ul>
                <div>
                    <input type="text" placeholder="&yen;"/>
                    <span></span>
                    <input type="text" placeholder="&yen;"/>
                    <button>确定</button>
                </div>
            </div>
            <!--商品分类展示-->
            <div class="classified-display">
                <div class="classified-products">
                    <ul class="classified-lists">
                        @for ($a = 0; $a < 20; $a++)
                            <li>
                                <div class="list-img">
                                    <img src="{{ asset('img/kinds-pro.png') }}">
                                </div>
                                <div class="list-info">
                                    <p class="list-info-title">时尚渐变色</p>
                                    <p>
                                        <span class="new-price"><i>&yen;</i>{{ $a }}</span>
                                        <span class="old-price"><i>&yen;</i>580.00</span>
                                    </p>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        var a = 0;
        $(".icon").click(
                function () {
                    a++;
//                console.log("hello");//显示消息，不影响页面的加载
                    var arr = new Array();
                    //遍历节点取值赋给数组，并绑定事件
                    //.each(function(index,Element))   返回jQuery
                    // 描述：遍历一个jQuery对象，为每个匹配元素执行一个函数
                    //index表示当前元素的位置  e表示当前的元素
                    $(".classified-lists .new-price").each(function (index, e) {
                        //alert("index:"+index)
                        // alert("e:"+e)
                        arr[index] = parseInt($(e).text().substring(1));
                    });

                    if (a % 2 != 0) {
                        //  升序
                        for (var i = 1; i < arr.length; i++) {
                            for (var j = 0; j < arr.length - i; j++) {
                                var temp = 0;
                                if (arr[j] > arr[j + 1]) {
                                    temp = arr[j];
                                    arr[j] = arr[j + 1];
                                    arr[j + 1] = temp;
                                }
                            }
                        }
                    } else {
                        //  降序
                        for (var i = 1; i < arr.length; i++) {
                            for (var j = 0; j < arr.length - i; j++) {
                                var temp = 0;
                                if (arr[j] < arr[j + 1]) {
                                    temp = arr[j];
                                    arr[j] = arr[j + 1];
                                    arr[j + 1] = temp;
                                }
                            }
                        }
                    }

                    // 获取数组的长度
                    var len = $(".classified-lists .new-price").length;
                    //取到li下的数字值
                    //把li与数组一一对应的顺序进行追加到ul
                    for (var i = 0; i < arr.length; i++) {
                        for (var j = 0; j < len; j++) {
                            if (arr[i] == $(".classified-lists .new-price").eq(j).text().substring(1)) {
                                // console.log(i+""+j);
                                $(".classified-lists .new-price").eq(j).parents("li").remove().appendTo(".classified-lists");
                                break;
                            }
                        }
                    }
                });
    </script>
@endsection
