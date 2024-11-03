<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Mentor;

class MentorRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Mentor::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
