<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;

class EventsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $fetchEvents = function () use ($filters) {
            return Event::search($filters)
                ->filter($filters)
                ->sort($filters)
                ->active()
                ->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        try {
            $cacheKey = CommonHelper::buildCacheKey('events', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $fetchEvents);
        } catch (\Exception $e) {
            return $fetchEvents();
        }
    }

    public function findBySlug(string $slug)
    {
        $fetchEventDetail = function () use ($slug) {
            return Event::where('slug', $slug)->with('gallery')->firstOrFail();
        };

        try {
            return Cache::remember("events:$slug", config('common.api_cache_time'), $fetchEventDetail);
        } catch (\Exception $e) {
            return $fetchEventDetail();
        }
    }
}
