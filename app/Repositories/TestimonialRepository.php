<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Testimonial;

class TestimonialRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Testimonial::sort($filters)->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
