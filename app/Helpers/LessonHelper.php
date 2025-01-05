<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTO\LessonProgressResource;
use Carbon\Carbon;

class LessonHelper
{
    public static function validateTimeDuration(LessonProgressResource $progressData): bool
    {
        $lessonDuration = $progressData->lesson->duration;
        $previousTimeCarbon = Carbon::parse($progressData->startTime);
        $currentTime = now();
        $diffInSeconds = $previousTimeCarbon->diffInSeconds($currentTime);

        $ration = ($lessonDuration / 100) * 50;

        if (($lessonDuration && $ration < $diffInSeconds) || !$lessonDuration) {
            return true;
        }
        return false;
    }

    public static function validateIncompleteLessons(array $lessonProgress): int
    {
        $totalIncompleteLessons = array_reduce(
            $lessonProgress,
            function ($count, $progress) {
                $isLesson = $progress['contentable_type'] === config('common.contentable_type.lesson');
                $isNotPass = !$progress['is_pass'];

                return $count + ($isNotPass && $isLesson ? 1 : 0);
            },
            0
        );

        return $totalIncompleteLessons;
    }

    public function validateQuizData(LessonProgressResource $progressData): bool
    {
        $quizzes = $progressData->quizzes;
        $courseId = $progressData->lesson->course_id;
    }
}
