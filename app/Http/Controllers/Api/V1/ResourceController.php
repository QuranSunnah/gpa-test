<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ResourceService;
use App\Traits\ApiResponse;


class ResourceController extends Controller
{
    use ApiResponse;

    public function __construct(private ResourceService $service) {}

    public function show(int $lessonId)
    {
        $quizInfo = $this->service->getResource($lessonId);

        return $this->response($quizInfo, __('Resource Info'));
    }
}
