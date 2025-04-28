<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsRepository
{
    public function getSettings()
    {
        $settings = function () {
            return Setting::select(['website_settings', 'system_settings', 'media'])->first();
        };

        try {
            return Cache::remember('settings', config('common.api_cache_time'), $settings);
        } catch (\Exception $e) {
            return $settings();
        }
    }
}
