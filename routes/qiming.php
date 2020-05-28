<?php

use Illuminate\Support\Facades\Route;

Route::resource('/orders', 'OrderController');
Route::post('/payment', 'WechatController@payment');
Route::get('order_status/{order}', 'OrderController@status');
Route::get('repay/{order}', 'OrderController@repay');
