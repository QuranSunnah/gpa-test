<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\InstituteRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InstituteController extends Controller
{
    use ApiResponse;

    public function __construct(private InstituteRepository $repository)
    {
    }

    public function list(): JsonResponse
    {
        return $this->response($this->repository->getList(), __('Institute details'));
    }
}
