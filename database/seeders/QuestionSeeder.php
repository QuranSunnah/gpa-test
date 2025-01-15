<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        Question::truncate();

        $questions = [];
        $categoryIds = Category::pluck('id');
        $quizIds = Quiz::pluck('id');

        foreach ($quizIds as $quizId) {
            for ($i = 1; $i <= rand(5, 10); ++$i) {
                $options = [
                    'Option A',
                    'Option B',
                    'Option C',
                    'Option D',
                ];
                shuffle($options);

                $questions[] = [
                    'title' => "Question $i for Quiz $quizId",
                    'category_id' => $categoryIds->random(),
                    'type' => rand(1, 2),
                    'options' => json_encode($options),
                    'answers' => json_encode([array_rand($options)]),
                    'feedbacks' => json_encode(['Correct', 'Incorrect']),
                    'time_limit' => rand(30, 120),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Question::insert($questions);
    }
}
