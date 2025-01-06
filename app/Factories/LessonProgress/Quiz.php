<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Models\Question;
use App\Models\Quiz as ModelsQuiz;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class Quiz implements LessonProgressInterface
{
    private LessonUnlockService $lessonUnlockService;

    public function __construct(LessonUnlockService $lessonUnlockService)
    {
        $this->lessonUnlockService = $lessonUnlockService;
    }

    public function process(LessonProgressResource $progressData): void
    {
        $quizData = $this->validateAndFetchQuizData($progressData);

        if ($quizData['is_passed']) {
            $updatedProgressResource = $this->updateLessonProgressObj($progressData, $quizData['score']);
            $this->lessonUnlockService->UpdateAndunLockNextLesson($progressData, $updatedProgressResource);
        }
    }

    public function validateAndFetchQuizData(LessonProgressResource $progressData): array
    {
        $quizModel = $this->fetchActiveQuiz($progressData);
        $questions = $this->fetchQuestions($quizModel);

        return $this->evaluateQuiz($progressData->quizzes, $questions, $quizModel->pass_marks_percentage);
    }

    private function fetchActiveQuiz(LessonProgressResource $progressData): ModelsQuiz
    {
        return ModelsQuiz::where('id', $progressData->contentableId)
            ->where('course_id', $progressData->course->id)
            ->active()
            ->firstOrFail();
    }

    private function fetchQuestions(ModelsQuiz $quiz): Collection
    {
        $questionIds = json_decode($quiz->question_ids, true, 512, JSON_THROW_ON_ERROR);

        return Question::whereIn('id', $questionIds)
            ->active()
            ->select(['id', 'type', 'answers'])
            ->get()
            ->keyBy('id');
    }

    private function evaluateQuiz(array $submittedQuizzes, Collection $questions, int $passPercentage): array
    {
        $totalQuestions = $questions->count();
        $correctAnswers = 0;

        if (count($submittedQuizzes) !== $totalQuestions) {
            throw new \Exception("Mismatch between submitted quizzes and expected questions.");
        }

        foreach ($submittedQuizzes as $submittedQuiz) {
            $questionId = $submittedQuiz['id'];
            $submittedAnswers = $submittedQuiz['answers'];

            if (!$questions->has($questionId)) {
                throw new \Exception("Question ID not found in the quiz.");
            }

            $question = $questions->get($questionId);
            $correctAnswers += $this->isAnswerCorrect($question->type, $submittedAnswers, $question->answers) ? 1 : 0;
        }

        $score = (int) round(($correctAnswers / $totalQuestions) * 100);

        return [
            'score' => $score,
            'is_passed' => $score >= $passPercentage,
        ];
    }

    private function isAnswerCorrect(int $type, $submittedAnswers, string $correctAnswer): bool
    {
        try {
            if ($type === 1) {
                return is_string($submittedAnswers) && strtolower($submittedAnswers) === strtolower($correctAnswer);
            } else {
                $correctAnswer = json_decode($correctAnswer, true, 512, JSON_THROW_ON_ERROR);

                return is_array($submittedAnswers) && $submittedAnswers == $correctAnswer;
            }
        } catch (\Exception $e) {
            Log::error("Invalid Quiz Data: " . $e->getMessage());
        }

        return false;
    }

    private function updateLessonProgressObj(LessonProgressResource $progressData, int $score): array
    {
        return array_map(
            fn($progress) => (int)$progress['id'] === $progressData->lesson->id
                ? array_merge($progress, [
                    'is_pass' => 1,
                    'score' => $score,
                    'end_time' => Carbon::now()->timestamp
                ])
                : $progress,
            json_decode($progressData->lessonProgress->lessons, true, 512, JSON_THROW_ON_ERROR)
        );
    }
}
