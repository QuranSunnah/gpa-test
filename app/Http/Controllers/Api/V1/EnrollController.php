<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    use ApiResponse;

    public function __construct(private EnrollService $enrollService) {}

    public function enroll(Course $course)
    {
        $studentId = Auth::id();

        $this->enrollService->enrollStudent($course->id, $studentId);
        return $this->response([], __("Enrolled Succefully"));
    }
}
