<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Exceptions\QuizFailedException;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Repositories\QuizRepository;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class Quiz implements LessonProgressInterface
{
    public function __construct(
        private QuizRepository $quizRepository,
        private LessonUnlockService $lessonUnlockService,
    ) {}

    public function process(LessonProgressResource $progressInfo): array
    {
        $quizResultInfo = $this->getQuizResultInfo($progressInfo);

        $updatedProgressResource = array_map(
            fn($progress) => (int) $progress['id'] === $progressInfo->lessonId
                ? array_merge($progress, [
                    'end_time' => Carbon::now()->timestamp,
                    ...$quizResultInfo,
                ])
                : $progress,
            $progressInfo->lessonProgress
        );

        return $this->lessonUnlockService->updateAndUnlockNextLesson($progressInfo, $updatedProgressResource);
    }

    private function getQuizResultInfo(LessonProgressResource $progressInfo): array
    {
        $quizWithQuestions = $this->quizRepository->getQuizInfo($progressInfo->contentableId);
        $quiz = $quizWithQuestions->first();

        $passMarksPercentage = $quiz->pass_marks_percentage ?? 100;
        $quizResults = $this->getTotalCorrectAns($progressInfo->quizzes, $quizWithQuestions);
        $totalCorrectAns = $quizResults['total_correct'];
        $scorePercentage = (int) round(($totalCorrectAns / $quizWithQuestions->count()) * 100);

        $isPassed = ($scorePercentage >= $passMarksPercentage) ? true : false;

        if (!$isPassed) {
            $message = __("Failed: Your score is below: {$passMarksPercentage}");

            throw new QuizFailedException(
                $message,
                $quizResults['question_results']
            );
        }

        return [
            'is_passed' => $isPassed,
            'total_correct_ans' => $totalCorrectAns,
            'score_percentage' => $scorePercentage,
        ];
    }

    private function getTotalCorrectAns(array $quizzes, Collection $quizWithQuestions): array
    {
        $questions = $quizWithQuestions->keyBy('id');

        if (count($quizzes) !== $questions->count()) {
            throw new ModelNotFoundException('Mismatch between submitted quizzes and expected questions.');
        }

        $correctAnswers = 0;
        $questionResults = [];

        foreach ($quizzes as $submittedQuiz) {
            $questionId = $submittedQuiz['id'];
            if (!$questions->has($questionId)) {
                throw new ModelNotFoundException('Question ID not found in the quiz.');
            }

            $question = $questions->get($questionId);
            $isCorrect = $this->isAnswerCorrect(
                $question->type,
                $submittedQuiz['answers'],
                $question->answers
            );

            $correctAnswers += $isCorrect ? 1 : 0;
            $questionResults[] = [
                'id' => $questionId,
                'is_correct' => $isCorrect,
            ];
        }

        return [
            'total_correct' => $correctAnswers,
            'question_results' => $questionResults,
        ];
    }

    private function isAnswerCorrect(int $type, string|array $submittedAnswers, string $correctAnswer): bool
    {
        try {
            if ($type === 1) {
                return is_string($submittedAnswers) && strtolower($submittedAnswers) === strtolower($correctAnswer);
            } else {
                $correctAnswer = json_decode($correctAnswer, true, 512, JSON_THROW_ON_ERROR);

                return is_array($submittedAnswers) && $submittedAnswers == $correctAnswer;
            }
        } catch (\Exception $e) {
            Log::error('Invalid Quiz Data: ' . $e->getMessage());
        }

        return false;
    }
}
