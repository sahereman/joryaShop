@extends('layouts.mobile')
@section('title', '购物车')
@section('content')
    {{--如果需要引入子视图--}}
    {{--@include('layouts._header')--}}

    {{--填充页面内容--}}
    <h1>购物车 页面</h1>


    {{--如果需要引入子视图--}}
    @include('layouts._footer_mobile')
@endsection


@section('scriptsAfterJs')
    <script type="text/javascript">
        //页面单独JS写这里
        $(".itemsF").removeClass("itemsActive");
		$(".itemsG").addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Select_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
    </script>
@endsection
