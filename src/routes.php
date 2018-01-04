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
    Route::get('/', function (\Illuminate\Http\Request $request) {
    })->name('hhh');

    // Img
    Route::group(['prefix' => config('butterfly.route_name.img')], function()
    {
        // $uid, $sourceName, $size = null
        Route::get('member', 'ImgController@getMember')->name('img-member');
        Route::get('picture', 'ImgController@getPicture')->name('img-picture');
    });

    // Admin
    Route::group(['namespace' => 'Admin','prefix' => config('butterfly.route_name.admin')], function()
    {
        // Auth
        Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
            Route::get('login', 'AuthController@showLoginForm');
            Route::post('login', 'AuthController@login');
            Route::get('logout', 'AuthController@logout');
        });
        // Can
        Route::group(['middleware' => ['butterfly.admin.auth']], function()
        {
            // 首页
            Route::get('/', ['uses'=>'IndexController@index'])->name('admin-index');
            // 我的面板
            Route::group(['prefix' => 'me'], function () {
                Route::get('/', ['uses'=>'MeController@index'])->name('admin-me');
                Route::post('update', ['uses'=>'MeController@update'])->name('admin-me-update');
                Route::post('upload-img', ['uses'=>'MeController@uploadImg'])->name('admin-me-uploadImg');
            });
            // 后台管理
            Route::group(['namespace' => 'Manage', 'prefix' => 'manage'], function () {
                // 目录管理
                Route::group(['prefix' => 'menu'], function () {
                    Route::get('/', ['uses' => 'MenuController@index'])->name('admin-manage-menu');
                    Route::get('add/{parentID?}', ['uses' => 'MenuController@getAdd'])->name('admin-manage-menu-add');
                    Route::post('add-post', ['uses' => 'MenuController@postAdd'])->name('admin-manage-menu-add-post');
                    Route::get('edit/{id}', ['uses' => 'MenuController@getEdit'])->name('admin-manage-menu-edit');
                    Route::post('edit-post/{id}', ['uses' => 'MenuController@postEdit'])->name('admin-manage-menu-edit-post');
                    Route::get('del/{id}', ['uses' => 'MenuController@getDel'])->name('admin-manage-menu-del');
                    Route::post('display', ['uses' => 'MenuController@display'])->name('admin-manage-menu-display');
                });
                // 目录管理
                Route::group(['prefix' => 'permissions'], function () {
                    Route::get('/', ['uses' => 'PermissionsController@index'])->name('admin-manage-permissions');
                    Route::get('add-group', ['uses' => 'PermissionsController@getAddGroup'])->name('admin-manage-permissions-add-group');
                    Route::post('add-group-post', ['uses' => 'PermissionsController@postAddGroup'])->name('admin-manage-permissions-add-group-post');
                    Route::get('edit-group/{id}', ['uses' => 'PermissionsController@getEditGroup'])->name('admin-manage-permissions-edit-group');
                    Route::post('edit-group-post/{id}', ['uses' => 'PermissionsController@postEditGroup'])->name('admin-manage-permissions-edit-group-post');
                    Route::get('del-group/{id}', ['uses' => 'PermissionsController@getDelGroup'])->name('admin-manage-permissions-del-group');
                    Route::get('permissions/{groupID}', ['uses' => 'PermissionsController@getPermissions'])->name('admin-manage-permissions-permissions');
                    Route::post('permissions-post/{groupID}', ['uses' => 'PermissionsController@postPermissions'])->name('admin-manage-permissions-permissions-post');
                });
                // 后台用户管理
                Route::group(['prefix' => 'member'], function() {
                    Route::get('/', ['uses' => 'MemberController@index'])->name('admin-manage-member');
                    Route::get('add', ['uses' => 'MemberController@getAdd'])->name('admin-manage-member-add');
                    Route::post('add-post', ['uses' => 'MemberController@postAdd'])->name('admin-manage-member-add-post');
                    Route::get('edit/{id}', ['uses' => 'MemberController@getEdit'])->name('admin-manage-member-edit');
                    Route::post('edit-post/{id?}', ['uses' => 'MemberController@postEdit'])->name('admin-manage-member-edit-post');
                    Route::get('del/{id}', ['uses' => 'MemberController@getDel'])->name('admin-manage-member-del');
                    Route::post('upload-img', ['uses'=>'MemberController@uploadImg'])->name('admin-manage-member-uploadImg');
                });
                // 后台日志
                Route::group(['prefix' => 'log'], function() {
                    Route::get('/', ['uses' => 'LogController@index'])->name('admin-manage-log');
                });
            });
        });
    });
});