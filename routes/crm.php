<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function () {
    Route::get("report", "Admin\\ReportController@changeLevel");

    Route::post("/login", "Admin\\LoginController@login");
    Route::middleware('web.auth')->group(function() {
        Route::delete("/session", "Admin\\SessionController@logout");
        Route::prefix("client")->name("client.")->group(function () {
            Route::get("create", "Admin\\ClientController@create");
            Route::get("sea", "Admin\\ClientController@seaList");
            Route::get("delete", "Admin\\ClientController@delete");
            Route::get("appeal","Admin\\ClientController@changeRadio");
            Route::get("import","Admin\\ClientController@import");

        });
        Route::prefix("information")->name("admin.")->group(function () {
            Route::post("/password", "Admin\\AdminInfoController@ChangePassword");
            Route::match(['get', 'post'], 'info', "Admin\\AdminInfoController@info")->name("info");
        });

        Route::prefix("setting")->name("setting.")->group(function (){
            Route::match(['get', 'post'], 'role', "Admin\\SettingController@role")->name("role");
            Route::get('/lable','Admin\\SettingController@LabelManagement');
            Route::post('/lable','Admin\\SettingController@LabelManagement')->name("lable");
            Route::get('/label_list','Admin\\SettingController@label');

        });
        Route::prefix("news")->name("news.")->group(function (){
            Route::get('/system','Admin\\NewsController@system');
            Route::get('/business','Admin\\NewsController@business');
            Route::get('/peruser','Admin\\NewsController@peruser');
            Route::get('/allperuser','Admin\\NewsController@AllPeruser');
            Route::get('/allnew','Admin\\NewsController@NoReading');

        });

        Route::prefix("service")->name("service.")->group(function(){
            Route::get('/region','Admin\\ServiceController@region');
        });
    });

});
