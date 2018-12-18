<div class="navigation_left">
    <ul class="staircase_navigation">
        <li class="navigation_title user_index">
            <a href="{{ route('users.home') }}">@lang('basic.home')</a>
        </li>
        <li class="navigation_title">
            <a href="javascript:void(0);">@lang('basic.users.Personal_Center')</a>
        </li>
        <li class="account_info">
            <a href="{{ route('users.edit', ['user' => Auth::id()]) }}">@lang('basic.users.Account_information')</a>
        </li>
        <li class="change_psw">
            <a href="{{ route('users.password', ['user' => Auth::id()]) }}">@lang('basic.users.Change_Password')</a>
        </li>
        <li class="user_address">
            <a href="{{ route('user_addresses.index') }}">@lang('basic.users.Receiving_address')</a>
        </li>
        <li class="my_collection">
            <a href="{{ route('user_favourites.index') }}">@lang('basic.users.My_collection')</a>
        </li>
        <li class="browse_history">
            <a href="{{ route('user_histories.index') }}">@lang('basic.users.Browse_history')</a>
        </li>
        <li class="navigation_title">
            <a>@lang('basic.users.Transaction_management')</a>
        </li>
        <li class="my_order">
            <a href="{{ route('orders.index') }}">@lang('basic.users.My_order')</a>
        </li>
        <li class="navigation_title">
            <a>@lang('basic.users.Service_Centre')</a>
        </li>
        <li class="after_sale">
            <a href="{{ route('articles.show', ['slug' => 'refunding_service']) }}">@lang('basic.users.After-sale_service')</a>
        </li>
    </ul>
</div>
