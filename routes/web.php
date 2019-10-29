<?php

Route::get('test', 'IndexController@test');

Route::get('locale/{locale}', 'IndexController@localeUpdate')->name('locale.update'); // 修改网站语言
Route::get('currency/{currency}', 'IndexController@currencyUpdate')->name('currency.update'); // 修改币种

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

// Route::resource('example', ExampleController::class);
// Route::get('example', 'ExampleController@index')->name('example.index');
// Route::get('example/create', 'ExampleController@create')->name('example.create');
// Route::get('example/{example}', 'ExampleController@show')->name('example.show');
// Route::get('example/{example}/edit', 'ExampleController@edit')->name('example.edit');
// Route::post('example', 'ExampleController@store')->name('example.store');
// Route::put('example/{example}', 'ExampleController@update')->name('example.update');
// Route::delete('example/{example}', 'ExampleController@destroy')->name('example.destroy');

// Route::redirect('/', 'login')->name('root');/*首页*/
// Route::get('/', 'PagesController@root')->name('root');/*首页*/

Route::get('error', 'PagesController@error')->name('error');/*错误提示页示例*/
Route::get('success', 'PagesController@success')->name('success');/*成功提示页示例*/

Horizon::auth(function ($request) {
    return Auth::guard('admin')->check();
});
Auth::routes();

/*重写原生登录接口*/
Route::post('login', 'Auth\LoginController@login')->name('login.post'); // form表单提交数据，执行登录

/*订单*/
Route::get('orders/pre_payment', 'OrdersController@prePayment')->name('orders.pre_payment'); // 订单预支付页面：选择地址+币种页面
Route::post('orders/pre_payment_by_sku_attr', 'OrdersController@prePaymentBySkuAttr')->name('orders.pre_payment_by_sku_attr'); // 根据 SKU 参数组合 跳转至订单预支付页面
Route::get('orders/search_by_sn/{sn}', 'OrdersController@searchBySn')->name('orders.search_by_sn'); // 根据订单序列号查看订单详情
Route::post('orders', 'OrdersController@store')->name('orders.store'); // 提交订单
Route::post('orders/integrate', 'OrdersController@integrate')->name('orders.integrate'); // 多个订单聚合支付

