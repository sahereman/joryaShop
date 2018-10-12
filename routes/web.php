<?php

Route::get('test', function () {
    dd('test');
});

/*通过邮箱验证码重置密码*/
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); // 忘记密码，通过邮箱重置密码页面
Route::post('password/reset/send_email_code', 'Auth\ResetPasswordController@sendEmailCode')->name('reset.send_email_code'); // 发送邮箱验证码
Route::post('password/reset/resend_email_code', 'Auth\ResetPasswordController@resendEmailCode')->name('reset.resend_email_code'); // 再次发送邮箱验证码 [for Ajax request]
Route::get('password/reset/input_email_code', 'Auth\ResetPasswordController@inputEmailCode')->name('reset.input_email_code'); // 输入邮箱验证码页面
Route::post('password/reset/verify_email_code', 'Auth\ResetPasswordController@verifyEmailCode')->name('reset.verify_email_code'); // 验证邮箱验证码
Route::get('password/reset/override', 'Auth\ResetPasswordController@override')->name('reset.override'); // 重复输入新密码页面
Route::post('password/reset/override_password', 'Auth\ResetPasswordController@overridePassword')->name('reset.override_password'); // 重置密码为新密码
Route::get('password/reset/success', 'Auth\ResetPasswordController@success')->name('reset.success'); // 通过邮箱验证码重置密码成功页面
// $this->post('password/reset', 'Auth\ResetPasswordController@reset');

/*// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');*/

//    Route::resource('example', ExampleController::class);
//    Route::get('example', 'ExampleController@index')->name('example.index');
//    Route::get('example/create', 'ExampleController@create')->name('example.create');
//    Route::get('example/{example}', 'ExampleController@show')->name('example.show');
//    Route::get('example/{example}/edit', 'ExampleController@edit')->name('example.edit');
//    Route::post('example', 'ExampleController@store')->name('example.store');
//    Route::put('example/{example}', 'ExampleController@update')->name('example.update');
//    Route::delete('example/{example}', 'ExampleController@destroy')->name('example.destroy');

//Route::redirect('/', 'login')->name('root');/*首页*/
//Route::get('/', 'PagesController@root')->name('root');/*首页*/

Route::get('error', 'PagesController@error')->name('error');/*错误提示页示例*/
Route::get('success', 'PagesController@success')->name('success');/*成功提示页示例*/

Horizon::auth(function ($request) {
    return Auth::guard('admin')->check();
});
Auth::routes();

/*通过邮箱验证码登录*/
Route::post('login/send_email_code', 'Auth\LoginController@sendEmailCode')->name('login.send_email_code'); // 发送邮箱验证码 [for Ajax request]
Route::post('login/verify_email_code', 'Auth\LoginController@verifyEmailCode')->name('login.verify_email_code'); // 验证邮箱验证码 [for Ajax request]

