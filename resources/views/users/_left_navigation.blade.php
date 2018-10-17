<div class="navigation_left">
	<ul class="staircase_navigation">
		<li class="navigation_title user_index">
			<a href="{{ route('users.home') }}">首页</a>
		</li>
		<li class="navigation_title">
			<a>个人中心</a>
		</li>
		<li class="account_info">	 				
			<a href="{{ route('users.edit',['user' =>1]) }}">账户信息</a>	
		</li>
		<li class="change_psw">
			<a href="{{ route('users.password',['user' => 1]) }}">修改密码</a>
		</li>
		<li class="user_address">
			<a href="{{ route('user_addresses.index') }}">收货地址</a>
		</li>
		<li class="my_collection">
			<a href="{{ route('user_favourites.index') }}">我的收藏</a>
		</li>
		<li class="browse_history">
			<a href="{{ route('user_histories.index') }}">浏览历史</a>
		</li>
		<li class="navigation_title">
			<a>交易管理</a>
		</li>
		<li class="my_order">
			<a href="{{ route('orders.index') }}">我的订单</a>
		</li>
		<li class="navigation_title">
			<a>服务中心</h5>
		</li>
		<li class="after_sale">
			<a href="{{ route('root') }}">售后服务</a>
		</li>
	</ul>
</div>