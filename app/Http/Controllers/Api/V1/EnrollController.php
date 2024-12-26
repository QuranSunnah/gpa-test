<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollController extends Controller
{
    use ApiResponse;

    public function __construct(private EnrollService $enrollService) {}

    public function enroll(Course $course)
    {
        $userId = auth()->id();

        $this->enrollService->enrollStudent($course->id, $userId);
        return $this->response([], __("Enrolled Succefully"));
    }
}