/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('users.home'); // 主页
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit'); // 编辑个人信息页面
    Route::get('users/{user}/password', 'UsersController@password')->name('users.password'); // 修改密码页面
    Route::get('users/{user}/update_phone', 'UsersController@updatePhone')->name('users.update_phone'); // 修改手机页面
    Route::get('users/{user}/binding_phone', 'UsersController@bindingPhone')->name('users.binding_phone'); // 绑定手机页面
    Route::put('users/{user}', 'UsersController@update')->name('users.update'); // 编辑个人信息提交 & 修改密码提交 & 绑定手机提交

    /*商品收藏*/
    Route::get('user_favourites', 'UserFavouritesController@index')->name('user_favourites.index'); // 列表
    Route::post('user_favourites', 'UserFavouritesController@store')->name('user_favourites.store'); // 加入收藏
    Route::delete('user_favourites/{userFavourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除

    /*浏览历史*/
    Route::get('user_histories', 'UserHistoriesController@index')->name('user_histories.index'); // 列表
    // TODO ...
    // Route::post('user_histories', 'UserHistoriesController@store')->name('user_histories.store'); // 队列追加浏览历史
    Route::delete('user_histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    Route::delete('user_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*收货地址*/
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index'); // 列表
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create'); // 创建
    Route::get('user_addresses/{userAddress}', 'UserAddressesController@edit')->name('user_addresses.edit'); // 编辑
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store'); // 提交
    Route::put('user_addresses/{userAddress}', 'UserAddressesController@update')->name('user_addresses.update'); // 更新
    Route::delete('user_addresses/{userAddress}', 'UserAddressesController@destroy')->name('user_addresses.destroy'); // 删除
    Route::patch('user_addresses/{userAddress}/set_default', 'UserAddressesController@setDefault')->name('user_addresses.set_default'); // 设置默认

    /*购物车*/
    Route::get('carts', 'CartsController@index')->name('carts.index'); // 购物车
    Route::post('carts', 'CartsController@store')->name('carts.store'); // 加入购物车
    Route::patch('carts/{cart}', 'CartsController@update')->name('carts.update'); // 更新 (增减数量)
    Route::delete('carts/{cart}', 'CartsController@destroy')->name('carts.destroy'); // 删除
    Route::delete('carts', 'CartsController@flush')->name('carts.flush'); // 清空

    /*订单*/
    Route::get('orders', 'OrdersController@index')->name('orders.index'); // 订单列表
    Route::get('orders/create', 'OrdersController@create')->name('orders.create'); // 提交订单页面 (参数:购物车ids or 立即购买sku_id)
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show'); // 订单详情
    Route::post('orders', 'OrdersController@store')->name('orders.store'); // 提交订单
    Route::get('orders/{order}/payment_method', 'OrdersController@paymentMethod')->name('orders.payment_method'); // 选择支付方式页面
    Route::patch('orders/{order}/close', 'OrdersController@close')->name('orders.close'); // [主动|被动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    Route::patch('orders/{order}/ship', 'OrdersController@ship')->name('orders.ship'); // 卖家配送发货 [订单进入待收货状态:status->receiving]
    Route::patch('orders/{order}/complete', 'OrdersController@complete')->name('orders.complete'); // 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    Route::delete('orders/{order}', 'OrdersController@destroy')->name('orders.destroy'); // 订单删除
    Route::get('orders/{order}/comments', 'OrdersController@comments')->name('orders.comments'); // 订单评价 [api-for-ajax-request]
    Route::post('orders/{order}/post_comments', 'OrdersController@postComments')->name('orders.post_comments'); // 发布评价
    Route::post('orders/{order}/post_comments', 'OrdersController@postComments')->name('orders.post_comments'); // 发布评价
    Route::get('orders/{order}/refund', 'OrdersController@refund')->name('orders.refund'); // 申请退单页面
    Route::post('orders/{order}/post_refund', 'OrdersController@postRefund')->name('orders.post_refund'); // 发起退单申请 [订单进入售后状态:status->refunding]
    Route::post('orders/{order}/update_refund', 'OrdersController@updateRefund')->name('orders.update_refund'); // 更新退单申请信息 [订单进入售后状态]

    /*支付*/
    Route::get('payments/{order}/alipay', 'PaymentsController@alipay')->name('payments.alipay'); // 支付宝支付
    Route::get('payments/{order}/wechat', 'PaymentsController@wechat')->name('payments.wechat'); // 微信支付
    Route::get('payments/{order}/paypal', 'PaymentsController@paypal')->name('payments.paypal'); // PayPal支付
    Route::get('payments/{order}/success', 'PaymentsController@success')->name('payments.success'); // 支付成功页面 [notify_url]


});

/*首页*/
Route::get('/', 'IndexController@root')->name('root'); // 首页

/*商品分类*/
Route::get('product_categories/{category}', 'ProductCategoriesController@index')->name('product_categories.index'); // 列表
Route::get('product_categories/{category}/home', 'ProductCategoriesController@home')->name('product_categories.home'); // 商品分类呈现[一|二级分类]

/*商品*/
Route::get('products', 'ProductsController@index')->name('products.index'); // 列表 | 搜素结果
Route::get('products/{product}', 'ProductsController@show')->name('products.show'); // 详情
Route::get('products/{product}/comments', 'ProductsController@comments')->name('products.comments'); // 评价 [api-for-ajax-request]

/*通用-单页展示*/
Route::get('pages/{page}', 'PagesController@show')->name('pages.show');

/*通用-广告展示*/
Route::get('posters/{poster}', 'PostersController@show')->name('posters.show');

/*支付回调 [return_url]*/
Route::post('payments/alipay/callback', 'PaymentsController@alipayCallback')->name('payments.alipay.callback'); // 支付宝支付回调
Route::post('payments/wechat/callback', 'PaymentsController@wechatCallback')->name('payments.wechat.callback'); // 微信支付回调
Route::post('payments/paypal/callback', 'PaymentsController@paypalCallback')->name('payments.paypal.callback'); // PayPal支付回调
