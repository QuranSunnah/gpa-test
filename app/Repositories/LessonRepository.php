<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;
use Illuminate\Support\Facades\Auth;

class LessonRepository
{
    public function getLessonProgress(int $lessonId): Enroll
    {
        return Enroll::join('lessons', 'lessons.course_id', '=', 'enrolls.course_id')
            ->where([
                ['enrolls.status', '=', config('common.status.active')],
                ['enrolls.user_id', '=', Auth::id()],
                ['lessons.id', '=', $lessonId],
            ])
            ->select('lessons.*', 'enrolls.lesson_progress')
            ->firstOrFail();
    }
}
