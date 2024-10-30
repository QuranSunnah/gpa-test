<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api', 'prefix' => 'v1'], function () {
    Route::get('/sliders/{id}', 'SliderController@show');
    Route::get('/partners', 'PartnerController@index');
    Route::get('/mentors', 'MentorController@index');
    Route::get('/testimonials', 'TestimonialController@index');
});

// Route::middleware(['auth:api'])
//     ->prefix('v1')
//     ->namespace('App\Http\Controllers\Api')
//     ->group(function () {});
