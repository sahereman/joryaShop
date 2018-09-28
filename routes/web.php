<?php

Route::get('test', function () {
    dd('test');
});


Horizon::auth(function ($request) {
    return Auth::guard('admin')->check();
});
Auth::routes();


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

Route::get('/', 'IndexController@root')->name('root');/*首页*/
