@extends('layouts.mobile')
@section('title', App::isLocale('en') ? 'Categories' : '商品分类')
@section('content')
    <div class="cgeBox">
        <div class="cgeHead">
            <a href="{{route('mobile.search')}}" class="cgeHeadSearch">
                <img src="{{ asset('static_m/img/icon_search3.png') }}"/>
                <input type="text" name="" id="" value="" placeholder="@lang('product.you want to search')"
                       readonly="readonly"/>
            </a>
        </div>
        <div class="cgeMain">
            <div class="cgeMainLeft">
                @foreach($categories as $category)
                    @if($category->id == $category_id)
                        <div class="cgeActive" code="{{ $category->id }}"
                             data-url="{{ route('mobile.product_categories.more', ['category' => $category->id]) }}">
                            {{ App::isLocale('en') ? $category->name_en : $category->name_zh }}
                        </div>
                    @else
                        <div code="{{ $category->id }}"
                             data-url="{{ route('mobile.product_categories.more', ['category' => $category->id]) }}">
                            {{ App::isLocale('en') ? $category->name_en : $category->name_zh }}
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="cgeMainRight" code="{{ App::isLocale('en') ? 'en' : 'zh' }}">
            </div>
        </div>
    </div>
    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
        $(".itemsL").addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Select_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Unchecked_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Unchecked_my.png') }}");
        $(".cgeMainLeft div").on("click", function () {
            $(".cgeMainLeft div").removeClass("cgeActive");
            $(this).addClass("cgeActive");
            var url = $(this).attr("data-url");
            getResults(url);
        });
        window.onload = function () {
            var url = $(".cgeMainLeft").find(".cgeActive").attr("data-url");
            getResults(url);
        };
        //获取商品分类列表
        function getResults(url) {
            $.ajax({
                type: "get",
                url: url,
                beforeSend: function () {
                },
                success: function (json) {
                    var dataobj = json.data.children;
                    var html = "";
                    var name, list_name;
                    if (dataobj.length > 0) {
                        $.each(dataobj, function (i, n) {
                            name = ($(".cgeMainRight").attr('code') == "en") ? n.name_en : n.name_zh;
                            html += "<div class='cgeMainRightItem'>";
                            html += "<div class='cgeMainRightItemTitle'>";
                            html += "<span class='line'></span>";
                            html += "<span class='txt'>" + name + "</span>";
                            html += "<span class='line'></span>";
                            html += "</div>";
                            html += "<div class='cgeItemProBox'>";
                            if (n.products.length > 0) {
                                $.each(n.products, function (a, b) {
                                    list_name = ($(".cgeMainRight").attr('code') == "en") ? b.name_en : b.name_zh;
                                    html += "<div class='cgeItemPro'>";
                                    html += "<img class='lazy' src=" + b.thumb_url + ">";
                                    html += "<p>" + list_name + "</p>";
                                    html += "</div>";
                                });
                            }
                            html += "</div>";
                            html += "</div>";
                        });
                    } else {
                    }
                    $(".cgeMainRight").html("");
                    $(".cgeMainRight").append(html);
                },
                error: function (e) {
                    console.log(e);
                },
                complete: function () {
                }
            });
        }
    </script>
@endsection
