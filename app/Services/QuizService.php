<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\QuestionResource;
use Illuminate\Http\Response;
use App\Models\Quiz;
use App\Repositories\LessonRepository;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(private LessonRepository $repository) {}

    public function getQuizzes(int $lessonId): array
    {
        $quizId = $this->getQuizId($lessonId);

        $quizWithQuestions = Quiz::join('questions as qt', DB::raw('JSON_CONTAINS(quizzes.question_ids, JSON_ARRAY(qt.id))'), '=', DB::raw('1'))
            ->where('quizzes.id', $quizId)
            ->select([
                'quizzes.id as quiz_id',
                'quizzes.title as quiz_title',
                'quizzes.pass_marks_percentage',
                'qt.*',
            ])
            ->get();

        $quiz = $quizWithQuestions->first();

        return [
            'id' => $quiz->id ?? null,
            'title' => $quiz->title ?? null,
            'pass_marks_percentage' => $quiz->pass_marks_percentage ?? null,
            'total_questions' => $quizWithQuestions->count(),
            'questions' => QuestionResource::collection($quizWithQuestions),
        ];
    }

    private function getQuizId(int $lessonId): int
    {
        $lessonProgress = $this->repository->getLessonProgress($lessonId);

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn($lesson) =>
            $lesson['id'] == $lessonId &&
                $lesson['contentable_type'] == config('common.contentable_type.quiz')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested quiz not found'), Response::HTTP_BAD_REQUEST);
        }

        return $lessonProgress->contentable_id;
    }
}
