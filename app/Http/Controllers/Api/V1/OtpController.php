<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OtpController extends Controller
{
    use ApiResponse;

    public function __construct(private OtpService $service)
    {
    }

    public function send(OtpRequest $request): JsonResponse
    {
        $this->service->send($request);

        return $this->msgResponse('OTP send successfull.', Response::HTTP_OK);
    }
}
