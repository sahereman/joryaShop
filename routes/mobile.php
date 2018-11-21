<?php

//以下所有路由 URL 为 site.com/mobile/ + 路由URL

Route::get('test', function () {

    App::setLocale('en');


    return 'test';
});


/*首页*/
Route::get('/', 'IndexController@root')->name('mobile.root'); // 首页
Route::get('locale', 'IndexController@localeShow')->name('mobile.locale.show'); // 修改网站语言 页面


/*注册与登录*/
Route::get('login', 'Auth\LoginController@showLoginForm')->name('mobile.login.show'); // 登录 页面
Route::post('login', 'Auth\LoginController@login')->name('mobile.login.store'); // 登录 请求
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('mobile.register.show'); // 注册 页面
Route::post('register', 'Auth\RegisterController@register')->name('mobile.register.store'); // 注册 请求


/*重置密码*/
Route::get('password/reset/sms', 'Auth\ResetPasswordController@smsShow')->name('mobile.reset.sms.show'); // 重置密码(短信方式) 页面
Route::post('password/reset/sms', 'Auth\ResetPasswordController@smsSubmit')->name('mobile.reset.sms.store'); // 重置密码(短信方式) 请求
Route::get('password/reset/override', 'Auth\ResetPasswordController@overrideShow')->name('mobile.reset.override.show'); // 重置新密码 页面
Route::post('password/reset/override', 'Auth\ResetPasswordController@overrideSubmit')->name('mobile.reset.override.store'); // 重置新密码 请求
Route::get('password/reset/success', 'Auth\ResetPasswordController@successShow')->name('mobile.reset.success.show'); // 重置密码成功 页面

///*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('mobile.users.home'); // 个人中心 页面
    Route::get('users/{user}/edit', 'UsersController@edit')->name('mobile.users.edit'); // 编辑个人信息 页面
    Route::put('users/{user}', 'UsersController@update')->name('mobile.users.update'); // 编辑个人信息提交 请求
    Route::get('users/{user}/password', 'UsersController@password')->name('mobile.users.password'); // 修改密码 页面
    Route::put('users/{user}/update_password', 'UsersController@updatePassword')->name('mobile.users.update_password'); // 修改密码 请求
    Route::get('users/{user}/setting', 'UsersController@settingShow')->name('mobile.users.setting'); // 设置 页面


    /*收货地址*/
    Route::get('user_addresses', 'UserAddressesController@index')->name('mobile.user_addresses.index'); // 列表 页面
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('mobile.user_addresses.create'); // 新增 页面
    Route::get('user_addresses/{address}/edit', 'UserAddressesController@edit')->name('mobile.user_addresses.edit'); // 编辑 页面
    //Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store'); // 新增 请求
    //Route::put('user_addresses/{userAddress}', 'UserAddressesController@update')->name('user_addresses.update'); // 编辑 请求
    //Route::delete('user_addresses/{userAddress}', 'UserAddressesController@destroy')->name('user_addresses.destroy'); // 删除 请求
    //Route::patch('user_addresses/{userAddress}/set_default', 'UserAddressesController@setDefault')->name('user_addresses.set_default'); // 设置默认

    /*商品收藏*/
    Route::get('user_favourites', 'UserFavouritesController@index')->name('mobile.user_favourites.index'); // 列表 页面
    //Route::post('user_favourites', 'UserFavouritesController@store')->name('user_favourites.store'); // 加入收藏
    //Route::delete('user_favourites/{userFavourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除

    /*浏览历史*/
    Route::get('user_histories', 'UserHistoriesController@index')->name('mobile.user_histories.index'); // 列表 页面
    //Route::delete('user_histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    //Route::delete('user_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*我的订单*/
    Route::get('orders', 'OrdersController@index')->name('mobile.orders.index'); // 列表 页面
    Route::get('orders/list', 'OrdersController@list')->name('mobile.orders.list'); // 获取订单数据 请求 [for Ajax request]
    Route::get('orders/{order}', 'OrdersController@show')->name('mobile.orders.show'); // 详情 页面
    Route::get('orders/{order}/show_shipment', 'OrdersController@showShipment')->name('mobile.orders.show_shipment'); // 物流详情 页面

    // 订单评价
    Route::get('orders/{order}/create_comment', 'OrdersController@createComment')->name('mobile.orders.create_comment'); // 创建订单评价 页面
    Route::post('orders/{order}/store_comment', 'OrdersController@storeComment')->name('mobile.orders.store_comment'); // 发布订单评价 请求 [每款产品都必须发布评价 + 评分]
    Route::get('orders/{order}/show_comment', 'OrdersController@showComment')->name('mobile.orders.show_comment'); // 查看订单评价 页面

});
