<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getTopList()
    {
        return Category::select('id', 'slug', 'name')
            ->where('is_top', 1)
            ->active()
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getReport()
    {
        return Category::select(
            'categories.*',
            \DB::raw('SUM(courses.total_lessons) as total_lessons'),
            \DB::raw('SUM(courses.total_enrollments) as total_enrollments'),
        )
            ->where('categories.is_top', 1)
            ->leftJoin('courses', 'categories.id', '=', 'courses.category_id')
            ->active()
            ->orderBy('name', 'ASC')
            ->groupBy('categories.id')
            ->get();
    }
}
