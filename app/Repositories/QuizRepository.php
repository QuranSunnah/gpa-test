<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QuizRepository
{
    public function getQuizInfo(int $id): Collection
    {
        $quizInfo = function () use ($id) {
            return Quiz::join('questions as qt', DB::raw(
                'JSON_CONTAINS(quizzes.question_ids, JSON_ARRAY(qt.id))'
            ), '=', DB::raw('1'))
                ->where('quizzes.id', $id)
                ->select([
                    'quizzes.id as quiz_id',
                    'quizzes.title as quiz_title',
                    'quizzes.pass_marks_percentage',
                    'quizzes.each_qmark',
                    'quizzes.instructions',
                    'qt.*',
                ])
                ->get();
        };

        try {
            return Cache::remember("quizes:$id", config('common.api_cache_time'), $quizInfo);
        } catch (\Exception $e) {
            return $quizInfo();
        }
    }
}
