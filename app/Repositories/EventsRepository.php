<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Events;

class EventsRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Events::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
