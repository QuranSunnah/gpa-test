<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Support\Facades\Cache;

class SliderRepository
{
    public function findBySlug(string $slug)
    {
        $sliderDetail = function () use ($slug) {
            return Slider::where('slug', $slug)->firstOrFail();
        };

        try {
            return Cache::remember("sliders:$slug", config('common.api_cache_time'), $sliderDetail);
        } catch (\Exception $e) {
            return $sliderDetail();
        }
    }
}
