<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\Mentor;
use Illuminate\Support\Facades\Cache;

class MentorRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $members = function () use ($filters) {
            return Mentor::sort($filters)
                ->active()
                ->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        try {
            $cacheKey = CommonHelper::buildCacheKey('mentors', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $members);
        } catch (\Exception $e) {
            return $members();
        }
    }
}
