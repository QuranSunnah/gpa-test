<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\SliderRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    use ApiResponse;

    public function __construct(private SliderRepository $repository)
    {
    }

    public function show(string $slug): JsonResponse
    {
        return $this->response($this->repository->findBySlug($slug), 'Slider details found');
    }
}
