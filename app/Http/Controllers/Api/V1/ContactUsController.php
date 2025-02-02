<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Services\ContactUsService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactUsController extends Controller
{
    use ApiResponse;

    public function __construct(private ContactUsService $service) {}

    public function save(ContactUsRequest $request): JsonResponse
    {
        $this->service->save($request);

        return $this->response([], 'Message sent successfully');
    }
}
