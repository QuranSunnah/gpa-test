<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use ApiResponse;

    public function __construct(private NewsRepository $repository) {}

    public function index(Request $request): JsonResponse
    {
        return $this->paginateResponse($this->repository->paginate($request->query->all()));
    }

    public function show(string $slug): JsonResponse
    {
        return $this->response($this->repository->findBySlug($slug), __('News not found'));
    }
}
