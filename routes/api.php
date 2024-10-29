<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api', 'prefix' => 'v1'], function () {
    Route::get('/sliders', 'SliderController@index');
});

// Route::middleware(['auth:api'])
//     ->prefix('v1')
//     ->namespace('App\Http\Controllers\Api')
//     ->group(function () {});
