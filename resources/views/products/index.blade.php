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
        		<li>
        			<a>价格</a>
        		</li>
        	</ul>
        	<div>
        		<input type="text" placeholder="￥"/>
        		<span></span>
        		<input type="text" placeholder="￥"/>
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
	    							<span class="new-price"><i>￥</i>2556.00</span>
	    							<span class="old-price"><i>￥</i>580.00</span>
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
    	
    </script>
@endsection
