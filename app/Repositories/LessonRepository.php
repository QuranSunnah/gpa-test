<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class LessonRepository
{
    public function getLessonProgress(int $lessonId): LessonProgress
    {
        return LessonProgress::join('lessons', 'lessons.course_id', '=', 'lesson_progress.course_id')
            ->where([
                ['lesson_progress.status', '=', config('common.status.active')],
                ['lesson_progress.user_id', '=', Auth::id()],
                ['lessons.id', '=', $lessonId],
            ])
            ->select('lessons.*', 'lesson_progress.lessons')
            ->firstOrFail();
    }
}
