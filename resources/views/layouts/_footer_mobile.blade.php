<div class="footer">
	<div class="itemsF itemsS itemsActive">
		<img src="{{ asset('static_m/img/Select_home.png') }}"/>
		<span>首页</span>
	</div>
	<div class="itemsF itemsL">
		<img src="{{ asset('static_m/img/Unchecked_classification.png') }}"/>
		<span>分类</span>
	</div>
	<div class="itemsF itemsG">
		<img src="{{ asset('static_m/img/Unchecked_Shopping.png') }}"/>
		<span>购物车</span>
	</div>
	<div class="itemsF itemsW">
		<img src="{{ asset('static_m/img/Unchecked_my.png') }}"/>
		<span>我</span>
	</div>
</div>

<script src="{{ asset('static_m/js/app.js') }}"></script>
<script type="text/javascript">
	$(".itemsS").on("click",function(){
		$(".itemsF").removeClass("itemsActive");
		$(this).addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Select_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Unchecked_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
		window.location.href = "{{ route('mobile.root')}}";
	});
	$(".itemsL").on("click",function(){
		$(".itemsF").removeClass("itemsActive");
		$(this).addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Select_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Unchecked_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
	});
	$(".itemsG").on("click",function(){
		$(".itemsF").removeClass("itemsActive");
		$(this).addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Select_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Unchecked_my.png') }}");
	});
	$(".itemsW").on("click",function(){
		$(".itemsF").removeClass("itemsActive");
		$(this).addClass("itemsActive");
		$(".itemsS img").attr("src","{{ asset('static_m/img/Unchecked_home.png') }}");
		$(".itemsL img").attr("src","{{ asset('static_m/img/Unchecked_classification.png') }}");
		$(".itemsG img").attr("src","{{ asset('static_m/img/Unchecked_Shopping.png') }}");
		$(".itemsW img").attr("src","{{ asset('static_m/img/Select_my.png') }}");
		window.location.href = "{{ route('mobile.users.home')}}";
	});
</script>
