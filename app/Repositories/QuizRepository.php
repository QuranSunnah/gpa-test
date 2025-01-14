<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QuizRepository
{
    public function getQuizInfo(int $quizId): Collection
    {
        return Quiz::join('questions as qt', DB::raw('JSON_CONTAINS(quizzes.question_ids, JSON_ARRAY(qt.id))'), '=', DB::raw('1'))
            ->where('quizzes.id', $quizId)
            ->select([
                'quizzes.id as quiz_id',
                'quizzes.title as quiz_title',
                'quizzes.pass_marks_percentage',
                'qt.*',
            ])
            ->get();
    }
}
