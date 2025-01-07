<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::truncate();

        $categories = [
            [
                'name' => 'Programming',
                'slug' => Str::slug('Programming'),
                'description' => 'Courses related to various programming languages and frameworks.',
                'parent' => 0,
                'image' => 'programming.jpg',
                'order' => 1,
                'is_top' => 1,
                'is_highlighted' => 1,
                'others' => json_encode(['icon' => 'code']),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Science',
                'slug' => Str::slug('Data Science'),
                'description' => 'Courses on data analysis, machine learning, and AI.',
                'parent' => 0,
                'image' => 'data-science.jpg',
                'order' => 3,
                'is_top' => 1,
                'is_highlighted' => 1,
                'others' => json_encode(['icon' => 'analytics']),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Machine Learning',
                'slug' => Str::slug('Machine Learning'),
                'description' => 'In-depth courses on ML algorithms and applications.',
                'parent' => 0,
                'image' => 'machine-learning.jpg',
                'order' => 5,
                'is_top' => 0,
                'is_highlighted' => 1,
                'others' => json_encode(['icon' => 'ai']),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
