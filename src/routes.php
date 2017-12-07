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
Route::get('admin', function () {
    return view('butterfly::admin.index');
});