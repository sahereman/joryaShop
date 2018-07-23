<center>
    @guest
        <a href="{{ route('root') }}">首页</a>
        <a href="{{ route('login') }}">登录</a>
        <a href="{{ route('register') }}">注册</a>
    @else
        <a href="{{ route('root') }}">首页</a>
        <a href="{{ route('users.edit',Auth::id()) }}">个人设置</a>

        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endguest
</center>
<hr>

