<?php

use Illuminate\Support\Facades\Route;

Route::prefix("admin")->group(function () {
    Route::post("/session", "Admin\\SessionController@login");
    Route::delete("/session", "Admin\\SessionController@logout");
});
