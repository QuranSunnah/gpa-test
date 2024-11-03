<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\News;

class NewsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return News::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
