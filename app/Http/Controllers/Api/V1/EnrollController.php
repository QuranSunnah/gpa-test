<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repositories\EnrollRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollController extends Controller
{
    use ApiResponse;

    public function __construct(private EnrollRepository $repository)
    {
    }

    public function enroll(Request $request): JsonResponse
    {
        $this->repository->enrollStudent($request->course_id);
        return $this->response([], __("Enrolled Succefully"));
    }
}
