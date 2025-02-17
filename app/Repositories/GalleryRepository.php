<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Gallery;

class GalleryRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Gallery::search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
