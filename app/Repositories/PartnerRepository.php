<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\Partner;
use Illuminate\Support\Facades\Cache;

class PartnerRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $partners = function () use ($filters) {
            return Partner::sort($filters)
                ->active()
                ->paginate($filters['limit'] ?? config('common.pagi_limit'));
        };

        try {
            $cacheKey = CommonHelper::buildCacheKey('partners', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $partners);
        } catch (\Exception $e) {
            return $partners();
        }
    }
}
