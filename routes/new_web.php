<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|111
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace("Child")->prefix("child")->name("child.")->group(function () {
    Route::post("/login", "LoginController@login");
    Route::middleware("wechat.oauth")->prefix("wechat")->name("wechat.")->group(function () {
        Route::get("bind", "WechatController@bind")->name("bind");
    });
    Route::middleware('web.auth')->group(function() {
        Route::get('index',"IndexController@index");
        Route::post("password", "ChildinfoController@ChangePassword")->name("password");
        Route::get('logout',"LoginController@logout");

        Route::prefix("info")->name("info.")->group(function () {
            Route::get("", "InfoController@index")->name("index");
            Route::match(['get', 'post'], "apply", "InfoController@apply")->name("apply");
            Route::get("apply-list", "InfoController@list")->name("apply-list");
        });



        Route::get("", "IndexController@index")->name("index");
    });
});

Route::namespace("Admin")->prefix('admin')->group(function () {

    Route::post("/login", "LoginController@login");

    Route::middleware('web.auth')->group(function() {
        Route::post("password", "AdmininfoController@ChangePassword")->name("password");
        Route::get('/logout',"LoginController@logout");
        Route::prefix("child-account")->name("child-account.")->group(function () {
            Route::get('', "ChildAccountController@index")->name("index");
            Route::post('create', "ChildAccountController@create")->name("create");
            Route::match(['get', 'post'],'edit', "ChildAccountController@edit")->name("edit");
            Route::get('open', "ChildAccountController@open")->name("open");
            Route::get('close', "ChildAccountController@delete")->name("close");
            Route::match(['get', 'post'], 'money', "ChildAccountController@money")->name("money");
            Route::get('money-list',"ChildAccountController@MoneyList");
        });

        Route::get('/hot-word', "HotWordController@edit")->name("hot-word");
        Route::post('/hot-word', "HotWordController@save");

        Route::get('/check-name', 'CheckNameController@web')->name('check-name');
        Route::get('/check-name/delete', 'CheckNameController@delete')->name('check-name.delete');
        Route::post('/check-name/import', 'CheckNameController@import')->name('check-name.import');

        Route::match(['get', 'post'], '/system/money', "SystemController@money")->name("system.money");

        Route::get('/popup', 'PopupController@list')->name('popup');
        Route::get('/popup/delete', 'PopupController@delete')->name('popup.delete');
        Route::post('/popup/import', 'PopupController@import')->name('popup.import');

        Route::prefix('info')->name('info.')->group(function () {

            Route::get('list', 'InfoController@list')->name("list");
            Route::match(['get', 'post'], 'change', 'InfoController@change')->name("change");
            Route::get('delete', 'InfoController@delete')->name("delete");
            Route::get('user', 'InfoController@user')->name("user");
            Route::prefix('apply')->name('apply.')->group(function () {
                Route::get('index', 'ApplyController@index')->name("index");
                Route::get('adopt', 'ApplyController@adopt')->name("adopt");
                Route::get('refuse', 'ApplyController@refuse')->name("refuse");
                Route::get('export', 'ApplyController@export')->name("export");
            });

            Route::get('export', 'InfoController@export')->name("export");
        });

        Route::get("analyze", "AnalyzeController@index")->name("analyze");
        Route::get("", "IndexController@index")->name("index");
    });
});
