<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2017/12/7
 * Time: 14:35
 */
//WEB
Route::group(['namespace' => 'Weiler\Butterfly\Http\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', function () {
        echo 'Home';
    });
    //Admin
    Route::group(['namespace' => 'Admin','prefix' => 'admin'],function()
    {
        //Auth
        Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
            Route::get('login', 'AuthController@showLoginForm');
            Route::post('login', 'AuthController@login');
            Route::get('logout', 'AuthController@logout');
        });
        //Can
        Route::group([],function()
        {
            Route::get('/', ['uses'=>'IndexController@index']);
        });
    });
});