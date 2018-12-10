<?php

//以下所有路由 URL 为 site.com/mobile/ + 路由URL

Route::get('test', function () {
    App::setLocale('en');
    return 'test';
});


/*首页*/
Route::get('/', 'IndexController@root')->name('mobile.root'); // 首页
Route::get('guess_more', 'IndexController@guessMore')->name('mobile.guess_more'); // guess more ... @ mobile root page [for Ajax request]
Route::get('search', 'IndexController@search')->name('mobile.search'); // 搜索 页面 [仅展示页面]
Route::get('locale', 'IndexController@localeShow')->name('mobile.locale.show'); // 修改网站语言 页面

/*通用-单页展示*/
Route::get('articles/{slug}', 'ArticlesController@show')->name('mobile.articles.show');

/*注册与登录*/
Route::get('login', 'Auth\LoginController@showLoginForm')->name('mobile.login.show'); // 登录 页面
Route::post('login', 'Auth\LoginController@login')->name('mobile.login.store'); // 登录 请求
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('mobile.register.show'); // 注册 页面
Route::post('register', 'Auth\RegisterController@register')->name('mobile.register.store'); // 注册 请求
Route::post('logout', 'Auth\LoginController@logout')->name('mobile.logout'); // Log out


/*重置密码*/
Route::get('password/reset/sms', 'Auth\ResetPasswordController@smsShow')->name('mobile.reset.sms.show'); // 重置密码(短信方式) 页面
Route::post('password/reset/sms', 'Auth\ResetPasswordController@smsSubmit')->name('mobile.reset.sms.store'); // 重置密码(短信方式) 请求
Route::get('password/reset/override', 'Auth\ResetPasswordController@overrideShow')->name('mobile.reset.override.show'); // 重置新密码 页面
Route::post('password/reset/override', 'Auth\ResetPasswordController@overrideSubmit')->name('mobile.reset.override.store'); // 重置新密码 请求
Route::get('password/reset/success', 'Auth\ResetPasswordController@successShow')->name('mobile.reset.success.show'); // 重置密码成功 页面

/*商品分类*/
Route::get('product_categories', 'ProductCategoriesController@index')->name('mobile.product_categories.index'); // 商品分类展示首页 [仅展示页面]
Route::get('product_categories/{category}/more', 'ProductCategoriesController@more')->name('mobile.product_categories.more'); // 商品一级分类展示列表 下拉加载更多 [for Ajax request]

/*商品*/
Route::get('products/search', 'ProductsController@search')->name('mobile.products.search'); // 商品搜索结果 页面 [仅展示页面]
Route::get('products/{product}', 'ProductsController@show')->name('mobile.products.show'); // 商品详情 页面

/*手机端 - 微信浏览器内获取用户 open id*/
Route::get('payments/get_wechat_open_id', 'PaymentsController@getWechatOpenId')->name('mobile.payments.get_wechat_open_id'); // get wechat open_id

/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('mobile.users.home'); // 个人中心 页面
    Route::get('users/{user}/setting', 'UsersController@setting')->name('mobile.users.setting'); // 个人中心设置 页面
    Route::get('users/{user}/edit', 'UsersController@edit')->name('mobile.users.edit'); // 编辑个人信息 页面
    Route::put('users/{user}', 'UsersController@update')->name('mobile.users.update'); // 编辑个人信息提交 请求
    Route::get('users/{user}/password', 'UsersController@password')->name('mobile.users.password'); // 修改密码 页面
    Route::get('users/{user}/password', 'UsersController@password')->name('mobile.users.password'); // 修改密码 页面
    Route::put('users/{user}/update_password', 'UsersController@updatePassword')->name('mobile.users.update_password'); // 修改密码 请求
    Route::get('users/{user}/password_success', 'UsersController@passwordSuccess')->name('mobile.users.password_success'); // 修改密码成功 页面

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
    Route::get('user_histories', 'UserHistoriesController@index')->name('mobile.user_histories.index'); // 列表 [仅展示页面]
    Route::get('user_histories/more', 'UserHistoriesController@more')->name('mobile.user_histories.more'); // 列表 下拉加载更多 [for Ajax request]
    //Route::delete('user_histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    //Route::delete('user_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*我的订单*/
    Route::get('orders', 'OrdersController@index')->name('mobile.orders.index'); // 列表 页面
    Route::get('orders/more', 'OrdersController@more')->name('mobile.orders.more'); // 获取订单数据 请求 [for Ajax request]
    Route::get('orders/pre_payment', 'OrdersController@prePayment')->name('mobile.orders.pre_payment'); // 订单预支付页面：选择地址+币种页面
    Route::get('orders/{order}/payment_method', 'OrdersController@paymentMethod')->name('mobile.orders.payment_method'); // 选择支付方式页面
    Route::get('orders/{order}', 'OrdersController@show')->name('mobile.orders.show'); // 详情 页面
    Route::get('orders/{order}/show_shipment', 'OrdersController@showShipment')->name('mobile.orders.show_shipment'); // 物流详情 页面
    Route::get('orders/{order}/refund', 'OrdersController@refund')->name('mobile.orders.refund'); // 售后订单 [仅退款] 退单申请页面
    Route::get('orders/{order}/refund_with_shipment', 'OrdersController@refundWithShipment')->name('mobile.orders.refund_with_shipment'); //售后订单 [退货并退款] 退单申请页面

    /*订单评价*/
    Route::get('orders/{order}/create_comment', 'OrdersController@createComment')->name('mobile.orders.create_comment'); // 创建订单评价 页面
    Route::get('orders/{order}/show_comment', 'OrdersController@showComment')->name('mobile.orders.show_comment'); // 查看订单评价 页面

    /*购物车*/
    Route::get('carts', 'CartsController@index')->name('mobile.carts.index'); // 购物车 页面

    /*支付*/
    Route::get('payments/{order}/alipay/wap', 'PaymentsController@alipayWap')->name('mobile.payments.alipay.wap'); // Alipay Mobile-Wap 支付页面
    Route::get('payments/{order}/wechat/mp', 'PaymentsController@wechatMp')->name('mobile.payments.wechat.mp')->middleware('openid'); // Wechat Mobile-MP(公众号) 支付页面
    Route::get('payments/{order}/wechat/wap', 'PaymentsController@wechatWap')->name('mobile.payments.wechat.wap'); // Wechat Mobile-Wap 支付页面
    Route::get('payments/{order}/paypal/create', 'PaymentsController@paypalCreate')->name('mobile.payments.paypal.create'); // PayPal: create a payment
    // Route::get('payments/{order}/paypal/get', 'PaymentsController@paypalGet')->name('mobile.payments.paypal.get'); // PayPal: get the info of a payment [Test API]
    Route::get('payments/{order}/paypal/execute', 'PaymentsController@paypalExecute')->name('mobile.payments.paypal.execute'); // PayPal: execute[approve|cancel] a payment

    /*支付回调 [return_url]*/
    Route::get('payments/{order}/alipay/return', 'PaymentsController@alipayReturn')->name('mobile.payments.alipay.return'); // Alipay 支付回调

    /*支付成功: Wechat & Paypal*/
    Route::get('payments/{order}/success', 'PaymentsController@success')->name('mobile.payments.success'); // 通用 - 支付成功页面 [Wechat & Paypal]
});
