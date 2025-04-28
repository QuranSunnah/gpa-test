<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Partner;

class PartnerRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Partner::sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
