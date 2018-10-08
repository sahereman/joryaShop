<?php

Route::get('test', function () {
    dd('test');
});


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
//    Route::get('example/{example}', 'ExampleController@show')->name('example.show');
//    Route::get('example/create', 'ExampleController@create')->name('example.create');
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

/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户中心*/
    Route::get('users', 'UsersController@home')->name('users.home'); // 主页
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit'); // 编辑个人信息页面
    Route::get('users/{user}/password', 'UsersController@password')->name('users.password'); // 修改密码页面
    Route::get('users/{user}/binding_Phone', 'UsersController@bindingPhone')->name('users.binding_phone'); // 绑定手机页面
    Route::put('users/{user}', 'UsersController@update')->name('users.update'); // 编辑个人信息提交 & 修改密码提交 & 绑定手机提交

    /*商品收藏*/
    Route::get('users_favourites', 'UserFavouritesController@index')->name('user_favourites.index'); // 列表
    Route::post('users_favourites', 'UserFavouritesController@store')->name('user_favourites.store'); // 加入收藏
    Route::delete('users_favourites/{userFavourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy'); // 删除

    /*浏览历史*/
    Route::get('users_histories', 'UserHistoriesController@index')->name('user_histories.index'); // 列表
    Route::delete('users_histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy'); // 删除
    Route::delete('users_histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空

    /*收货地址*/
    Route::get('users_addresses', 'UserAddressesController@index')->name('user_addresses.index'); // 列表
    Route::get('users_addresses/create', 'UserAddressesController@create')->name('user_addresses.create'); // 创建
    Route::get('users_addresses/edit', 'UserAddressesController@edit')->name('user_addresses.edit'); // 编辑
    Route::post('users_addresses', 'UserAddressesController@store')->name('user_addresses.store'); // 提交
    Route::put('users_addresses/{userAddress}', 'UserAddressesController@update')->name('user_addresses.update'); // 更新
    Route::delete('users_addresses/{userAddress}', 'UserAddressesController@destroy')->name('user_addresses.destroy'); // 删除
    Route::patch('users_addresses/{userAddress}/set_default', 'UserAddressesController@setDefault')->name('user_addresses.set_default'); // 设置默认

    /*购物车*/
    Route::get('carts', 'CartsController@index')->name('carts.index'); // 购物车
    Route::post('carts', 'CartsController@store')->name('carts.store'); // 加入购物车
    Route::patch('carts/{cart}', 'CartsController@update')->name('carts.update'); //更新 (增减数量)
    Route::delete('carts/{cart}', 'CartsController@destroy')->name('carts.destroy'); //删除
    Route::delete('carts', 'CartsController@flush')->name('carts.flush'); // 清空

    /*订单*/
    Route::get('orders', 'OrdersController@index')->name('orders.index'); // 订单列表
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show'); // 订单详情
    Route::get('orders/create', 'OrdersController@create')->name('orders.create'); // 提交订单页面 (参数:购物车ids or 立即购买sku_id)
    Route::post('orders', 'OrdersController@store')->name('orders.store'); // 提交订单
    Route::get('orders/{order}/pay_method', 'OrdersController@payMethod')->name('orders.pay_method'); // 选择支付方式页面
    Route::patch('orders/{order}/close', 'OrdersController@close')->name('orders.close'); // 取消订单
    Route::patch('orders/{order}/receive', 'OrdersController@receive')->name('orders.receive'); // 确认收货
    Route::delete('orders/{order}', 'OrdersController@destroy')->name('orders.destroy'); // 订单删除

    /*支付*/
    Route::get('payments/{order}/alipay', 'PaymentsController@alipay')->name('payments.alipay'); // 支付宝支付
    Route::get('payments/{order}/wechat', 'PaymentsController@wechat')->name('payments.wechat'); // 微信支付
    Route::get('payments/{order}/paypal', 'PaymentsController@paypal')->name('payments.paypal'); // PayPal支付
    Route::get('payments/{order}/success', 'PaymentsController@success')->name('payments.success'); // 支付成功页面


});

/*首页*/
Route::get('/', 'IndexController@root')->name('root'); // 首页

/*商品分类*/
Route::get('product_categories/{category}', 'ProductCategoriesController@index')->name('product_categories.index'); // 列表
Route::get('product_categories/{category}/home', 'ProductCategoriesController@home')->name('product_categories.home'); // 商品分类呈现[一|二级分类]

/*商品*/
Route::get('products', 'ProductsController@index')->name('products.search'); // 列表
Route::get('products/{product}', 'ProductsController@show')->name('products.show'); // 详情
Route::get('products/{product}/comments', 'ProductsController@comments')->name('products.comments'); // 评价

/*通用-单页展示*/
Route::get('pages/{page}', 'PagesController@show')->name('pages.show');

/*支付回调*/
Route::post('payments/alipay/return', 'PaymentsController@alipayReturn')->name('payments.alipay.return'); // 支付宝回调
Route::post('payments/wechat/return', 'PaymentsController@wechatReturn')->name('payments.wechat.return'); // 微信支付
Route::post('payments/paypal/return', 'PaymentsController@paypalReturn')->name('payments.paypal.return'); // PayPal支付
