<?php

Route::get('test', function () {
    dd('test');
});

/*通过邮箱验证码登录*/
// Route::post('login/send_email_code', 'Auth\LoginController@sendEmailCode')->name('login.send_email_code'); // 发送邮箱验证码 [for Ajax request]
// Route::post('login/verify_email_code', 'Auth\LoginController@verifyEmailCode')->name('login.verify_email_code'); // 验证邮箱验证码 [for Ajax request]

/*通过短信验证码登录*/
Route::post('login/send_sms_code', 'Auth\LoginController@sendSmsCode')->name('login.send_sms_code'); // 发送短信验证码 [for Ajax request]
Route::post('login/verify_sms_code', 'Auth\LoginController@verifySmsCode')->name('login.verify_sms_code'); // 验证短信验证码 [for Ajax request]

/*通过邮箱验证码重置密码*/
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); // 忘记密码，通过邮箱重置密码页面
// Route::post('password/reset/send_email_code', 'Auth\ResetPasswordController@sendEmailCode')->name('reset.send_email_code'); // 校验邮箱，并跳转下一步
// Route::get('password/reset/input_email_code', 'Auth\ResetPasswordController@inputEmailCode')->name('reset.input_email_code'); // 输入邮箱验证码页面
// Route::post('password/reset/resend_email_code', 'Auth\ResetPasswordController@resendEmailCode')->name('reset.resend_email_code'); // 发送邮箱验证码 [for Ajax request]
// Route::post('password/reset/verify_email_code', 'Auth\ResetPasswordController@verifyEmailCode')->name('reset.verify_email_code'); // 验证邮箱验证码
// Route::get('password/reset/override', 'Auth\ResetPasswordController@override')->name('reset.override'); // 重复输入新密码页面
// Route::post('password/reset/override_password', 'Auth\ResetPasswordController@overridePassword')->name('reset.override_password'); // 重置密码为新密码
// Route::get('password/reset/success', 'Auth\ResetPasswordController@success')->name('reset.success'); // 通过邮箱验证码重置密码成功页面
// $this->post('password/reset', 'Auth\ResetPasswordController@reset');

/*通过短信验证码重置密码*/
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); // 忘记密码，通过短信重置密码页面
Route::post('password/reset/send_sms_code', 'Auth\ResetPasswordController@sendSmsCode')->name('reset.send_sms_code'); // 校验国家|地区码+手机号码，并跳转下一步
Route::get('password/reset/input_sms_code', 'Auth\ResetPasswordController@inputSmsCode')->name('reset.input_sms_code'); // 输入短信验证码页面
Route::post('password/reset/resend_sms_code', 'Auth\ResetPasswordController@resendSmsCode')->name('reset.resend_sms_code'); // 发送短信验证码 [for Ajax request]
Route::post('password/reset/verify_sms_code', 'Auth\ResetPasswordController@verifySmsCode')->name('reset.verify_sms_code'); // 验证短信验证码
Route::get('password/reset/override', 'Auth\ResetPasswordController@override')->name('reset.override'); // 重复输入新密码页面
Route::post('password/reset/override_password', 'Auth\ResetPasswordController@overridePassword')->name('reset.override_password'); // 重置密码为新密码
Route::get('password/reset/success', 'Auth\ResetPasswordController@success')->name('reset.success'); // 通过短信验证码重置密码成功页面
// $this->post('password/reset', 'Auth\ResetPasswordController@reset');

/*通过邮箱验证码注册*/
// Route::post('register/send_email_code', 'Auth\RegisterController@sendEmailCode')->name('register.send_email_code'); // 发送邮箱验证码 [for Ajax request]

/*通过短信验证码注册*/
Route::post('register/send_sms_code', 'Auth\RegisterController@sendSmsCode')->name('register.send_sms_code'); // 发送短信验证码 [for Ajax request]

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

/*重写原生登录接口*/
Route::post('login', 'Auth\LoginController@login')->name('login.post'); // form表单提交数据，执行登录

