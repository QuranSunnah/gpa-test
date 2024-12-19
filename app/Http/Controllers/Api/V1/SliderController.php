<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    use ApiResponse;

    public function show(string $slug): JsonResponse
    {
        return $this->response(Slider::where('slug', $slug)->firstOrFail(), 'Slider details found');
    }
}
