<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\CommonHelper;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseRepository implements Repository
{
    public function paginate(array $filters = [])
    {
        $fetchCourses = function () use ($filters) {
            return Course::select(
                'courses.id',
                'courses.title',
                'slug',
                'price',
                'instructor_id',
                'category_id',
                'short_description',
                'full_description',
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
        };

        if (!empty($filters['s'])) {
            return $fetchCourses();
        }

        try {
            $cacheKey = CommonHelper::buildCacheKey('courses', $filters);

            return Cache::remember($cacheKey, config('common.api_cache_time'), $fetchCourses);
        } catch (\Exception $e) {
            return $fetchCourses();
        }
    }

    public function getTopCategoryCourses(?string $limit)
    {
        $topCategoryCourses = function () use ($limit) {
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
        };

        try {
            return Cache::remember(
                "top_category_courses:limit:$limit",
                config('common.api_cache_time'),
                $topCategoryCourses
            );
        } catch (\Exception $e) {
            return $topCategoryCourses();
        }
    }

    public function getTopCourses()
    {
        $topCourses = function () {
            return Course::select(
                'id',
                'title',
                'slug',
                'category_id',
                'instructor_id',
                'short_description',
                'media_info',
                'is_top',
                'duration',
                'total_lessons',
                'total_enrollments'
            )
                ->with(['instructor:id,name,photo', 'category:id,name'])
                ->where('type', config('common.course_type_options.regular'))
                ->where('is_top', config('common.confirmation.yes'))
                ->active()
                ->orderBy('id', 'DESC')
                ->get();
        };

        try {
            return Cache::remember('top_courses', config('common.api_cache_time'), $topCourses);
        } catch (\Exception $e) {
            return $topCourses();
        }
    }

    public function findBySlug(string $slug)
    {
        $fetchCourseDetail = function () use ($slug) {
            return Course::with([
                'category:id,name',
                'instructor:id,name,biography,photo,designation',
                'sections' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'sections.lessons' => function ($query) {
                    $query->select(
                        'id',
                        'section_id',
                        'title',
                        'contentable_type',
                        'contentable_id',
                        'duration',
                        'summary',
                    )
                        ->with('contentable:id,title')->orderBy('order', 'asc');
                },
            ])
                ->where('slug', $slug)->firstOrFail();
        };

        try {
            return Cache::remember("courses:$slug", config('common.api_cache_time'), $fetchCourseDetail);
        } catch (\Exception $e) {
            return $fetchCourseDetail();
        }
    }

    public function mycourses(array $filters = [])
    {
        return Course::join('enrolls', 'enrolls.course_id', 'courses.id')
            ->select([
                'courses.id',
                'courses.slug',
                'courses.media_info',
                'courses.type',
                'courses.title',
                'courses.total_enrollments',
                'courses.duration',
                'enrolls.total_marks',
            ])
            ->filter($filters)
            ->where('enrolls.user_id', Auth::id())
            ->where('enrolls.status', config('common.status.active'))
            ->active()
            ->orderBy('enrolls.id', 'DESC')
            ->paginate($filters['limit'] ?? config('common.pagi_limit'));
    }

    public function getCourseList()
    {
        $fetchCourseList = function () {
            return Course::active()->select('slug', 'title')->get()->toArray();
        };

        try {
            return Cache::remember('course:list', config('common.api_cache_time'), $fetchCourseList);
        } catch (\Exception $e) {
            return $fetchCourseList();
        }
    }
}
