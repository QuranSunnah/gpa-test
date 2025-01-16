<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Member;

class MemberRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Member::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
