<nav class="navbar navbar-default">
    <div class="navbar-top">
        <div class="m-wrapper">
            <div class="navbar-top-left pull-left">
                <ul>
                    <li>
                        <span>@lang('app.switch language')：</span>
                    </li>
                    <li class="dropdown">
                        <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span>{{ App::getLocale() == 'en' ? 'English' : '中文' }}</span>
                            <img src="{{ asset('img/header/down_arrow.png') }}">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a href="{{ route('locale.update', ['locale' => 'zh-CN']) }}">
                                    <img src="{{ asset('img/header/cn_flag.png') }}">
                                    <span>中文</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('locale.update', ['locale' => 'en']) }}">
                                    <img src="{{ asset('img/header/en_flag.png') }}">
                                    <span>English</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="navbar-top-right pull-right">
                @guest
                    <a class="login">@lang('app.Sign_in')</a>
                    <a class="register">@lang('app.Register')</a>
                    <a class="about-us" href="{{ route('articles.show', ['slug' => 'about']) }}">@lang('app.About_us')</a>
                @else
                    <a id="user_info_btn" role="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false" class="user_name">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="user_info_btn">
                        <li>

                            <a class="touser_center" href="{{ route('users.home') }}">
                                <img class="user_img" src="{{ Auth::user()->avatar_url }}">
                                <span>@lang('app.Account_information')</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="login_out_a"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                @lang('app.Sign_out')
                            </a>
                        </li>
                    </ul>
                    <img src="{{ asset('img/header/down_arrow.png') }}">
                    <a class="about-us" href="{{ route('articles.show', ['slug' => 'about']) }}">@lang('app.About_us')</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endguest
            </div>
        </div>
    </div>
    <div class="navbar-bottom">
        <div class="m-wrapper">
            <div class="navbar-bottom-top">
                <div class="navbar-bottom-top-left">
                    <div class="header_logo">
                        <a href="{{ route('root') }}">
                            <img src="{{ asset('img/logo2.png') }}">
                            <p>@lang('app.The Best For You')</p>
                        </a>
                    </div>
                    <div class="navbar-bottom-top-left-right">
                        <p>@lang('app.Stock Custom  Hair Systems')</p>
                        <p><span>@lang('app.30 Day Money Back')</span> @lang('app.Guarantee')</p>
                    </div>
                </div>
                <div class="navbar-bottom-top-right">
                    <a href="{{ route('articles.show', ['slug' => 'stock_order']) }}">@lang('app.Stock Order')</a>
                    <a href="{{ route('articles.show', ['slug' => 'custom_order']) }}">@lang('app.Custom Order')</a>
                    <a href="{{ route('articles.show', ['slug' => 'duplicate']) }}">@lang('app.Duplicate')</a>
                    <a href="{{ route('articles.show', ['slug' => 'repair']) }}">@lang('app.Repair')</a>
                </div>
            </div>
            <div class="navbar-bottom-bottom">
                <ul class="navbar-bottom-bottom-left">
                    {{--<li class="first_menu">
                        <a href="{{ route('root') }}"><span>@lang('basic.home')</span></a>
                    </li>--}}
                    @foreach(\App\Models\Menu::pcMenus() as $menu)
                        @if(isset($menu['parent']))
                            <li class="first_menu">
                                <a href="{{ $menu['parent']->link }}">{{ App::isLocale('zh-CN') ? $menu['parent']->name_zh : $menu['parent']->name_en }}</a>
                                <!--二级菜单内容-->
                                @if(isset($menu['children']))
                                    <div class="nav-panel-dropdown">
                                        <ul>
                                            @foreach($menu['children'] as $child)
                                                <li>
                                                    <a href="{{ $child['link'] }}"><span>{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @else
                            <li class="first_menu">
                                <a href="{{ $menu->link }}">{{ App::isLocale('zh-CN') ? $menu->name_zh : $menu->name_en }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <div class="pull-right header-search">
                    <ul>
                        <li>
                            <input type="search" data-url="{{ route('products.search_hint') }}" class="selectInput_header" placeholder="@lang('app.Please enter the item you are searching for')">
                            <a class="search_btn" href="javascript:void(0);">
                                <img src="{{ asset('img/search_magnifier.png') }}">
                            </a>
                            <div class="selectList dis_n" data-url="{{ route('products.search') }}">
                                <ul></ul>
                            </div>
                        </li>
                        <li class="shppingCart">
                            <a href="{{ route('carts.index') }}" class="shop_cart">
                                <img src="{{ asset('img/header/shop_car.png') }}">
                                @if(isset($cart_count))
                                    <div class="for_cart_num">
                                        <span class="shop_cart_num">{{ $cart_count }}</span>
                                    </div>
                                @else
                                    <div class="for_cart_num">
                                        <span class="shop_cart_num">0</span>
                                    </div>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
