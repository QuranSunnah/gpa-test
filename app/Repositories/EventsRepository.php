<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;

class EventsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Event::search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
