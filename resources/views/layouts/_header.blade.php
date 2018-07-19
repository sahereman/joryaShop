@guest
    <a href="{{ route('login') }}">登录</a>
    <a href="{{ route('register') }}">注册</a>
@else
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        退出登录
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
@endguest