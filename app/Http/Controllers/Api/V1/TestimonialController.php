<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\TestimonialRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function __construct(private TestimonialRepository $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }
}
