<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        return $this->response(Institute::select('id', 'name')->get(), __('Institute details'));
    }
}
