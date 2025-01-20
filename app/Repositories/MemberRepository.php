<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Member;

class MemberRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Member::sort($filters)
            ->where('status', config('common.status.active'))
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
