<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 2020111111111111111111
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace("Child")->prefix("child")->name("child.")->group(function () {
    Auth::routes();

    Route::match(['get', 'post'], "password", "Auth\\ChangePasswordController")->name("password");

    Route::prefix("info")->name("info.")->group(function () {
        Route::get("", "InfoController@index")->name("index");
        Route::match(['get', 'post'], "{id}/apply", "InfoController@apply")->name("apply");
        Route::get("apply", "InfoController@list")->name("list");
    });

    Route::middleware("wechat.oauth")->prefix("wechat")->name("wechat.")->group(function () {
        Route::get("bind/{id}", "WechatController@bind")->name("bind");
    });

    Route::get("", "IndexController@index")->name("index");
});

Route::namespace("Admin")->prefix('admin')->group(function () {
    Auth::routes();

    Route::match(['get', 'post'], "password", "Auth\\ChangePasswordController")->name("password");

    Route::prefix("child-account")->name("child-account.")->group(function () {
        Route::get('', "ChildAccountController@index")->name("index");
        Route::match(['get', 'post'], 'create', "ChildAccountController@create")->name("create");
        Route::match(['get', 'post'], '{id}/edit', "ChildAccountController@edit")->name("edit");
        Route::get('{id}/open', "ChildAccountController@open")->name("open");
        Route::get('{id}/close', "ChildAccountController@delete")->name("close");
        Route::match(['get', 'post'], '{id}/money', "ChildAccountController@money")->name("money");
    });

    Route::get('/hot-word', "HotWordController@edit")->name("hot-word");
    Route::post('/hot-word', "HotWordController@save");

    Route::get('/check-name', 'CheckNameController@web')->name('check-name');
    Route::get('/check-name/{id}/delete', 'CheckNameController@delete')->name('check-name.delete');
    Route::post('/check-name/import', 'CheckNameController@import')->name('check-name.import');

    Route::match(['get', 'post'], '/system/money', "SystemController@money")->name("system.money");

    Route::get('/popup', 'PopupController@list')->name('popup');
    Route::get('/popup/{id}/delete', 'PopupController@delete')->name('popup.delete');
    Route::post('/popup/import', 'PopupController@import')->name('popup.import');

    Route::prefix('info')->name('info.')->group(function () {
        Route::get('list', 'InfoController@list')->name("list");
        Route::match(['get', 'post'], '{id}/change', 'InfoController@change')->name("change");
        Route::get('{id}/delete', 'InfoController@delete')->name("delete");
        Route::get('user', 'InfoController@user')->name("user");
        Route::prefix('apply')->name('apply.')->group(function () {
            Route::get('index', 'ApplyController@index')->name("index");
            Route::get('{id}/pass', 'ApplyController@pass')->name("pass");
            Route::get('{id}/refuse', 'ApplyController@refuse')->name("refuse");
            Route::get('export', 'ApplyController@export')->name("export");
        });
        Route::get('export/view', 'InfoController@exportView')->name("export.view");
        Route::get('export', 'InfoController@export')->name("export");
    });

    Route::get("analyze", "AnalyzeController@index")->name("analyze");
    Route::get("", "IndexController@index")->name("index");
});
