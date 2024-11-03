<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Traits\ApiResponse;

class SliderController extends Controller
{
    use ApiResponse;

    public function show(int $id)
    {
        return $this->response(Slider::findOrFail($id), 'Slider details found');
    }
}
