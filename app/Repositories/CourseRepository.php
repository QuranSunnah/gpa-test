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
            \DB::raw('COUNT(DISTINCT lessons.id) as total_lessons'),
            \DB::raw('COUNT(DISTINCT enrolls.id) as total_enrollments')
        )
            ->leftJoin('lessons', 'courses.id', '=', 'lessons.course_id')
            ->leftJoin('enrolls', 'courses.id', '=', 'enrolls.course_id')
            ->groupBy('courses.id')
            ->search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }
}