/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('users.home'); // 主页
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit'); // 编辑个人信息页面
    Route::get('users/{user}/password', 'UsersController@password')->name('users.password'); // 修改密码页面
    Route::put('users/{user}/update_password', 'UsersController@updatePassword')->name('users.update_password'); // 修改密码提交
    Route::get('users/{user}/password_success', 'UsersController@passwordSuccess')->name('users.password_success'); // 修改密码成功 页面
    Route::put('users/{user}', 'UsersController@update')->name('users.update'); // 编辑个人信息提交 & 修改密码提交 & 绑定手机提交

    /*商品收藏*/
    Route::get('user_favourites', 'UserFavouritesController@index')->name('user_favourites.index'); // 列表
    Route::post('user_favourites', 'UserFavouritesController@store')->name('user_favourites.store'); // 加入收藏
    Route::delete('user_favourites/multi_delete', 'UserFavouritesController@multiDelete')->name('user_favourites.multi_delete'); // 删除多条收藏记录
    // Route::delete('user_favourites/{favourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除
    Route::delete('user_favourites', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除

    /*浏览历史*/
    Route::get('user_histories', 'UserHistoriesController@index')->name('user_histories.index'); // 列表
    Route::delete('user_histories/multi_delete', 'UserHistoriesController@multiDelete')->name('user_histories.multi_delete'); // 删除多条浏览历史
    Route::delete('user_histories/{history}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    Route::delete('user_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*收货地址*/
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index'); // 列表
    // Route::get('user_addresses/list_all', 'UserAddressesController@listAll')->name('user_addresses.list_all'); // 获取当前用户收货地址列表 [for Ajax request]
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store'); // 创建用户收货地址表单提交
    Route::post('user_addresses/store_for_ajax', 'UserAddressesController@storeForAjax')->name('user_addresses.store_for_ajax'); // 创建用户收货地址 [for Ajax request]
    Route::put('user_addresses/{address}', 'UserAddressesController@update')->name('user_addresses.update'); // 更新
    Route::delete('user_addresses/{address}', 'UserAddressesController@destroy')->name('user_addresses.destroy'); // 删除
    Route::patch('user_addresses/{address}/set_default', 'UserAddressesController@setDefault')->name('user_addresses.set_default'); // 设置默认

    /*定制商品*/
    // Route::get('products/custom/{product}/{slug?}', 'ProductsController@customShow')->name('products.custom.show'); // 定制商品详情
    // Route::post('products/custom/{product}/{slug?}', 'ProductsController@customStore')->name('products.custom.store'); // 定制商品提交
    // Route::put('products/custom/{product}/{slug?}', 'ProductsController@customUpdate')->name('products.custom.update'); // 定制商品修改

    /*购物车*/
    // Route::get('carts', 'CartsController@index')->name('carts.index'); // 购物车
    // Route::post('carts/store_by_sku_parameters', 'CartsController@storeBySkuParameters')->name('carts.store_by_sku_parameters'); // 加入购物车
    // Route::post('carts', 'CartsController@store')->name('carts.store'); // 加入购物车
    // Route::patch('carts/{cart}', 'CartsController@update')->name('carts.update'); // 更新 (增减数量)
    // Route::delete('carts/{cart}', 'CartsController@destroy')->name('carts.destroy'); // 删除
    // Route::delete('carts', 'CartsController@flush')->name('carts.flush'); // 清空

    /*订单*/
    Route::get('orders', 'OrdersController@index')->name('orders.index'); // 订单列表
    Route::get('orders/get_available_coupons', 'OrdersController@getAvailableCoupons')->name('orders.get_available_coupons'); // 获得当前用户可用的优惠券列表 [for Ajax request]
    Route::get('orders/get_total_shipping_fee', 'OrdersController@getTotalShippingFee')->name('orders.get_total_shipping_fee'); // 获得订单在当前用户地址下的总运费 [for Ajax request]
    // Route::get('orders/pre_payment', 'OrdersController@prePayment')->name('orders.pre_payment'); // 订单预支付页面：选择地址+币种页面
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show'); // 订单详情
    // Route::post('orders', 'OrdersController@store')->name('orders.store'); // 提交订单
    // Route::get('orders/{order}/payment_method', 'OrdersController@paymentMethod')->name('orders.payment_method'); // 选择支付方式页面
    Route::patch('orders/{order}/close', 'OrdersController@close')->name('orders.close'); // [主动]取消订单，交易关闭 [订单进入交易关闭状态:status->closed]
    Route::patch('orders/{order}/complete', 'OrdersController@complete')->name('orders.complete'); // 确认收货，交易关闭 [订单进入交易结束状态:status->completed]
    Route::delete('orders/{order}', 'OrdersController@destroy')->name('orders.destroy'); // 订单删除

    // 订单评价
    Route::get('orders/{order}/create_comment', 'OrdersController@createComment')->name('orders.create_comment'); // 创建订单评价页面
    Route::post('orders/{order}/store_comment', 'OrdersController@storeComment')->name('orders.store_comment'); // 发布订单评价 [每款产品都必须发布评价 + 评分]
    Route::get('orders/{order}/show_comment', 'OrdersController@showComment')->name('orders.show_comment'); // 查看订单评价页面
    // Route::post('orders/{order}/append_comment', 'OrdersController@appendComment')->name('orders.append_comment'); // 追加订单评价 [可针对某一款产品单独追加评论]

    Route::get('orders/{order}/is_paid', 'OrdersController@isPaid')->name('orders.is_paid'); // 判断订单是否已经支付 [for Ajax request]
    Route::get('payments/{payment}/is_completed', 'PaymentsController@isCompleted')->name('payments.is_completed'); // 判断订单支付是否已经完成 [for Ajax request]

    // 售后订单 [仅退款]
    Route::get('orders/{order}/refund', 'OrdersController@refund')->name('orders.refund'); // 退单申请页面
    Route::post('orders/{order}/store_refund', 'OrdersController@storeRefund')->name('orders.store_refund'); // 发起退单申请 [订单进入售后状态:status->refunding]
    Route::put('orders/{order}/update_refund', 'OrdersController@updateRefund')->name('orders.update_refund'); // 提交更新退单申请

    //售后订单 [退货并退款]
    Route::get('orders/{order}/refund_with_shipment', 'OrdersController@refundWithShipment')->name('orders.refund_with_shipment'); // 退单申请页面
    Route::post('orders/{order}/store_refund_with_shipment', 'OrdersController@storeRefundWithShipment')->name('orders.store_refund_with_shipment'); // 发起退单申请 [订单进入售后状态:status->refunding]
    Route::put('orders/{order}/update_refund_with_shipment', 'OrdersController@updateRefundWithShipment')->name('orders.update_refund_with_shipment'); // 提交更新退单申请

    Route::patch('orders/{order}/revoke_refund', 'OrdersController@revokeRefund')->name('orders.revoke_refund'); // 撤销退单申请 [订单恢复状态:status->shipping | receiving]

    /*支付*/
    // Route::get('payments/{payment}/alipay', 'PaymentsController@alipay')->name('payments.alipay'); // Alipay 支付页面
    // Route::get('payments/{payment}/wechat', 'PaymentsController@wechat')->name('payments.wechat'); // Wechat 支付页面
    // Route::get('payments/{payment}/paypal/create', 'PaymentsController@paypalCreate')->name('payments.paypal.create'); // PayPal: create a payment
    // Route::get('payments/{payment}/paypal/get', 'PaymentsController@paypalGet')->name('payments.paypal.get'); // PayPal: get the info of a payment [Test API]
    // Route::get('payments/{payment}/paypal/execute', 'PaymentsController@paypalExecute')->name('payments.paypal.execute'); // PayPal: execute[approve|cancel] a payment

    /*支付回调 [return_url]*/
    // Route::get('payments/{payment}/alipay/return', 'PaymentsController@alipayReturn')->name('payments.alipay.return'); // Alipay 支付回调

    /*退款 [just for test]*/
    Route::post('payments/refund', 'PaymentsController@refund')->name('payments.refund'); // 通用 - 模拟后台发起订单退款
    Route::post('payments/alipay/refund', 'PaymentsController@alipayRefund')->name('payments.alipay.refund'); // Alipay 退款
    Route::post('payments/wechat/refund', 'PaymentsController@wechatRefund')->name('payments.wechat.refund'); // Wechat 退款
    Route::post('payments/paypal/refund', 'PaymentsController@paypalRefund')->name('payments.paypal.refund'); // Paypal 退款

    /*支付成功: Wechat & Paypal*/
    // Route::get('payments/{payment}/success', 'PaymentsController@success')->name('payments.success'); // 通用 - 支付成功页面 [Wechat & Paypal]
});

