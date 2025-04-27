<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\News;
use Illuminate\Support\Facades\Cache;

class NewsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $news = function () use ($filters) {
            return News::search($filters)
                ->filter($filters)
                ->sort($filters)
                ->active()
                ->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        if (!empty($filters['s'])) {
            return $news();
        }

        try {
            $cacheKey = CommonHelper::buildCacheKey('news', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $news);
        } catch (\Exception $e) {
            return $news();
        }
    }

    public function findBySlug(string $slug)
    {
        $newsDetail = function () use ($slug) {
            return News::where('slug', $slug)->firstOrFail();
        };

        try {
            return Cache::remember("news:$slug", config('common.api_cache_time'), $newsDetail);
        } catch (\Exception $e) {
            return $newsDetail();
        }
    }
}
