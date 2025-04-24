<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Institute;
use Illuminate\Support\Facades\Cache;

class InstituteRepository
{
    public function getList()
    {
        $fetchInstituteList = function () {
            return Institute::select('id', 'name')->OrderBy('name', 'ASC')->get();
        };

        try {
            return Cache::remember('institute:list', config('common.api_cache_time'), $fetchInstituteList);
        } catch (\Exception $e) {
            return $fetchInstituteList();
        }
    }
}
