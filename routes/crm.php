<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function () {
    Route::get("report", "Admin\\ReportController@saleDate");

    Route::post("/login", "Admin\\LoginController@login");
    Route::middleware('web.auth')->group(function() {
        Route::delete("/session", "Admin\\SessionController@logout");
        Route::prefix("client")->name("client.")->group(function () {
            Route::get("", "Admin\\ClientController@list");
        });
        Route::prefix("information")->name("admin.")->group(function () {
            Route::post("/password", "Admin\\AdminInfoController@ChangePassword");
            Route::match(['get', 'post'], 'info', "Admin\\AdminInfoController@info")->name("info");
        });

        Route::prefix("setting")->name("setting.")->group(function (){
            Route::match(['get', 'post'], 'role', "Admin\\SettingController@role")->name("role");
            Route::get('/lable','Admin\\SettingController@LabelManagement');
            Route::post('/lable','Admin\\SettingController@LabelManagement')->name("lable");
        });
        Route::prefix("news")->name("news.")->group(function (){
            Route::get('/system','Admin\\NewsController@system');
            Route::get('/business','Admin\\NewsController@business');
            Route::get('/peruser','Admin\\NewsController@peruser');
            Route::get('/allperuser','Admin\\NewsController@AllPeruser');
            Route::get('/allnew','Admin\\NewsController@NoReading');

        });

    });

});
