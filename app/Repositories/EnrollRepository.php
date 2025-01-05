<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;

class EnrollRepository
{
    public function isEnrolled(int $studentId, int $courseId): ?Enroll
    {
        return Enroll::where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->active()
            ->first();
    }
}
