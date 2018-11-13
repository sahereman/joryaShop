<?php

//以下所有路由 URL 为 site.com/mobile/ + 路由URL

Route::get('test', function () {

    App::setLocale('en');


    return 'test';


//    dd(
//        $locale = App::getLocale()
//
//    );
//
//    return true;
//
//    dd(session()->all());
//
//    //    dd(\App\Models\CountryCode::countryCodes());
//    dd('test mobile');
});


/*首页*/
Route::get('/', 'IndexController@root')->name('mobile.root'); // 首页


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
//Route::group(['middleware' => 'auth'], function () {

/*用户中心*/
Route::get('users', 'UsersController@home')->name('mobile.users.home'); // 个人中心 页面
Route::get('users/{user}/edit', 'UsersController@edit')->name('mobile.users.edit'); // 编辑个人信息 页面
Route::put('users/{user}', 'UsersController@update')->name('users.update'); // 编辑个人信息提交 请求
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


//});

//Route::post('password/reset/send_sms_code', 'Auth\ResetPasswordController@sendSmsCode')->name('reset.send_sms_code'); // 校验国家|地区码+手机号码，并跳转下一步
//Route::get('password/reset/input_sms_code', 'Auth\ResetPasswordController@inputSmsCode')->name('reset.input_sms_code'); // 输入短信验证码页面
//Route::post('password/reset/resend_sms_code', 'Auth\ResetPasswordController@resendSmsCode')->name('reset.resend_sms_code'); // 发送短信验证码 [for Ajax request]
//Route::post('password/reset/verify_sms_code', 'Auth\ResetPasswordController@verifySmsCode')->name('reset.verify_sms_code'); // 验证短信验证码
//Route::get('password/reset/override', 'Auth\ResetPasswordController@override')->name('reset.override'); // 重复输入新密码页面
//Route::post('password/reset/override_password', 'Auth\ResetPasswordController@overridePassword')->name('reset.override_password'); // 重置密码为新密码
//Route::get('password/reset/success', 'Auth\ResetPasswordController@success')->name('reset.success'); // 通过短信验证码重置密码成功页面
