<?php

declare(strict_types=1);

// laravel follows snakeCase(underscore based) convention for key name in config file

return [
    'pagi_limit' => 20,
    'status' => [
        'active' => 1,
        'inactive' => 0,
    ],
    'user_status' => [
        'inactive' => 0,
        'active' => 1,
        'profile_pending' => 2,
    ],
    'confirmation' => [
        'no' => 0,
        'yes' => 1,
    ],
    'course_type' => [
        'free' => 1,
        'paid' => 2,
        'premium' => 3,
        'request' => 4,
    ],
    'course-level' => [
        'beginner' => 1,
        'intermediate' => 2,
        'advanced' => 3,
    ],
    'language' => [
        'bangla' => 1,
        'english' => 2,
    ],
    'designation' => [
        'student' => 1,
        'service_holder' => 2,
        'self_employed' => 3,
        'others' => 4,
    ],
    'contentable_type' => [
        'lesson' => 1,
        'quiz' => 2,
        'resource' => 3,
    ],
    'provider' => [
        'manual' => 1,
        'google' => 2,
    ],
    'verified_by' => [
        'email' => 1,
        'phone' => 2,
        'google' => 3,
    ],
    'otp_expired_duration_at_min' => 5,
    'gender' => [
        'male' => 1,
        'female' => 2,
        'others' => 3,
    ],
    'blood_group' => [
        'A+' => 1,
        'A-' => 2,
        'B+' => 3,
        'B-' => 4,
        'O+' => 5,
        'O-' => 6,
        'AB+' => 7,
        'AB-' => 8,
    ],
    'academic_status' => [
        'univeristy_student' => 1,
        'graduate' => 2,
        'post_graduate' => 3,
    ],
];
