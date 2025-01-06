<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Models\Question;
use App\Models\Quiz as QuizModel;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;


class Quiz implements LessonProgressInterface
{
    public function __construct(private LessonUnlockService $lessonUnlockService) {}

    public function process(LessonProgressResource $progressInfo): array
    {
        $quizResultInfo = $this->getQuizResultInfo($progressInfo);

        $updatedProgressResource = array_map(
            fn($progress) => (int)$progress['id'] === $progressInfo->lessonId
                ? array_merge($progress, [
                    'is_pass' => $quizResultInfo['is_passed'],
                    'score' => $quizResultInfo['score'],
                    'end_time' => Carbon::now()->timestamp
                ])
                : $progress,
            $progressInfo->lessonProgress
        );

        $this->lessonUnlockService->updateAndUnlockNextLesson($progressInfo, $updatedProgressResource);

        return collect($updatedProgressResource)->firstWhere('id', $progressInfo->lessonId);
    }

    private function getQuizResultInfo(LessonProgressResource $progressInfo): array
    {
        $quiz = QuizModel::where('id', $progressInfo->contentableId)
            ->where('course_id', $progressInfo->courseId)
            ->active()
            ->firstOrFail();

        $questions = Question::whereIn('id', json_decode($quiz->question_ids, true, 512, JSON_THROW_ON_ERROR))
            ->active()
            ->select(['id', 'type', 'answers'])
            ->get()
            ->keyBy('id');

        $totalQuestions = $questions->count();

        $passMarks = $quiz->pass_marks_percentage;
        $totalCorrectAns = $this->getTotalCorrectAns($progressInfo->quizzes, $questions);
        $score = (int) round(($totalCorrectAns / $totalQuestions) * 100);

        $isPassed = ($score >= $passMarks) ? 1 : 0;

        if (!$isPassed) {
            throw new \Exception(__("Failed: Your score is blow: {$passMarks}", Response::HTTP_OK));
        }

        return [
            'score' => $score,
            'is_passed' => $isPassed,
        ];
    }

    private function getTotalCorrectAns(array $quizzes, Collection $questions)
    {
        if (count($quizzes) !== $questions->count()) {
            throw new ModelNotFoundException("Mismatch between submitted quizzes and expected questions.");
        }

        $correctAnswers = 0;
        foreach ($quizzes as $submittedQuiz) {
            $questionId = $submittedQuiz['id'];

            if (!$questions->has($questionId)) {
                throw new ModelNotFoundException("Question ID not found in the quiz.");
            }

            $question = $questions->get($questionId);
            $correctAnswers += $this->isAnswerCorrect($question->type, $submittedQuiz['answers'], $question->answers) ? 1 : 0;
        }
        return $correctAnswers;
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
            Log::error("Invalid Quiz Data: " . $e->getMessage());
        }

        return false;
    }
}
