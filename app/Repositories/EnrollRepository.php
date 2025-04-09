<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class EnrollRepository
{
    public function isStudentEnrolled(string $slug): ?LessonProgress
    {
        return LessonProgress::query()
            ->join('courses as C', 'lesson_progress.course_id', '=', 'C.id')
            ->where('lesson_progress.user_id', Auth::id())
            ->where('C.slug', $slug)
            ->where('lesson_progress.status', config('common.status.active'))
            ->select('lesson_progress.*')
            ->first();
    }
}
