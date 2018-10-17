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


    /*产品分类*/
    $router->get('product_categories', 'ProductCategoriesController@index')->name('admin.product_categories.index');
    $router->get('product_categories/create', 'ProductCategoriesController@create')->name('admin.product_categories.create');
    $router->get('product_categories/{id}', 'ProductCategoriesController@show')->name('admin.product_categories.show');
    $router->get('product_categories/{id}/edit', 'ProductCategoriesController@edit')->name('admin.product_categories.edit');
    $router->post('product_categories', 'ProductCategoriesController@store')->name('admin.product_categories.store');
    $router->put('product_categories/{id}', 'ProductCategoriesController@update')->name('admin.product_categories.update');
    $router->delete('product_categories/{id}', 'ProductCategoriesController@destroy')->name('admin.product_categories.destroy');

    /*产品*/
    $router->get('products', 'ProductsController@index')->name('admin.products.index');
    $router->get('products/create', 'ProductsController@create')->name('admin.products.create');
    $router->get('products/{id}', 'ProductsController@show')->name('admin.products.show');
    $router->get('products/{id}/edit', 'ProductsController@edit')->name('admin.products.edit');
    $router->post('products', 'ProductsController@store')->name('admin.products.store');
    $router->put('products/{id}', 'ProductsController@update')->name('admin.products.update');
    $router->delete('products/{id}', 'ProductsController@destroy')->name('admin.products.destroy');


    //    $router->resource('example', ExampleController::class)->names('admin.example');
    //    $router->get('example', 'ExampleController@index')->name('admin.example.index');
    //    $router->get('example/create', 'ExampleController@create')->name('admin.example.create');
    //    $router->get('example/{id}', 'ExampleController@show')->name('admin.example.show');
    //    $router->get('example/{id}/edit', 'ExampleController@edit')->name('admin.example.edit');
    //    $router->post('example', 'ExampleController@store')->name('admin.example.store');
    //    $router->put('example/{id}', 'ExampleController@update')->name('admin.example.update');
    //    $router->delete('example/{id}', 'ExampleController@destroy')->name('admin.example.destroy');
});
