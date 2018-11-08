<?php

//以下所有路由 URL 为 site.com/mobile/ + 路由URL

Route::get('test', function () {
    dd('test mobile');
});


/*首页*/
Route::get('/', 'IndexController@root')->name('mobile.root'); // 首页