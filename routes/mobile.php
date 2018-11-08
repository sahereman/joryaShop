<?php

//以下所有路由 URL 为 site.com/mobile/ + 路由URL

Route::get('test', function () {

//    dd(\App\Models\CountryCode::countryCodes());
    dd('test mobile');
});


/*首页*/
Route::get('/', 'IndexController@root')->name('mobile.root'); // 首页


/*Auth*/
$this->get('login', 'Auth\LoginController@showLoginForm')->name('mobile.login.show'); // 登录页面
$this->post('login', 'Auth\LoginController@login')->name('mobile.login'); // 登录请求
$this->post('logout', 'Auth\LoginController@logout')->name('mobile.logout'); // 退出登录
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('mobile.register.show'); // 注册页面
$this->post('register', 'Auth\RegisterController@register')->name('mobile.register'); // 注册请求



