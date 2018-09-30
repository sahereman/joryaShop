<nav class="navbar navbar-default">
	
		<div class="navbar-top">
			<div class="container">
				<div class="navbar-top-left pull-left">
				    <ul>
				    	<li>
				    		<span>切换语言：</span>
				    	</li>
				    	<li class="dropdown">
				    		 <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    		 	<span>中文</span>
					   	    	<img  src="{{ asset('img/header/down_arrow.png') }}">
					   	    </button>
					   	    <ul class="dropdown-menu" aria-labelledby="dLabel">
	                            <li>
	                                <a href="{{ route('root') }}">
	                                	<img src="{{ asset('img/header/cn_flag.png') }}">
	                                	<span>中文</span>
	                                </a>
	                            </li>
	                            <li>
	                                <a href="{{ route('root') }}">
	                                	<img src="{{ asset('img/header/en_flag.png') }}">
	                                	<span>EN</span>
	                                </a>
	                            </li>
	                        </ul>
				    	</li>
				   	</ul>
				</div>
				<div class="navbar-top-right pull-right">
					@guest
				        <a href="{{ route('login') }}">登录</a>
				        <a href="{{ route('register') }}">注册</a>
				        <a class="about-us" href="{{ route('root') }}">关于我们</a>
				    @else
				        <a href="{{ route('root') }}">首页</a>
				        <a href="{{ route('users.edit',Auth::id()) }}">个人设置</a>
				
				        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
				        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				            {{ csrf_field() }}
				        </form>
				    @endguest		
				</div>
			</div>
		</div>
		<div class="navbar-bottom">
			<div class="m-wrapper">
				<div class="pull-left header-menu dropdown">
					<button id="menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<p>
							<span></span>
							<span></span>
							<span></span>
						</p>
						<p>MENU</p>
					</button>
					<div  class="dropdown-menu" aria-labelledby="menu">
						<ul>
							<li>
								<a href="{{ route('root') }}">首页</a>
							</li>
							<li>
								<a href="{{ route('root') }}">穿搭</a>
							</li>
							<li>
								<a href="{{ route('root') }}">商务</a>
							</li>
							<li>
								<a href="{{ route('root') }}">简约</a>
							</li>
							<li>
								<a href="{{ route('root') }}">直发</a>
							</li>
							<li>
								<a href="{{ route('root') }}">卷发</a>
							</li>
							<li>
								<a href="{{ route('root') }}">时尚</a>
							</li>
							<li>
								<a href="{{ route('root') }}">正品保证</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="pull-right header-search">
					<ul>
						<li>
							<input type="search" placeholder="请输入您要搜索的商品">
							<a href="{{ route('login') }}">
								<img src="{{ asset('img/search_magnifier.png') }}">
							</a>
						</li>
						<li>
							<a href="{{ route('carts.index') }}" class="shop_cart">
								<img src="{{ asset('img/header/shop_car.png') }}">
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
</nav>
@section('scriptsAfterJs')
    <script type="text/javascript">
    	$(document).ready(function () {
    		
    	})
    </script>
@endsection