/*首页*/
Route::get('/', 'IndexController@root')->name('root'); // 首页

/*通用-获取上传图片预览 [应用场景:用户中心]*/
Route::post('image/preview', 'IndexController@imagePreview')->name('image.preview');
/*通用-获取上传Avatar头像图片预览 [应用场景:用户中心]*/
Route::post('image/avatar_preview', 'IndexController@avatarPreview')->name('image.avatar_preview');
/*通用-获取上传Avatar头像图片路径+预览 [应用场景:用户中心]*/
Route::post('image/avatar_upload', 'IndexController@avatarUpload')->name('image.avatar_upload');
/*通用-获取原上传图片路径+预览 [应用场景:退款]*/
Route::post('image/upload', 'IndexController@imageUpload')->name('image.upload');
/*通用-获取评论上传图片路径+预览 [应用场景:评价]*/
Route::post('comment_image/upload', 'IndexController@commentImageUpload')->name('comment_image.upload');
/*通用-单页展示*/
Route::get('articles/{slug}', 'ArticlesController@show')->name('articles.show');

/*商品分类*/
Route::get('product_categories/{category}/{slug?}', 'ProductCategoriesController@index')->name('product_categories.index'); // 商品分类及其商品列表

/*定制商品*/
Route::get('products/custom/{product}/{slug?}', 'ProductsController@customShow')->name('products.custom.show'); // 定制商品详情
Route::post('products/custom/{product}/{slug?}', 'ProductsController@customStore')->name('products.custom.store'); // 定制商品提交
Route::put('products/custom/{product}/{slug?}', 'ProductsController@customUpdate')->name('products.custom.update'); // 定制商品修改

/*修复商品*/
Route::get('products/repair/{product}/{slug?}', 'ProductsController@repairShow')->name('products.repair.show'); // 修复商品详情
Route::post('products/repair/{product}/{slug?}', 'ProductsController@repairStore')->name('products.repair.store'); // 修复商品提交
Route::put('products/repair/{product}/{slug?}', 'ProductsController@repairUpdate')->name('products.repair.update'); // 修复商品修改

/*复制商品*/
Route::get('products/duplicate/{product}/{slug?}', 'ProductsController@duplicateShow')->name('products.duplicate.show'); // 复制商品详情
Route::post('products/duplicate/{product}/{slug?}', 'ProductsController@duplicateStore')->name('products.duplicate.store'); // 复制商品提交
Route::put('products/duplicate/{product}/{slug?}', 'ProductsController@duplicateUpdate')->name('products.duplicate.update'); // 复制商品修改

/*商品*/
Route::get('products/search', 'ProductsController@search')->name('products.search'); // 搜素结果
Route::get('products/search_hint', 'ProductsController@searchHint')->name('products.search_hint'); // 模糊搜素提示结果 [10 records] [for Ajax request]
// Route::get('products/search_by_param', 'ProductsController@searchByParam')->name('products.search_by_param'); // 搜素结果
// Route::get('products/custom/{product}/{slug?}', 'ProductsController@customShow')->name('products.custom.show'); // 定制商品详情
// Route::post('products/custom/{product}/{slug?}', 'ProductsController@customStore')->name('products.custom.store'); // 定制商品提交
// Route::put('products/custom/{product}/{slug?}', 'ProductsController@customUpdate')->name('products.custom.update'); // 定制商品修改
Route::get('products/{product}/comment', 'ProductsController@comment')->name('products.comment'); // 获取商品评价 [for Ajax request]
Route::get('products/{product}/{slug?}', 'ProductsController@show')->name('products.show'); // 商品详情页
Route::post('products/{product}/share', 'ProductsController@share')->name('products.share'); // 发送商品分享邮件 [for Ajax request]
Route::post('products/{product}/search_by_sku_attr', 'ProductsController@searchBySkuAttr')->name('products.search_by_sku_attr'); // 筛选可用的 SKU 属性值

