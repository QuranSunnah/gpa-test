<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Traits\ApiResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $settings = Setting::where('status', config('common.status.active'))
            ->first();

        return $this->response(new SettingResource($settings));
    }
}
