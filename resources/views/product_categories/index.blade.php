@extends('layouts.app')
@section('title', '商品分类')
@section('content')
    @include('common.error')
    <div class="productCate my_orders">
    	 <!--商品分类导图-->
        <div class="swiper-container Taxonomy" id="Taxonomy">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a>
                        <img src="{{ asset('img/banner-2.png') }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="m-wrapper">
        	<!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">商品分类</a>
                </p>
            </div>
            <div class="classification-level">
            	<p class="level_title">分类：</p>
            	<ul>
            		<li class="active">
            			<span>全部</span>
            		</li>
            		<li>
            			<span>上衣</span>
            		</li>
            		<li>
            			<span>秋季小香风</span>
            		</li>
            		<li>
            			<span>连衣裙</span>
            		</li>
            		<li>
            			<span>a字裙</span>
            		</li>
            	</ul>
            </div>
            <!--商品分类展示-->
            @for ($i = 0; $i < 2; $i++)
			    <div class="classified-display">
			    	<div class="classified-title">
			    		<h3>进口好材料，温柔拖住你的美丽</h3>
			    		<p>严选材料，随时随地烫拉染</p>
			    	</div>
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
			@endfor
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
    	
    </script>
@endsection
