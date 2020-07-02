<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function () {
    Route::get("report", "Admin\\ClientController@seaList");

    Route::post("/login", "Admin\\LoginController@login");
    Route::middleware('web.auth')->group(function() {
        Route::delete("/session", "Admin\\SessionController@logout");
        Route::prefix("client")->name("client.")->group(function () {
            Route::get("create", "Admin\\ClientController@create");
            Route::get("sea", "Admin\\ClientController@seaList");
            Route::get("private", "Admin\\ClientController@privateList");
            Route::get("delete", "Admin\\ClientController@delete");
            Route::get("appeal","Admin\\ClientController@changeRadio");
            Route::post("import","Admin\\ClientController@import");
            Route::post("import_data","Admin\\ClientController@importData");
            Route::get("detail","Admin\\ClientController@detail");
            Route::get("follow","Admin\\ClientController@followUp");
            Route::get("deal","Admin\\ClientController@deal");
            Route::get("comment","Admin\\ClientController@comment");
            Route::get("appeal","Admin\\ClientController@appealList");
            Route::get("into_sea","Admin\\ClientController@intoSea");


        });
        Route::prefix("information")->name("admin.")->group(function () {
            Route::post("/password", "Admin\\AdminInfoController@ChangePassword");
            Route::match(['get', 'post'], 'info', "Admin\\AdminInfoController@info")->name("info");
        });

        Route::prefix("setting")->name("setting.")->group(function (){
            Route::match(['get', 'post'], 'role', "Admin\\SettingController@role")->name("role");
            Route::get('lable','Admin\\SettingController@LabelManagement');
            Route::post('lable','Admin\\SettingController@LabelManagement')->name("lable");
            Route::get('label_list','Admin\\SettingController@label');
            Route::get('rating_label','Admin\\SettingController@ratingLabels');
            Route::get('stage','Admin\\SettingController@stage');
            Route::get('create_label','Admin\\SettingController@createLabel');

        });
        Route::prefix("news")->name("news.")->group(function (){
            Route::get('/system','Admin\\NewsController@system');
            Route::get('/business','Admin\\NewsController@business');
            Route::get('/peruser','Admin\\NewsController@peruser');
            Route::get('/allperuser','Admin\\NewsController@AllPeruser');
            Route::get('/allnew','Admin\\NewsController@NoReading');

        });

        Route::prefix("service")->name("service.")->group(function(){
            Route::get('/service','Admin\\ServiceController@service');
            Route::get('/sale','Admin\\ServiceController@sale');

        });
    });

});
