<?php

declare(strict_types=1);

// laravel follows snakeCase(underscore based) convention for key name in config file

return [
    'pagi_limit' => 20,
    'status' => [
        'active' => 1,
        'inactive' => 0,
    ],
    'confirmation' => [
        'no' => 0,
        'yes' => 1,
    ],
    'courseType' => [
        'free' => 1,
        'paid' => 2,
        'premium' => 3,
        'request' => 4,
    ],
    'courseLevel' => [
        'beginner' => 1,
        'intermediate' => 2,
        'advanced' => 3,
    ],
    'language' => [
        'english' => 1,
        'bangla' => 2,
    ],
    'designation' => [
        'student' => 1,
        'service_holder' => 2,
        'self_employed' => 3,
        'others' => 4,
    ],
    'contentable_type' => [
        'lesson' => 'App\Models\Lesson',
        'quiz' => 'App\Models\Quiz',
        'final_exam' => 'App\Models\Examination',
        'resource' => 'App\Models\Resource',
    ],
];
