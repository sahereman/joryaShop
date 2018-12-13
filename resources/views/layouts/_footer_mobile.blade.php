<div class="footer">
    <div class="itemsF itemsS itemsActive">
        <img src="{{ asset('static_m/img/Select_home.png') }}"/>
        <span>@lang('app.Home')</span>
    </div>
    <div class="itemsF itemsL">
        <img src="{{ asset('static_m/img/Unchecked_classification.png') }}"/>
        <span>@lang('app.Classification')</span>
    </div>
    <div class="itemsF itemsG">
        {{--@if($cart_count)
            <label class="num">{{ $cart_count }}</label>
        @endif--}}
        <img src="{{ asset('static_m/img/Unchecked_Shopping.png') }}"/>
        <span>@lang('app.Shopping Cart')</span>
    </div>
    <div class="itemsF itemsW">
        <img src="{{ asset('static_m/img/Unchecked_my.png') }}"/>
        <span>@lang('app.Account')</span>
    </div>
</div>

<script src="{{ asset('static_m/js/app.js') }}"></script>
<script type="text/javascript">
    $(".itemsS").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Select_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Unchecked_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Unchecked_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Unchecked_my.png') }}");
        window.location.href = "{{ route('mobile.root')}}";
    });
    $(".itemsL").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Select_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Unchecked_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Unchecked_my.png') }}");
        window.location.href = "{{ route('mobile.product_categories.index')}}";
    });
    $(".itemsG").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Unchecked_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Select_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Unchecked_my.png') }}");
        window.location.href = "{{ route('mobile.carts.index')}}";
    });
    $(".itemsW").on("click", function () {
        $(".itemsF").removeClass("itemsActive");
        $(this).addClass("itemsActive");
        $(".itemsS img").attr("src", "{{ asset('static_m/img/Unchecked_home.png') }}");
        $(".itemsL img").attr("src", "{{ asset('static_m/img/Unchecked_classification.png') }}");
        $(".itemsG img").attr("src", "{{ asset('static_m/img/Unchecked_Shopping.png') }}");
        $(".itemsW img").attr("src", "{{ asset('static_m/img/Select_my.png') }}");
        window.location.href = "{{ route('mobile.users.home')}}";
    });
</script>
