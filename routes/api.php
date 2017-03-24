<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::resource('register', 'Auth\RegisterController');

    Route::resource('login', 'Auth\LoginController');
});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::resource('me', 'User\UserDetailsController');
});

Route::any('{all}', 'ApiController@fourOhFour')->where('all', '.*');
