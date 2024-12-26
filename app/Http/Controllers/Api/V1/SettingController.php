<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->response(Setting::select(['website_settings', 'system_settings', 'media'])->first());
    }
}
