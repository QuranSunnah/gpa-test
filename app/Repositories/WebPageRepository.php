<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\WebPage;
use Illuminate\Support\Facades\Cache;

class WebPageRepository
{
    public function findDetails(int $lang, string $slug): array
    {
        $webPageDetails = function () use ($lang, $slug) {
            return WebPage::where([
                ['slug', $slug],
                ['status', config('common.status.active')],
                ['lang', $lang],
            ])
                ->select('components')
                ->firstOrFail()->components;
        };

        try {
            $cacheKey = CommonHelper::buildCacheKey('web_page_detail', [
                'lang' => $lang,
                'slug' => $slug,
            ]);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $webPageDetails);
        } catch (\Exception $e) {
            return $webPageDetails();
        }
    }
}
