<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Response;

class SliderController extends Controller
{
    public function show(int $id)
    {
        return response()->json(
            [
                'message' => 'Slider details found',
                'data' => Slider::findOrFail($id),
            ],
            Response::HTTP_OK
        );
    }
}
