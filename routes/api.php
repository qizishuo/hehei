<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace("Api")->group(function () {
    Route::post("/captcha", "NotifyController@captcha");
    Route::post('/infos', 'InfoController@store');

    Route::get('/hot-words', 'HotWordController@index');
    Route::get('/check-names', 'CheckNameController@index');
    Route::get('/popups', 'PopupController@index');

    Route::post("/wechat/send", "WechatController@send");
});
