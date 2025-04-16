<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthenticateService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthenticateService $service) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        return $this->response($this->service->authenticateUser($request), 'Login successfull');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->delete();

        return $this->msgResponse('Logout successful');
    }
}
