<?php

declare(strict_types=1);

namespace App\Factories;

use App\Factories\LessonProgress\Lesson;
use App\Factories\LessonProgress\Quiz;

class LessonProgressFactory
{
    public static function create(string $contentableType)
    {
        switch ($contentableType) {
            case config('common.contentable_type.quiz'):
                return app(Quiz::class);
                break;
            case config('common.contentable_type.final_exam'):
                return app(Quiz::class);
                break;
            default:
                return app(Lesson::class);
        }
    }
}
