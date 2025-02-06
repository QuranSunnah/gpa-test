<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Services\PasswordResetService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    public function __construct(private PasswordResetService $service) {}

    public function reset(PasswordResetRequest $request): JsonResponse
    {
        $this->service->reset($request);
        return $this->response(
            [],
            'Reset password successfull.',
            Response::HTTP_OK
        );
    }
}
