<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Repositories\LessonRepository;
use App\Repositories\QuizRepository;
use Illuminate\Http\Response;

class QuizService
{
    public function __construct(
        private QuizRepository $quizRepository,
        private LessonRepository $lessonRepository,
    ) {
    }

    public function getQuizzes(int $lessonId): array
    {
        $quizId = $this->getQuizId($lessonId);
        $quizWithQuestions = $this->quizRepository->getQuizInfo($quizId);

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
        $lessonProgress = $this->lessonRepository->getLessonProgress($lessonId);

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn ($lesson) => $lesson['id'] == $lessonId
                && $lesson['contentable_type'] == config('common.contentable_type.quiz')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested quiz not found'), Response::HTTP_BAD_REQUEST);
        }

        return $lessonProgress->contentable_id;
    }
}
