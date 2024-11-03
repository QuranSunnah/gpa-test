<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse;

    public function __construct(private CourseRepository $repository) {}

    public function index(Request $request)
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }

    public function topCategoryCourses(Request $request)
    {
        return $this->response(
            $this->repository->topCategoryCourses($request->query('limit'))
        );
    }
}
