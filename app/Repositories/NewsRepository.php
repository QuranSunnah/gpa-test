<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\News;

class NewsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return News::search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }

    public function findBySlug(string $slug): News
    {
        return News::where('slug', $slug)->firstOrFail();
    }
}
