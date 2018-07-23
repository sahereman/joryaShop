<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->post('wang_editor/images', 'WangEditorController@images')->name('admin.wang_editor.images');/*WangEditor上传图片*/


    $router->get('/', 'HomeController@index')->name('admin.root');

    $router->get('users', 'UsersController@index')->name('admin.users.index');


//    $router->resource('example', ExampleController::class);
//    $router->get('example', 'ExampleController@index')->name('admin.example.index');
//    $router->get('example/{id}', 'ExampleController@show')->name('admin.example.show');
//    $router->get('example/create', 'ExampleController@create')->name('admin.example.create');
//    $router->get('example/{id}/edit', 'ExampleController@edit')->name('admin.example.edit');
//    $router->post('example', 'ExampleController@store')->name('admin.example.store');
//    $router->put('example/{id}', 'ExampleController@update')->name('admin.example.update');
//    $router->delete('example/{id}', 'ExampleController@destroy')->name('admin.example.destroy');
});
