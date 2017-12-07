<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2017/12/7
 * Time: 14:35
 */

Route::get('/', function () {
    echo 'Home';
});
//Admin
Route::group(['namespace' => 'Weiler\Butterfly\Http\Controllers\Admin','prefix' => 'admin'],function()
{
    Route::group([],function()
    {
        Route::get('/', ['uses'=>'IndexController@index']);
    });
});