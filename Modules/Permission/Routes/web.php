<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['web']], function()
{
    // 首页（仪表盘）
    Route::get('/',function(){
        return redirect('/dashboard');
    });
    // 登录页面
    Route::get('/auth/login',['uses'=>'Auth\LoginController@showLoginForm'])->name('login');
    // 退出
    Route::get('/auth/logout',['uses'=>'Auth\LoginController@logout'])->name('logout');
    // 登录确认
    Route::post('/auth/login',['uses'=>'Auth\LoginController@login']);
});

/*dashboard*/
Route::group(['prefix'=>'dashboard','middleware'=>['web','auth']],function()
{
    // 首页（仪表盘）
    Route::get('/','DashboardController@show');
    // 搜索 todo
    Route::post('/search','DashboardController@search');
    // 修改本地化语言
    Route::get('/change_local','DashboardController@changeLanguage');
});

Route::prefix('permission')->group(function() {
    Route::get('/', 'PermissionController@index');
});
