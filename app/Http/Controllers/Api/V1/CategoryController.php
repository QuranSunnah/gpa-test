<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(private CategoryRepository $repository)
    {
    }

    public function topList(Request $request): JsonResponse
    {
        return $this->response($this->repository->getTopCategotyList($request->query('limit')));
    }

    public function report(Request $request): JsonResponse
    {
        return $this->response($this->repository->getTopCategoryReport($request->query('limit')));
    }
}
