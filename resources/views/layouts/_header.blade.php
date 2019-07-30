<div class="header-top-container">
    <div class="header-top  header container clearer">
        <div class="inner-container">
            <div class="left-column">
                <div id="currency-switcher-wrapper-regular" class="item item-left">
                    <div class="currency-switcher dropdown">
                        <a href="#" class="dropdown-heading cover" type="button" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                            <span>
                               <span class="value">@lang('app.switch currency')</span>
                                <span class="caret">&nbsp;</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a href="{{ route('currency.update', ['currency' => 'USD']) }}">
                                    {{--<img src="{{ asset('img/header/en_flag.png') }}">--}}
                                    <span>&#36;</span>
                                    <span>USD</span>
                                </a>
                            </li>
                            @foreach(\App\Models\ExchangeRate::all() as $exchangeRate)
                                <li>
                                    <a href="{{ route('currency.update', ['currency' => $exchangeRate->currency]) }}">
                                        {{--<img src="{{ asset('img/header/en_flag.png') }}">--}}
                                        <span>{{ \App\Models\ExchangeRate::$symbolMap[$exchangeRate->currency] }}</span>
                                        <span>{{ $exchangeRate->currency }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div id="multilang-switcher-wrapper-regular" class="item item-left">
                    <div class="currency-switcher dropdown">
                        <a href="#" class="dropdown-heading cover" type="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <span>
                               <span class="value">English</span>
                                <span class="caret">&nbsp;</span>
                            </span>
                        </a>
                        {{--<ul class="dropdown-menu" aria-labelledby="dLabel">--}}
                            {{--<li>--}}
                                {{--<a href="{{ route('locale.update', ['locale' => 'zh-CN']) }}">--}}
                                    {{--<img src="{{ asset('img/header/cn_flag.png') }}">--}}
                                    {{--<span>中文</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="{{ route('locale.update', ['locale' => 'en']) }}">--}}
                                    {{--<img src="{{ asset('img/header/en_flag.png') }}">--}}
                                    {{--<span>English</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    </div>
                </div>
                <div class="item item-left" id="why-lordhair">
                    <a href="javascript:void(0)">Why Lyricalhair?</a>
                </div>
            </div>
            <div class="right-column">
                <div class="item item-right">
                    <a class="skype_btn" href="skype:live:info_1104672?call">
                        <img src="{{ asset('img/skype-call.png') }}"/>
                    </a>
                </div>
                <div class="item item-right mini-cart-wrapper-regular">
                    <a href="{{ route('carts.index') }}">
                        <img src="{{ asset('img/header/ic-cart.png') }}">
                        {{-- 判断是否登录登陆显示，不登陆不显示 --}}
                        @guest
                        @else
                            @if(isset($cart_count))
                                <span class="count">{{ $cart_count }}</span>
                            @else
                                <span class="count">0</span>
                            @endif
                        @endguest
                        <span class="caret">&nbsp;</span>
                    </a>
                    {{-- 显示部分商品列表没接口 --}}
                    <div id="header-cart" class="mini-cart-content dis_ni">
                        <div class="block-content-inner">
                            <div class="empty">You have no items in your shopping cart.</div>
                        </div>
                    </div>
                </div>
                <div class="item item-right">
                    <a href="#">Wishlist</a>
                </div>
                <div class="item item-right quick-login">
                    @guest
                        <a href="{{ route('login') }}">@lang('app.Log_In')</a>
                        <div class="quick-login-dropdown">
                            <form id="login-form" action="{{ route('login.post') }}" method="POST" autocomplete="off">
                                <p id="commn_login_token_code" class="dis_n">{{ csrf_field() }}</p>
                                <ul>
                                    <li>
                                        <em class="red">*</em>
                                        <input type="text" name="username" autocomplete="off" required placeholder="Email Address">
                                    </li>
                                    <li>
                                        <em class="red">*</em>
                                        <input type="password" name="password" autocomplete="off" required placeholder="Password">
                                    </li>
                                </ul>
                            </form>
                            <a class="btn_dialog commo_btn active" data-url="{{ route('login') }}">@lang('app.Log_In')</a>
                            <p class="greetings-word"> Want to join the family?</p>
                            <a href="{{ route('register') }}" class="to-register">
                                <span>Create an Account</span>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('users.home') }}">Hi, {{ Auth::user()->name }}</a>
                        <div class="quick-login-dropdown">
                            <ul>
                                <li class="login-content">
                                    <a class="touser_center" href="{{ route('users.home') }}">
                                        <span>@lang('app.Account_information')</span>
                                    </a>
                                </li>
                                <li class="login-content sign-out">
                                    <a href="{{ route('logout') }}" class="login_out_a"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        @lang('app.Sign_out')
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    @endguest
                </div>
                <div class="item item-right">
                    <a href="{{ route('articles.show', ['slug' => 'contact_us']) }}">@lang('app.Contact_Us')</a>
                </div>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-default">
    <div class="navbar-bottom">
        <div class="m-wrapper">
            <div class="navbar-bottom-top inner-container">
                <div class="navbar-bottom-top-left">
                    <div class="header_logo">
                        <a href="{{ route('root') }}">
                            <img src="{{ asset('img/logo2.png') }}">
                        </a>
                    </div>
                    <div class="navbar-bottom-top-left-right">
                        <p>@lang('app.Custom & Stock Hair Systems')</p>
                        <p><span>@lang('app.30-Day Money Back')</span> @lang('app.Guarantee')</p>
                    </div>
                </div>
                <div class="navbar-bottom-top-right">
                    @foreach(\App\Models\Menu::subPcMenus() as $sub_child)
                        <a href="{{ $sub_child['link'] }}">
                            {{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="navbar-bottom-bottom inner-container">
                <ul class="navbar-bottom-bottom-left">
                    <li class="img_menu">
                        <a href="{{ route('root') }}">
                            <img src="{{ asset('img/home2.png') }}">
                        </a>
                    </li>
                    @foreach(\App\Models\Menu::pcMenus() as $menu)
                        @if($menu->children->isNotEmpty())
                            <li class="first_menu">
                                <a href="{{ $menu->link }}">
                                    {{ App::isLocale('zh-CN') ? $menu->name_zh : $menu->name_en }}
                                </a>
                                <!--二级菜单内容-->
                                <div class="nav-panel-dropdown">
                                    <ul>
                                        <li>
                                            @foreach($menu['children'] as $key => $child)
                                                @if(($key%4) == 0)
                                                    <div class="nav-column">
                                                        <a class="nav-panel-one" href="{{ $child['link'] }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</span>
                                                        </a>
                                                        @if($child->children->isNotEmpty())
                                                            <div class="nav-panel-two">
                                                                @foreach($child['children'] as $sub_child)
                                                                    <div class="nav-two-item">
                                                                        <a href="{{ $sub_child['link'] }}">
                                                                            <span>{{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}</span>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </li>

                                        <li>
                                            @foreach($menu['children'] as $key => $child)
                                                @if(($key%4) == 1)
                                                    <div class="nav-column">
                                                        <a class="nav-panel-one" href="{{ $child['link'] }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</span>
                                                        </a>
                                                        @if($child->children->isNotEmpty())
                                                            <div class="nav-panel-two">
                                                                @foreach($child['children'] as $sub_child)
                                                                    <div class="nav-two-item">
                                                                        <a href="{{ $sub_child['link'] }}">
                                                                            <span>{{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}</span>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </li>

                                        <li>
                                            @foreach($menu['children'] as $key => $child)
                                                @if(($key%4) == 2)
                                                    <div class="nav-column">
                                                        <a class="nav-panel-one" href="{{ $child['link'] }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</span>
                                                        </a>
                                                        @if($child->children->isNotEmpty())
                                                            <div class="nav-panel-two">
                                                                @foreach($child['children'] as $sub_child)
                                                                    <div class="nav-two-item">
                                                                        <a href="{{ $sub_child['link'] }}">
                                                                            <span>{{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}</span>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </li>

                                        <li>
                                            @foreach($menu['children'] as $key => $child)
                                                @if(($key%4) == 3)
                                                    <div class="nav-column">
                                                        <a class="nav-panel-one" href="{{ $child['link'] }}">
                                                            <span>{{ App::isLocale('zh-CN') ? $child['name_zh'] : $child['name_en'] }}</span>
                                                        </a>
                                                        @if($child->children->isNotEmpty())
                                                            <div class="nav-panel-two">
                                                                @foreach($child['children'] as $sub_child)
                                                                    <div class="nav-two-item">
                                                                        <a href="{{ $sub_child['link'] }}">
                                                                            <span>{{ App::isLocale('zh-CN') ? $sub_child['name_zh'] : $sub_child['name_en'] }}</span>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </li>

                                    </ul>
                                </div>
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
                        <li class="for_show_search">
                            <a class="show_btn" href="javascript:void(0);">
                                <img src="{{ asset('img/search_magnifier.png') }}">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
{{-- 搜索框所在位置 --}}
<div class="search-wrapper-regular">
    <div class="show_search">
        <a class="search_btn" href="javascript:void(0);">
            <img src="{{ asset('img/header/search.png') }}">
        </a>
        <input type="search" data-url="{{ route('products.search_hint') }}" class="selectInput_header"
               placeholder="@lang('app.Please enter the item you are searching for')">
        <div class="selectList dis_n" data-url="{{ route('products.search') }}">
            <ul></ul>
        </div>
    </div>
</div>