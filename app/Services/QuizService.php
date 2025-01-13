<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Response;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuizService
{
    public function getQuizzes(int $lessonId): array
    {
        $quizId = $this->getQuizId($lessonId);

        $quiz = Quiz::findOrFail($quizId);
        $questions = Question::whereIn("id", json_decode($quiz->question_ids, true))->get();

        return [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'total_questions' => $questions->count(),
            'pass_marks_percentage' => $quiz->pass_marks_percentage,
            'questions' => QuestionResource::collection($questions),
        ];
    }

    private function getQuizId(int $lessonId)
    {
        $lessonProgress = LessonProgress::join('enrolls', 'enrolls.course_id', '=', 'lesson_progress.course_id')
            ->join('lessons', 'lessons.course_id', '=', 'enrolls.course_id')
            ->where([
                ['lessons.id', '=', $lessonId],
                ['enrolls.user_id', '=', Auth::id()],
                ['enrolls.status', '=', config('common.status.active')],
                ['lesson_progress.user_id', '=', Auth::id()]
            ])
            ->select('lesson_progress.lessons')
            ->firstOrFail();

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn($lesson) =>
            $lesson['id'] == $lessonId &&
                $lesson['contentable_type'] == config('common.contentable_type.quiz')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested quiz not found'), Response::HTTP_BAD_REQUEST);
        }

        return $targetLesson['contentable_id'];
    }
}
