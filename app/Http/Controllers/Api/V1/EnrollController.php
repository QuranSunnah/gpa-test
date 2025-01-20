<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EnrollService;
use App\Traits\ApiResponse;

class EnrollController extends Controller
{
    use ApiResponse;

    public function __construct(private EnrollService $enrollService)
    {
    }

    public function enroll(string $slug)
    {
        $this->enrollService->enrollStudent($slug);

        return $this->response([], __('Enrolled Succefully'));
    }
}
