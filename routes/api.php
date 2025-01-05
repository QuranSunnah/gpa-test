<?php

declare(strict_types=1);

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('/register', Api\V1\Auth\RegisterController::class);
    Route::post('/register/complete', [Api\V1\Auth\RegisterController::class, 'complete']);
    Route::post('/login', Api\V1\Auth\LoginController::class);
    Route::post('/logout', [Api\V1\Auth\LoginController::class, 'logout'])->middleware(['auth:api']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/otp/send', [Api\V1\OtpController::class, 'send']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/sliders/{id}', [Api\V1\SliderController::class, 'show']);
    Route::get('/partners', [Api\V1\PartnerController::class, 'index']);
    Route::get('/mentors', [Api\V1\MentorController::class, 'index']);
    Route::get('/testimonials', [Api\V1\TestimonialController::class, 'index']);
    Route::get('/news', [Api\V1\NewsController::class, 'index']);
    Route::get('/events', [Api\V1\EventsController::class, 'index']);
    Route::get('/courses', [Api\V1\CourseController::class, 'index']);
    Route::get('/courses/{slug}', [Api\V1\CourseController::class, 'show']);
    Route::get('/category/list', [Api\V1\CategoryController::class, 'list']);
    Route::get('/top-categories/list', [Api\V1\CategoryController::class, 'topList']);
    Route::get('/top-categories/report', [Api\V1\CategoryController::class, 'report']);
    Route::get('/top-categories/courses', [Api\V1\CourseController::class, 'topCategoryCourses']);

    Route::get('/settings', [Api\V1\SettingController::class, 'index']);
});

Route::middleware(['auth:api'])
    ->prefix('v1')
    ->group(function () {
        Route::group(['prefix' => 'courses'], function () {
            Route::post('{id}/enroll', [Api\V1\EnrollController::class, 'enroll']);
            Route::get('{id}/lesson_progress', [Api\V1\LessonProgressController::class, 'show']);
            Route::patch('{id}/lesson_progress', [Api\V1\LessonProgressController::class, 'save']);
        });
    });
