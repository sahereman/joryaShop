<div class="footer">
    <div class="itemsF itemsS itemsActive">
        <!--<img src="{{ asset('static_m/img/Select_home.png') }}"/>-->
        <div class="item_bg itemsS_bg"></div>
        <span>@lang('app.Home')</span>
    </div>
    <div class="itemsF itemsL">
        <!--<img src="{{ asset('static_m/img/Unchecked_classification.png') }}"/>-->
        <div class="item_bg itemsL_bg"></div>
        <span>@lang('app.Classification')</span>
    </div>
    <div class="itemsF itemsG">
        {{--@if($cart_count)
            <label class="num">{{ $cart_count }}</label>
        @endif--}}
        <!--<img src="{{ asset('static_m/img/Unchecked_Shopping.png') }}"/>-->
        <div class="item_bg itemsG_bg"></div>
        <span>@lang('app.Shopping Cart')</span>
    </div>
    <div class="itemsF itemsW">
        <!--<img src="{{ asset('static_m/img/Unchecked_my.png') }}"/>-->
        <div class="item_bg itemsW_bg"></div>
        <span>@lang('app.Account')</span>
    </div>
</div>

<script src="{{ asset('static_m/js/app.js') }}"></script>
<script type="text/javascript">
    $(".itemsS").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".item_bg").removeClass("active");
        $(".itemsS_bg").addClass("active");
        window.location.href = "{{ route('mobile.root')}}";
    });
    $(".itemsL").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".item_bg").removeClass("active");
        $(".itemsL_bg").addClass("active");
        window.location.href = "{{ route('mobile.product_categories.index')}}";
    });
    $(".itemsG").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".item_bg").removeClass("active");
        $(".itemsG_bg").addClass("active");
        window.location.href = "{{ route('mobile.carts.index')}}";
    });
    $(".itemsW").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".item_bg").removeClass("active");
        $(".itemsW_bg").addClass("active");
        window.location.href = "{{ route('mobile.users.home')}}";
    });
    $(function(){
	    $(".itemsActive").find(".item_bg").addClass("active");
    })
</script>
