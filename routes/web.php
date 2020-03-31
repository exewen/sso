<?php

use Illuminate\Support\Facades\Route;

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
// 登陆模块
Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/', function () {
    return redirect('/sso');
});
