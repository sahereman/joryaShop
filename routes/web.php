<?php

Route::get('test', function () {
    dd('test');
});

//Horizon::auth(function ($request) {
//    return Auth::guard('admin')->check();
//});
Auth::routes();

//Route::redirect('/', '/users')->name('root');/*首页*/
Route::get('/', 'PagesController@root')->name('root');/*首页*/


/*需要登录的路由*/
Route::group(['middleware' => 'auth'], function () {


});