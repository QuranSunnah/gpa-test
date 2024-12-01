<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegistrationCompleteRequest;
use App\Services\Interfaces\RegisterServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __construct(private RegisterServiceInterface $service)
    {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        return $this->response(
            $this->service->register($request),
            'An OTP has been sent to your email or phone. Please verify to complete registration.',
            Response::HTTP_CREATED
        );
    }

    public function complete(RegistrationCompleteRequest $request): JsonResponse
    {
        return $this->response(
            $this->service->complete($request),
            'Registration successfull.',
            Response::HTTP_CREATED
        );
    }
}
