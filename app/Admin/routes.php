<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->post('wang_editor/images', 'WangEditorController@images')->name('admin.wang_editor.images');/*WangEditor上传图片*/
    $router->get('dashboard', 'PagesController@dashboard')->name('admin.dashboard');

    $router->get('/', 'PagesController@index')->name('admin.root');

    /*系统设置*/
    $router->get('configs', 'ConfigsController@index')->name('admin.configs.index');/*详情*/
    $router->post('configs/submit', 'ConfigsController@submit')->name('admin.configs.submit');/*提交*/

    /*用户*/
    $router->get('users', 'UsersController@index')->name('admin.users.index');
    $router->get('users/create', 'UsersController@create')->name('admin.users.create');
    $router->get('users/{id}', 'UsersController@show')->name('admin.users.show');
    $router->get('users/{id}/edit', 'UsersController@edit')->name('admin.users.edit');
    $router->put('users/{id}', 'UsersController@update')->name('admin.users.update');
    $router->delete('users/{id}', 'UsersController@destroy')->name('admin.users.destroy');

    /*商品订单*/
    $router->get('orders', 'OrdersController@index')->name('admin.orders.index');/*列表*/
    $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');/*详情*/
    $router->delete('orders/{order}/delete', 'OrdersController@delete')->name('admin.orders.delete');/*删除*/
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');/*发货*/

    /*售后订单*/
    $router->get('order_refunds', 'OrderRefundsController@index')->name('admin.order_refunds.index');/*列表*/
    $router->get('order_refunds/{refund}', 'OrderRefundsController@show')->name('admin.order_refunds.show');/*详情*/
    $router->post('order_refunds/{refund}/check', 'OrderRefundsController@check')->name('admin.order_refunds.check');/*审核通过*/
    $router->post('order_refunds/{refund}/receive', 'OrderRefundsController@receive')->name('admin.order_refunds.receive');/*收货并退款*/


    /*产品分类*/
    $router->resource('product_categories', ProductCategoriesController::class)->names('admin.product_categories');

    /*产品*/
    $router->resource('products', ProductsController::class)->names('admin.products');

    /*广告位*/
    $router->resource('posters', PostersController::class)->names('admin.posters');

    /*文章*/
    $router->resource('articles', ArticlesController::class)->names('admin.articles');

    /*Banner图*/
    $router->resource('banners', BannersController::class)->names('admin.banners');

    /*汇率管理*/
    $router->resource('exchange_rates', ExchangeRatesController::class)->names('admin.exchange_rates');

    /*手机号国家管理*/
    $router->resource('country_codes', CountryCodesController::class)->names('admin.country_codes');

    /*快递公司管理*/
    $router->resource('shipment_companies', ShipmentCompaniesController::class)->names('admin.shipment_companies');

    /*导航菜单管理*/
    $router->resource('menus', MenusController::class)->names('admin.menus');

    //    $router->resource('example', ExampleController::class)->names('admin.example');
    //    $router->get('example', 'ExampleController@index')->name('admin.example.index');
    //    $router->get('example/create', 'ExampleController@create')->name('admin.example.create');
    //    $router->get('example/{id}', 'ExampleController@show')->name('admin.example.show');
    //    $router->get('example/{id}/edit', 'ExampleController@edit')->name('admin.example.edit');
    //    $router->post('example', 'ExampleController@store')->name('admin.example.store');
    //    $router->put('example/{id}', 'ExampleController@update')->name('admin.example.update');
    //    $router->delete('example/{id}', 'ExampleController@destroy')->name('admin.example.destroy');
});
