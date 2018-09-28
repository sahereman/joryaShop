<?php

Route::get('test', function () {
    dd('test');
});


Horizon::auth(function ($request) {
    return Auth::guard('admin')->check();
});
Auth::routes();

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


//Route::redirect('/', 'login')->name('root');/*首页*/
//Route::get('/', 'PagesController@root')->name('root');/*首页*/
Route::get('error', 'PagesController@error')->name('error');/*错误提示页示例*/
Route::get('success', 'PagesController@success')->name('success');/*成功提示页示例*/


/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {

    /*用户*/
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');




//    Route::resource('example', ExampleController::class);
//    Route::get('example', 'ExampleController@index')->name('example.index');
//    Route::get('example/{example}', 'ExampleController@show')->name('example.show');
//    Route::get('example/create', 'ExampleController@create')->name('example.create');
//    Route::get('example/{example}/edit', 'ExampleController@edit')->name('example.edit');
//    Route::post('example', 'ExampleController@store')->name('example.store');
//    Route::put('example/{example}', 'ExampleController@update')->name('example.update');
//    Route::delete('example/{example}', 'ExampleController@destroy')->name('example.destroy');
});

/*首页*/
Route::get('/', 'IndexController@root')->name('root'); // 首页

/*商品展示相关*/
Route::get('product_categories/{product_category}', 'ProductCategoriesController@index')->name('product_categories.index'); // 商品分类列表[一级分类]
Route::get('product_categories/{product_category}/product', 'ProductsController@getProductByCategory')->name('product_categories.get_product_by_category'); // 商品分类呈现[一|二级分类]
Route::get('products/search', 'ProductsController@search')->name('products.search'); // 商品搜索结果
Route::get('products/{product}', 'ProductsController@show')->name('products.show'); // 商品详情
Route::get('products/comments/{product}', 'ProductCommentsController@index')->name('product_comments.index'); // 商品评价列表
Route::post('products/comments', 'ProductCommentsController@store')->name('product_comments.store'); // 添加商品评价

/*用户中心*/
Route::get('users/info', 'UsersController@show')->name('users.show');
Route::get('users/edit', 'UsersController@edit')->name('users.edit');
Route::put('users/update', 'UsersController@update')->name('users.update');


/*我的订单*/
Route::get('users/orders/{status}', 'OrdersController@index')->name('orders.index');
Route::get('users/orders/{order}', 'OrdersController@show')->name('orders.show');
Route::get('users/orders/create', 'OrdersController@create')->name('orders.create');
Route::post('users/orders', 'OrdersController@store')->name('orders.store');
Route::get('users/orders/{order}/edit', 'OrdersController@edit')->name('orders.edit');
Route::put('users/orders/{order}', 'OrdersController@update')->name('orders.update');
Route::delete('users/orders/{order}', 'OrdersController@destroy')->name('orders.destroy');

/*我的收藏*/
Route::get('users/favourites', 'UserFavouritesController@index')->name('user_favourites.index');
Route::post('users/favourites', 'UserFavouritesController@create')->name('user_favourites.create');
Route::delete('users/favourites/{userFavourite}', 'UserFavouritesController@destroy')->name('user_favourites.destroy');

/*浏览历史*/
Route::get('users/histories', 'UserHistoriesController@index')->name('user_histories.index');
Route::delete('users/histories/{userHistory}', 'UserHistoriesController@destroy')->name('user_histories.destroy');

Route::delete('users/histories', 'UserHistoriesController@flush')->name('user_histories.flush'); // 清空浏览历史

/*收货地址*/
Route::get('users/addresses', 'UserAddressesController@index')->name('user_addresses.index');
Route::get('users/addresses/{userAddress}', 'UserAddressesController@show')->name('user_addresses.show');
Route::get('users/addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
Route::post('users/addresses', 'UserAddressesController@store')->name('user_addresses.store');
Route::get('users/addresses/edit', 'UserAddressesController@edit')->name('user_addresses.edit');
Route::put('users/addresses/{userAddress}', 'UserAddressesController@update')->name('user_addresses.update');
Route::delete('users/addresses/{userAddress}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

Route::patch('users/addresses/{userAddress}', 'UserAddressesController@setDefault')->name('user_addresses.set_default'); // 设置默认收货地址

/*购物车*/
Route::get('users/carts', 'CartsController@index')->name('carts.index');
Route::post('users/carts', 'CartsController@store')->name('carts.store');
Route::put('users/carts/{cart}', 'CartsController@update')->name('carts.update');
Route::delete('users/carts/{cart}', 'CartsController@destroy')->name('carts.destroy');

Route::delete('users/carts', 'CartsController@flush')->name('carts.flush'); // 清空购物车

/*通用-单页展示*/
Route::get('pages/{page}', 'PagesController@show')->name('pages.show');

/*宣传-单页展示*/
Route::get('posters/{poster}', 'PostersController@show')->name('poster.show');