/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('users.home'); // 主页
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit'); // 编辑个人信息页面
    Route::get('users/{user}/password', 'UsersController@password')->name('users.password'); // 修改密码页面
    Route::put('users/{user}/update_password', 'UsersController@updatePassword')->name('users.update_password'); // 修改密码提交
    // Route::get('users/{user}/phone', 'UsersController@phone')->name('users.phone'); // 绑定|修改手机页面
    // Route::get('users/{user}/binding_phone', 'UsersController@bindingPhone')->name('users.binding_phone'); // 绑定手机页面
    Route::get('users/{user}/email', 'UsersController@email')->name('users.email'); // 绑定|修改Email页面
    // Route::get('users/{user}/binding_email', 'UsersController@bindingEmail')->name('users.binding_email'); // 绑定Email页面
    Route::put('users/{user}', 'UsersController@update')->name('users.update'); // 编辑个人信息提交 & 修改密码提交 & 绑定手机提交

    /*商品收藏*/
    Route::get('user_favourites', 'UserFavouritesController@index')->name('user_favourites.index'); // 列表
    Route::post('user_favourites', 'UserFavouritesController@store')->name('user_favourites.store'); // 加入收藏
    Route::delete('user_favourites/{userFavourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除

    /*浏览历史*/
    Route::get('user_histories', 'UserHistoriesController@index')->name('user_histories.index'); // 列表
    Route::delete('user_histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    Route::delete('user_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*收货地址*/
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index'); // 列表
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
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show'); // 订单详情
    Route::get('orders/pre_payment', 'OrdersController@prePayment')->name('orders.pre_payment'); // 选择地址+币种页面
    Route::post('orders', 'OrdersController@store')->name('orders.store'); // 提交订单
    Route::get('orders/{order}/payment_method', 'OrdersController@paymentMethod')->name('orders.payment_method'); // 选择支付方式页面
    Route::patch('orders/{order}/close', 'OrdersController@close')->name('orders.close'); // [主动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    // Route::patch('orders/{order}/ship', 'OrdersController@ship')->name('orders.ship'); // 卖家配送发货 [订单进入待收货状态:status->receiving]
    Route::patch('orders/{order}/complete', 'OrdersController@complete')->name('orders.complete'); // 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    Route::delete('orders/{order}', 'OrdersController@destroy')->name('orders.destroy'); // 订单删除

    // 订单评价
    Route::get('orders/{order}/create_comment', 'OrdersController@createComment')->name('orders.create_comment'); // 创建订单评价页面
    Route::post('orders/{order}/store_comment', 'OrdersController@storeComment')->name('orders.store_comment'); // 发布订单评价 [每款产品都必须发布评价 + 评分]
    Route::get('orders/{order}/show_comment', 'OrdersController@showComment')->name('orders.show_comment'); // 查看订单评价页面
    // Route::post('orders/{order}/append_comment', 'OrdersController@appendComment')->name('orders.append_comment'); // 追加订单评价 [可针对某一款产品单独追加评论]

    // 售后订单 [仅退款]
    Route::get('orders/{order}/refund', 'OrdersController@refund')->name('orders.refund'); // 退单申请页面
    Route::post('orders/{order}/store_refund', 'OrdersController@storeRefund')->name('orders.store_refund'); // 发起退单申请 [订单进入售后状态:status->refunding]
    // Route::get('orders/{order}/edit_refund', 'OrdersController@editRefund')->name('orders.edit_refund'); // 更新退单申请页面
    Route::put('orders/{order}/update_refund', 'OrdersController@updateRefund')->name('orders.update_refund'); // 提交更新退单申请

    //售后订单 [退货并退款]
    Route::get('orders/{order}/refund_with_shipment', 'OrdersController@refundWithShipment')->name('orders.refund_with_shipment'); // 退单申请页面
    Route::post('orders/{order}/store_refund_with_shipment', 'OrdersController@storeRefundWithShipment')->name('orders.store_refund_with_shipment'); // 发起退单申请 [订单进入售后状态:status->refunding]
    // Route::get('orders/{order}/edit_refund_with_shipment', 'OrdersController@editRefundWithShipment')->name('orders.edit_refund_with_shipment'); // 更新退单申请页面
    Route::put('orders/{order}/update_refund_with_shipment', 'OrdersController@updateRefundWithShipment')->name('orders.update_refund_with_shipment'); // 提交更新退单申请

    Route::patch('orders/{order}/revoke_refund', 'OrdersController@revokeRefund')->name('orders.revoke_refund'); // 撤销退单申请 [订单恢复状态:status->shipping | receiving]

    /*支付*/
    Route::get('payments/{order}/alipay', 'PaymentsController@alipay')->name('payments.alipay'); // Alipay 支付页面
    Route::get('payments/{order}/wechat', 'PaymentsController@wechat')->name('payments.wechat'); // Wechat 支付页面
    Route::get('payments/{order}/paypal', 'PaymentsController@paypal')->name('payments.paypal'); // PayPal 支付页面

    /*支付回调 [return_url]*/
    Route::get('payments/alipay/return', 'PaymentsController@alipayReturn')->name('payments.alipay.return'); // Alipay 支付回调
    Route::get('payments/wechat/return', 'PaymentsController@wechatReturn')->name('payments.wechat.return'); // Wechat 支付回调
    Route::get('payments/paypal/return', 'PaymentsController@paypalReturn')->name('payments.paypal.return'); // PayPal 支付回调

});

