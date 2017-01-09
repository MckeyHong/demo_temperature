<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'checkLogin'], function () {
    // 帳號管理
    Route::resource('users', 'UsersController', ['only' => ['index', 'store', 'update', 'destroy']]);
    Route::put('users/active/{userID}/{active}', 'UsersController@active');
    Route::post('changePassword', 'UsersController@changePassword');

    // 首頁
    Route::get('/fan', 'MainController@fan');
    Route::get('/temperature', 'MainController@temperature');
    Route::get('/', 'MainController@index');
});

// 系統登入&登出
Route::post('/logout', 'UsersController@logout');
Route::get('/login', 'UsersController@getLogin');
Route::post('/login', 'UsersController@postLogin');
