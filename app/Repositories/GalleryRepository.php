<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;

class GalleryRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $fetchGalaries = function () use ($filters) {
            return Gallery::search($filters)
                ->filter($filters)
                ->sort($filters)
                ->active()
                ->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        if (!empty($filters['s'])) {
            return $fetchGalaries();
        }

        try {
            $cacheKey = CommonHelper::buildCacheKey('galleries', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $fetchGalaries);
        } catch (\Exception $e) {
            return $fetchGalaries();
        }
    }

    public function findBySlug(string $slug)
    {
        $fetchGalleryDetail = function () use ($slug) {
            return Gallery::where('slug', $slug)->firstOrFail();
        };

        try {
            return Cache::remember("galleries:$slug", config('common.api_cache_time'), $fetchGalleryDetail);
        } catch (\Exception $e) {
            return $fetchGalleryDetail();
        }
    }
}
