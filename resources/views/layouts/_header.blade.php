<nav class="navbar navbar-default">
    <div class="navbar-top">
        <div class="m-wrapper">
            <div class="navbar-top-left pull-left">
                <ul>
                    <li>
                        <span>切换语言：</span>
                    </li>
                    <li class="dropdown">
                        <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span>中文</span>
                            <img src="{{ asset('img/header/down_arrow.png') }}">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a>
                                    <img src="{{ asset('img/header/cn_flag.png') }}">
                                    <span>中文</span>
                                </a>
                            </li>
                            <li>
                                <a>
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
                <a class="login">登录</a>
                <a class="register">注册</a>
                <a class="about-us" href="{{ route('root') }}">关于我们</a>
                @else
                    <a id="user_info_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       class="user_name">{{ Auth::user()->name }}</a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li>

                            <a class="touser_center" href="{{ route('users.home') }}">
                                    <span>
                                        <img class="user_img" src="{{ Auth::user()->avatar_url }}">
                                    </span>
                                <span>账户信息</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
                        </li>
                    </ul>
                    <img src="{{ asset('img/header/down_arrow.png') }}">
                    <a class="about-us" href="{{ route('root') }}">关于我们</a>
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
                <div class="dropdown-menu" aria-labelledby="menu">
                    <ul>
                        <li>
                            <a href="{{ route('root') }}">首页</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">穿搭</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">商务</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">简约</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">直发</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">卷发</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">时尚</a>
                        </li>
                        <li>
                            <a href="{{ route('product_categories.index', 1) }}">正品保证</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header_logo">
                <a href="{{ route('root') }}">
                    <img src="{{ asset('img/logo.png') }}">
                </a>
            </div>
            <div class="pull-right header-search">
                <ul>
                    <li>
                        <input type="search" class="selectInput_header" placeholder="请输入您要搜索的商品">
                        <a class="search_btn" href="javascript:void(0)">
                            <img src="{{ asset('img/search_magnifier.png') }}">
                        </a>
						<div class="selectList dis_n" data-url="{{ route('products.search') }}">
							<ul></ul>
						</div>
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
