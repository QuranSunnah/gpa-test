<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\LessonProgressResource;
use Carbon\Carbon;

class LessonUnlockService
{
    public function generateCertficate(LessonProgressResource $progressData)
    {
        $totalPassMarks = $this->getTotalPassMarksPercentage($lessonsData, $lessonProgress);

        if ($totalPassMarks >= $this->course->pass_marks) {
            $this->generateCertficate($progressData->studentId);
        }
    }



    public function getTotalPassMarksPercentage($lessonsData, array $lessonProgress)
    {
        $totalPass = array_reduce($lessonProgress, fn($count, $progress) => $count + ($progress['is_pass'] ? 1 : 0), 0);

        $totalLesson = $lessonsData->count();

        return (100 / $totalLesson) * $totalPass;
    }
}
