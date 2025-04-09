<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse;

    public function __construct(private CourseRepository $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }

    public function list(Request $request): JsonResponse
    {
        $courses = Course::active()->select('slug', 'title')->get()->toArray();

        return $this->response($courses, __('Course List'));
    }

    public function topList(Request $request): JsonResponse
    {
        return $this->response($this->repository->getTopCourses());
    }

    public function topCategoryCourses(Request $request): JsonResponse
    {
        return $this->response(
            $this->repository->getTopCategoryCourses($request->query('limit'))
        );
    }

    public function show(string $slug): JsonResponse
    {
        return $this->response($this->repository->findBySlug($slug), 'Course details found');
    }

    public function myCourses(Request $request): JsonResponse
    {
        return $this->paginateResponse($this->repository->mycourses($request->query->all()));
    }
}
