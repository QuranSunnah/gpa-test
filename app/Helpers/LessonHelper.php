<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTO\LessonProgressResource;
use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;

class LessonHelper
{
    public static function validateTimeDuration(LessonProgressResource $progressData): bool
    {
        $lessonDuration = $progressData->lesson->duration;
        $previousTimeCarbon = Carbon::parse($progressData->startTime);
        $currentTime = now();
        $diffInSeconds = $previousTimeCarbon->diffInSeconds($currentTime);

        $ration = ($lessonDuration / 100) * 50;

        if (($lessonDuration && $ration < $diffInSeconds) || !$lessonDuration) {
            return true;
        }
        return false;
    }

    public static function validateIncompleteLessons(array $lessonProgress): int
    {
        $totalIncompleteLessons = array_reduce(
            $lessonProgress,
            function ($count, $progress) {
                $isLesson = $progress['contentable_type'] === config('common.contentable_type.lesson');
                $isNotPass = !$progress['is_pass'];

                return $count + ($isNotPass && $isLesson ? 1 : 0);
            },
            0
        );

        return $totalIncompleteLessons;
    }

    public static function getValidQuizData(LessonProgressResource $progressData): array
    {
        $quizzes = $progressData->quizzes;
        $quizId = $progressData->contentableId;
        $courseId = $progressData->lesson->course_id;

        $quiz = Quiz::where('id', $quizId)
            ->where('course_id', $courseId)
            ->active()
            ->firstOrFail();

        $questionIds = json_decode($quiz->question_ids, true, 512, JSON_THROW_ON_ERROR);

        $questions = Question::whereIn('id', $questionIds)
            ->active()
            ->select('id', 'type', 'answers')
            ->get()
            ->keyBy('id');

        $correctAnswers = 0;

        if (count($quizzes) !== count($questions)) {
            throw new \Exception("Invalid Request");
        }

        foreach ($quizzes as $submittedQuiz) {
            $questionId = $submittedQuiz['id'];
            $submittedAnswers = $submittedQuiz['answers'];

            // Check if the question exists in the quiz
            if (!isset($questions[$questionId])) {
                return [
                    'valid' => false,
                    'message' => "Invalid question ID: $questionId"
                ];
            }

            $question = $questions[$questionId];
            $correctAnswer = json_decode($question->answers, true, 512, JSON_THROW_ON_ERROR);

            // Validate based on question type
            if ($question->type === 'text') {
                // Text-based answer validation (case-insensitive)
                if (is_string($submittedAnswers) && strtolower($submittedAnswers) === strtolower($correctAnswer)) {
                    $correctAnswers++;
                }
            } elseif ($question->type === 'multiple-choice') {
                // Multiple-choice answer validation (array comparison)
                if (is_array($submittedAnswers) && empty(array_diff($submittedAnswers, $correctAnswer)) && empty(array_diff($correctAnswer, $submittedAnswers))) {
                    $correctAnswers++;
                }
            } else {
                return [
                    'valid' => false,
                    'message' => "Unknown question type for question ID: $questionId"
                ];
            }
        }

        // Calculate the score
        $score = ($correctAnswers / $totalQuestions) * 100;

        return [
            'valid' => true,
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions
        ];
    }
}
