<header>
    {{-- header上半部分 --}}
    <div class="header-top">
        <div class="main-content">
            {{-- 国家/语言切换&&关于我们 --}}
            <div class="country-language">
                <div class="country dropdown">
                    <a href="#" class="dropdown-heading cover" type="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <span>
                            <span class="value">{{session('GlobalCurrency') ? session('GlobalCurrency') : 'USD'}}</span>
                            <span class="caret">&nbsp;</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li>
                            <a href="{{ route('currency.update', ['currency' => 'USD']) }}">
                                <span>USD - US Dollar</span>
                            </a>
                        </li>
                        @foreach(\App\Models\ExchangeRate::all() as $exchangeRate)
                            <li>
                                <a href="{{ route('currency.update', ['currency' => $exchangeRate->currency]) }}">
                                    <span>{{ $exchangeRate->currency }} - {{$exchangeRate->name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="language dropdown">
                    <a href="#" class="dropdown-heading cover" type="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <span>
                            <span class="value">English</span>
                            <span class="caret">&nbsp;</span>
                        </span>
                    </a>
                </div>
                <div class="contact-us">
                    <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">
                        <span class="value">Contact Us</span>
                    </a>
                </div>
            </div>
            {{-- 服务口号 --}}
            <div class="service-commitment">
                <span>30-Day Money BackGuarantee</span>
            </div>
            {{-- 登录注册&&购物车等 --}}
            <div class="register-login">
                <div>
                    @guest
                      <a href="{{ route('login') }}">Sign in</a><span>/</span><a
                            href="{{ route('register') }}">Register</a>
                    @else
                      <a class="login-name" href="{{ route('users.home') }}">Hi, {{ Auth::user()->name }}</a>
                      <span>|</span>
                      <a href="{{ route('logout') }}" class="login_out_a"
                         onclick="event.preventDefault();document.getElementById('logout-form').submit();">@lang('app.Sign_out')
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                      </form>
                    @endguest
                </div>
                <div class="person-center">
                    <a href="{{ route('users.home') }}">
                        <img src="{{ asset('img/header/personCenter.png') }}" alt="lyricalhair">
                    </a>
                </div>
                <div class="collect">
                    <a href="{{ route('users.home') }}">
                        <img src="{{ asset('img/header/collect.png') }}" alt="lyricalhair">
                    </a>
                </div>
                <div class="shop-car">
                    <a href="{{ route('carts.index') }}">
                        <img src="{{ asset('img/header/shopCar.png') }}" alt="lyricalhair">
                    </a>
                    {{-- 购物车商品数量 --}}
                    @if(isset($cart_count))
                        <span class="count shop_cart_num">{{ $cart_count }}</span>
                    @else
                        <span class="count shop_cart_num">0</span>
                    @endif
                </div>
                <div class="message">
                    <a href="{{ route('users.home') }}">
                        <img src="{{ asset('img/header/message.png') }}" alt="lyricalhair">
                    </a>
                </div>
                {{-- 移动端menu --}}
                <div class="mobile-menu mobile-nav">
                    <a href="javascript:void(0)">
                        <span class="iconfont">&#xe604;</span>
                    </a>
                    {{-- 移动menu的内容 --}}
                    <div class="mobile-menu-content mobile-nav-list">
                        <div class="mobile-list-item">
                            <a href="{{ route('carts.index') }}">Car</a>
                        </div>
                        <div class="mobile-list-item">
                            <a href="{{ route('user_favourites.index') }}">Wishlist</a>
                        </div>
                        {{-- <div class="mobile-list-item">
                            @guest
                                <a href="{{ route('login') }}">@lang('app.Log_In')</a>
                            @else
                                <a href="{{ route('users.home') }}">Hi, {{ Auth::user()->name }}</a>
                            @endguest
                        </div> --}}
                        <div class="mobile-list-item">
                            <a href="{{ route('articles.show', ['slug' => 'contact_us']) }}">@lang('app.Contact_Us')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- header下部分 --}}
    <div class="header-bottom">
        <div class="main-content">
            {{-- header logo部分 --}}
            <div class="header-logo">
                <a href="{{ route('root') }}">
                    <img src="{{ asset('img/header/Logo.png') }}" alt="lyricalhair">
                </a>
            </div>
            {{-- header分类菜单及搜索 --}}
            <div class="header-menu">
                {{-- header分类导航 --}}
                <div class="menu-list">
                    {{-- 一级导航 --}}
                    <ul class="first-menu">
                        <li class="img_menu">
                            <a class="first-nav" href="{{ route('root') }}">HOME</a>
                        </li>
                        @foreach(\App\Models\Menu::pcMenus() as $menu)
                            @if($menu->children->isNotEmpty() && $menu->children->first()->children->isNotEmpty())
                                <li class="first-tab">
                                    <a href="{{ $menu->link }}" class="first-nav">
                                        {{ App::isLocale('zh-CN') ? $menu->name_zh : $menu->name_en }}
                                    </a>
                                    {{-- 二级导航。根据内容变化导航的样式，如果数据过多，固定三列，右侧一张图片,如果内容没有图片没有二级导航则加class—— nav-two-little，参考第二个  --}}
                                    <div class="header-nav-two dis_n">
                                        {{--左侧导航内容--}}
                                        {{-- 根据内容判断该ul显示那个class，有二级详细子分类的现实—— nav-menu-more,没有子分类的显示—— nav-menu-little,具体显示差别查看第一个和第二个的效果--}}
                                        <div class="main-content">
                                            <ul class="nav-menu-more">
                                                @foreach($menu['children'] as $child)
                                                    <li>
                                                        <p class="header-nav-title">{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</p>
                                                        {{-- 二级菜单跟内容判断是否显示 --}}
                                                        @if($child->children->isNotEmpty())
                                                            <div class="nav-two-menu">
                                                                @foreach($child['children'] as $sub_child)
                                                                    <a href="{{ $sub_child['link'] }}">
                                                                        <span>{{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}</span>
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            {{--右侧图片内容,根据实际内容判断是否显示--}}
                                            <div class="header-nav-img">
                                                <img src="{{ asset('img/header/menu-bg.png') }}" alt="lyricalhair">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @elseif($menu->children->isNotEmpty())
                                <li class="first-tab first-tab-little">
                                    <a href="{{ $menu->link }}" class="first-nav">
                                        {{ App::isLocale('zh-CN') ? $menu->name_zh : $menu->name_en }}
                                    </a>
                                    {{-- 二级导航。根据内容变化导航的样式，如果数据过多，固定三列，右侧一张图片，菜单少的一部分 --}}
                                    <div class="header-nav-two nav-two-little dis_n">
                                        {{--左侧导航内容--}}
                                        {{-- 根据内容判断该ul显示那个class，有二级详细子分类的现实—— nav-menu-more,没有子分类的显示—— nav-menu-little,具体显示差别查看第一个和第二个的效果--}}
                                        <ul class="nav-menu-little">
                                            @foreach($menu['children'] as $child)
                                                <li>
                                                    <p class="header-nav-title">
                                                        <a href="{{ $child['link'] }}">
                                                            {{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}
                                                        </a>
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @else
                                <li class="first-tab first-tab-little">
                                    <a href="{{ $menu->link }}" class="first-nav">
                                        {{ App::isLocale('zh-CN') ? $menu->name_zh : $menu->name_en }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                {{-- header搜索 --}}
                <div class="header-search">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('img/header/search.png') }}" alt="lyricalhair">
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
{{-- 头部搜索 --}}
<div class="search-mask dis_n">
    <div class="main-content">
        <a href="javascript:void(0)" class="close-mask">
            <img src="{{ asset('img/header/close-mask.png') }}" alt="lyricalhair">
        </a>
        <div class="search-group">
            <div class="input-group">
                <input type="search" data-url="{{ route('products.search_hint') }}" class="selectInput_header"
                       placeholder="Search..."/>
                <a href="javascript:void(0)">
                    <img src="{{ asset('img/header/search-mask.png') }}" alt="lyricalhair">
                </a>
            </div>
            {{-- 模糊搜索结果 --}}
            <div class="selectList dis_n" data-url="{{ route('products.search') }}">
                <ul></ul>
            </div>
        </div>
    </div>
</div> 
