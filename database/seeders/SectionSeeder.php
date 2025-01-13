<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run()
    {
        Section::truncate();

        $sections = [];
        $courseIds = Course::pluck('id');

        foreach ($courseIds as $courseId) {
            for ($i = 1; $i <= rand(2, 5); $i++) {
                $sections[] = [
                    'title' => "Section $i for Course $courseId",
                    'course_id' => $courseId,
                    'trainer_id' => rand(1, 10),
                    'order' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Section::insert($sections);
    }
}
