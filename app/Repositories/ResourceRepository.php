<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ResourceRepository
{
    public function findById(int $id): Collection
    {
        $resourceInfo = function () use ($id) {
            return Resource::findOrFail($id);
        };

        try {
            return Cache::remember("resources:$id", config('common.api_cache_time'), $resourceInfo);
        } catch (\Exception $e) {
            return $resourceInfo();
        }
    }
}
