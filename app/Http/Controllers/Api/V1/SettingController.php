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
            ->select(['website_settings', 'system_settings', 'media'])
            ->first();

        return $this->response($settings);
    }
}
