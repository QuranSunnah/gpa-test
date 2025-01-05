<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;
use Illuminate\Support\Facades\Auth;

class EnrollRepository
{
        public function isStudentEnrolled(int $courseId): ?Enroll
        {
                return Enroll::where('user_id', Auth::id())
                        ->where('course_id', $courseId)
                        ->active()
                        ->first();
        }
}
