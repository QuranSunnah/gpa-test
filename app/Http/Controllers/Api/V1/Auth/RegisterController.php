<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\RegisterServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(private RegisterServiceInterface $registerService)
    {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        return $this->response(
            $this->registerService->register($request),
            'Registration successfull',
            Response::HTTP_CREATED
        );
    }
}
