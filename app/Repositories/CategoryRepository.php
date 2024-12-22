<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getTopCategotyList(?string $limit)
    {
        $query = Category::select('id', 'slug', 'name')
            ->where('is_top', config('common.confirmation.yes'))
            ->active()
            ->orderBy('name', 'ASC');
        if ($limit) {
            $query->limit($limit);
        }
        return $query->get();
    }

    public function getTopCategoryReport(?string $limit)
    {
        $query = Category::select(
            'categories.*',
            \DB::raw('SUM(courses.total_lessons) as total_lessons'),
            \DB::raw('SUM(courses.total_enrollments) as total_enrollments'),
        )
            ->where('categories.is_top', config('common.confirmation.yes'))
            ->leftJoin('courses', 'categories.id', '=', 'courses.category_id')
            ->active()
            ->orderBy('name', 'ASC')
            ->groupBy('categories.id');
        if ($limit) {
            $query->limit($limit);
        }
        return $query->get();
    }
}
