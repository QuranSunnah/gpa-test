<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Course;

class CourseRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Course::select(
            'courses.id',
            'courses.title',
            'slug',
            'category_id',
            'short_description',
            'courses.media_info',
            'is_top',
            'courses.duration',
            // 'total_lessons',
            // 'total_enrollments'
        )
            ->search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
