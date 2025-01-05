<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\Course;

class CourseRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        return Course::select(
            'courses.id',
            'courses.title',
            'slug',
            'price',
            'instructor_id',
            'category_id',
            'short_description',
            'courses.media_info',
            'is_top',
            'courses.duration',
            'total_lessons',
            'total_enrollments',
            'courses.created_at'
        )->with(['instructor:id,name,photo', 'category:id,name'])
            ->search($filters)
            ->filter($filters)
            ->sort($filters)
            ->active()
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }

    public function getTopCategoryCourses(?string $limit)
    {
        return Category::with(['courses' => function ($query) {
            $query->select(
                'id',
                'title',
                'slug',
                'category_id',
                'short_description',
                'media_info',
                'is_top',
                'duration',
                'total_lessons',
                'total_enrollments'
            )
                ->orderBy('id', 'DESC')
                ->limit(config('common.pagi_limit'));
        }])
            ->where('is_top', config('common.confirmation.yes'))
            ->active()
            ->orderBy('name', 'ASC')
            ->limit($limit ?? config('common.pagi_limit'))
            ->get();
    }

    public function findById(int $id)
    {
        return Course::with([
            'sections' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'sections.lessons' => function ($query) {
                $query->with('contentable')->orderBy('order', 'asc');
            },
        ])
            ->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return Course::with([
            'category:id,name',
            'sections' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'sections.lessons' => function ($query) {
                $query->with('contentable')->orderBy('order', 'asc');
            },
        ])
            ->where('slug', $slug)->firstOrFail();
    }
}
