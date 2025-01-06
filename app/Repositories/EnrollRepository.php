<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;
use Illuminate\Support\Facades\Auth;

class EnrollRepository
{
        public function isStudentEnrolled(string $slug): ?Enroll
        {
                return Enroll::query()
                        ->join('courses as C', 'enrolls.course_id', '=', 'C.id')
                        ->where('enrolls.user_id', Auth::id())
                        ->where('C.slug', $slug)
                        ->where('enrolls.status', config('common.status.active'))
                        ->select('enrolls.*')
                        ->first();
        }
}