/*首页*/
Route::get('/', 'IndexController@root')->name('root'); // 首页

/*通用-获取上传图片预览*/
Route::post('image/preview', 'IndexController@imagePreview')->name('image.preview');
/*通用-获取原上传图片路径+预览*/
Route::post('image/upload', 'IndexController@imageUpload')->name('image.upload');
/*通用-获取评论上传图片路径+预览*/
Route::post('comment_image/upload', 'IndexController@commentImageUpload')->name('comment_image.upload');

/*通用-获取国家|地区码列表*/
Route::get('country_codes', 'CountryCodesController@index')->name('country_codes.index');

/*通用-获取物流公司列表*/
Route::get('shipment_companies', 'ShipmentCompaniesController@index')->name('shipment_companies.index');

/*通用-Aliyun发送短信 [目前仅用于用户注册、登录、重置密码时发送验证码]*/
Route::post('easy_sms_send', 'IndexController@easySmsSend')->name('easy_sms_send');

/*通用-快递100 API 实时查询订单物流状态*/
// Route::get('orders/{order}/shipment_query', 'OrdersController@shipmentQuery')->name('orders.shipment_query');

/*商品分类*/
Route::get('product_categories/{category}', 'ProductCategoriesController@index')->name('product_categories.index'); // 一级分类及其商品列表 [完整展示] or 二级分类及其商品列表 [下拉加载更多]

/*商品*/
// Route::get('products', 'ProductsController@index')->name('products.index'); // 二级分类及其商品列表 [下拉加载更多]
Route::get('products/search', 'ProductsController@search')->name('products.search'); // 搜素结果 [下拉加载更多]
Route::get('products/search_hint', 'ProductsController@searchHint')->name('products.search_hint'); // 模糊搜素提示结果 [10 records] [for Ajax request]
Route::get('products/{product}', 'ProductsController@show')->name('products.show'); // 商品详情页
Route::get('products/{product}/comment', 'ProductsController@comment')->name('products.comment'); // 获取商品评价 [for Ajax request]

/*通用-单页展示*/
Route::get('pages/{page}', 'PagesController@show')->name('pages.show');

/*通用-广告展示*/
Route::get('posters/{poster}', 'PostersController@show')->name('posters.show');

/*支付通知 [notify_url]*/
Route::post('payments/alipay/notify', 'PaymentsController@alipayNotify')->name('payments.alipay.notify'); // Alipay 支付成功通知 [notify_url]
Route::post('payments/wechat/notify', 'PaymentsController@wechatNotify')->name('payments.wechat.notify'); // Wechat 支付成功通知 [notify_url]
Route::post('payments/paypal/notify', 'PaymentsController@paypalNotify')->name('payments.paypal.notify'); // PayPal 支付成功通知 [notify_url]

// for test:
Route::get('payments/alipay/refund', 'PaymentsController@alipayRefund')->name('payments.alipay.refund'); // Alipay 退款
Route::get('payments/wechat/refund', 'PaymentsController@wechatRefund')->name('payments.wechat.refund'); // Wechat 退款
Route::get('payments/paypal/refund', 'PaymentsController@paypalRefund')->name('payments.paypal.refund'); // Paypal 退款
