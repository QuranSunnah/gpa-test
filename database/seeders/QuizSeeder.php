<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run()
    {
        Quiz::truncate();

        $quizzes = [];
        $courseIds = DB::table('courses')->pluck('id');

        foreach ($courseIds as $courseId) {
            for ($i = 1; $i <= rand(1, 3); ++$i) {
                $quizzes[] = [
                    'title' => "Quiz $i for Course $courseId",
                    'course_id' => $courseId,
                    'category_id' => rand(1, 5),
                    'type' => rand(1, 2),
                    'question_ids' => json_encode(range(1, rand(5, 15))),
                    'total_question' => rand(5, 15),
                    'each_qmark' => 1.5,
                    'pass_marks_percentage' => rand(40, 70),
                    'quiz_time' => rand(600, 1800),
                    'attempt_time' => rand(1, 3),
                    'penalty_time' => rand(0, 60),
                    'instructions' => "Instructions for Quiz $i in Course $courseId",
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Quiz::insert($quizzes);
    }
}
