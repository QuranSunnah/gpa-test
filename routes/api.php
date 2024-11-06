<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api\V1\Auth', 'prefix' => 'v1/auth'], function () {
    Route::post('/register', 'RegisterController');
    Route::post('/login', 'LoginController');
    Route::post('/logout', 'LoginController@logout')->middleware(['auth:api']);
});

Route::group(['namespace' => 'App\Http\Controllers\Api\V1', 'prefix' => 'v1'], function () {
    Route::get('/sliders/{id}', 'SliderController@show');
    Route::get('/partners', 'PartnerController@index');
    Route::get('/mentors', 'MentorController@index');
    Route::get('/testimonials', 'TestimonialController@index');
    Route::get('/news', 'NewsController@index');
    Route::get('/events', 'EventsController@index');
    Route::get('/courses', 'CourseController@index');
    Route::get('/courses/{id}', 'CourseController@show');
    Route::get('/top-categories/list', 'CategoryController@topList');
    Route::get('/top-categories/report', 'CategoryController@report');
    Route::get('/top-categories/courses', 'CourseController@topCategoryCourses');
});

Route::middleware(['auth:api'])
    ->prefix('v1')
    ->namespace('App\Http\Controllers\Api\V1')
    ->group(function () {
        Route::get('/test-auth-api', function () {
            return response()->json(['message' => 'Test auth api working']);
        });
    });
