<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $testimonials = function () use ($filters) {
            return Testimonial::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        try {
            return Cache::remember('testimonials', config('common.api_cache_time'), $testimonials);
        } catch (\Exception $e) {
            return $testimonials();
        }
    }
}
