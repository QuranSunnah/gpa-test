<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class LessonRepository
{
    public function getLessonProgress(int $lessonId): LessonProgress
    {
        return LessonProgress::join('enrolls', 'enrolls.course_id', '=', 'lesson_progress.course_id')
            ->join('lessons', 'lessons.course_id', '=', 'enrolls.course_id')
            ->where([
                ['lessons.id', '=', $lessonId],
                ['enrolls.user_id', '=', Auth::id()],
                ['enrolls.status', '=', config('common.status.active')],
                ['lesson_progress.user_id', '=', Auth::id()],
            ])
            ->select('lessons.*', 'lesson_progress.lessons')
            ->firstOrFail();
    }
}
