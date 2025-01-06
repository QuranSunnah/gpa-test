<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Database\Eloquent\Collection;


class LessonRepository
{

    public function getLesson(int $lessonId, int $courseId): Lesson
    {
        return Lesson::where('id', $lessonId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function getLessonProgress(int $studentId, int $courseId): LessonProgress
    {
        return LessonProgress::where("user_id", $studentId)
            ->where("course_id", $courseId)
            ->first();
    }

    public function getLessons($courseId): Collection
    {
        return Lesson::select('id', 'contentable_type', 'contentable_id', 'duration')
            ->where("course_id", $courseId)
            ->whereNot('contentable_type', config('common.contentable_type.resource'))
            ->orderBy("order", "ASC")
            ->get();
    }
}