/*购物车*/
Route::get('carts', 'CartsController@index')->name('carts.index'); // 购物车
Route::post('carts', 'CartsController@store')->name('carts.store'); // 加入购物车
Route::post('carts/store_by_sku_attr', 'CartsController@storeBySkuAttr')->name('carts.store_by_sku_attr'); // 根据 SKU 参数组合 加入购物车
Route::patch('carts/update', 'CartsController@update')->name('carts.update'); // 更新 (增减数量)
Route::delete('carts/delete', 'CartsController@destroy')->name('carts.destroy'); // 删除
Route::delete('carts/flush', 'CartsController@flush')->name('carts.flush'); // 清空

/*收货地址*/
Route::get('user_addresses/list_all', 'UserAddressesController@listAll')->name('user_addresses.list_all'); // 获取当前用户收货地址列表 [for Ajax request]
Route::post('user_addresses/store_for_ajax', 'UserAddressesController@storeForAjax')->name('user_addresses.store_for_ajax'); // 创建用户收货地址 [for Ajax request]

/*支付*/
Route::get('payments/{payment}/method', 'PaymentsController@method')->name('payments.method'); // 选择支付方式页面
Route::get('payments/{payment}/alipay', 'PaymentsController@alipay')->name('payments.alipay'); // Alipay 支付页面
Route::get('payments/{payment}/wechat', 'PaymentsController@wechat')->name('payments.wechat'); // Wechat 支付页面
Route::get('payments/{payment}/paypal/create', 'PaymentsController@paypalCreate')->name('payments.paypal.create'); // PayPal: create a payment
// Route::get('payments/{payment}/paypal/get', 'PaymentsController@paypalGet')->name('payments.paypal.get'); // PayPal: get the info of a payment [Test API]
Route::get('payments/{payment}/paypal/execute', 'PaymentsController@paypalExecute')->name('payments.paypal.execute'); // PayPal: execute[approve|cancel] a payment

/*支付回调 [return_url]*/
Route::get('payments/{payment}/alipay/return', 'PaymentsController@alipayReturn')->name('payments.alipay.return'); // Alipay 支付回调

/*支付成功: Wechat & Paypal*/
Route::get('payments/{payment}/success', 'PaymentsController@success')->name('payments.success'); // 通用 - 支付成功页面 [Wechat & Paypal]

/*支付通知 [notify_url]*/
Route::post('payments/{order}/alipay/notify', 'PaymentsController@alipayNotify')->name('payments.alipay.notify'); // Alipay 支付成功通知 [notify_url]
Route::post('payments/{order}/wechat/notify', 'PaymentsController@wechatNotify')->name('payments.wechat.notify'); // Wechat 支付成功通知 [notify_url]
Route::post('payments/{order}/paypal/notify', 'PaymentsController@paypalNotify')->name('payments.paypal.notify'); // PayPal 支付成功通知 [notify_url]

/*手机端 - 微信浏览器内获取用户 open id*/
Route::get('payments/get_wechat_open_id', 'PaymentsController@getWechatOpenId')->name('payments.get_wechat_open_id'); // get wechat open_id

/*留言板*/
Route::post('feedbacks', 'FeedbacksController@store')->name('feedbacks.store'); // 提交订阅|发布留言

/*Socialites*/
Route::get('socialites/login/{socialite}', 'SocialitesController@login')->name('socialites.login'); // Socialite Login Url
Route::get('socialites/callback/{socialite}', 'SocialitesController@callback')->name('socialites.callback'); // Socialite Callback Url
Route::get('socialites/deauthorize/{socialite}', 'SocialitesController@deauthorize')->name('socialites.deauthorize'); // Socialite Deauthorize Url
Route::get('socialites/delete/{socialite}', 'SocialitesController@delete')->name('socialites.delete'); // Socialite Delete Url

Route::get('contact_us.html', 'IndexController@contactUs')->name('contact_us');
Route::get('about_lyricalhair.html', 'IndexController@aboutLyricalhair')->name('about_lyricalhair');
Route::get('why_lyricalhair.html', 'IndexController@whyLyricalhair')->name('why_lyricalhair');

Route::get('{slug}.html', 'IndexController@seoUrl')->name('seo_url');

