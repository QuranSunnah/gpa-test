<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Response;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuizService
{
    public function getQuizzes(int $lessonId): array
    {
        $targetLesson = $this->findTargetLesson($lessonId);

        $quiz = Quiz::findOrFail($targetLesson['contentable_id']);
        $questions = Question::whereIn("id", json_decode($quiz->question_ids, true))->get();

        return [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'total_questions' => $questions->count(),
            'pass_marks_percentage' => $quiz->pass_marks_percentage,
            'questions' => QuestionResource::collection($questions),
        ];
    }

    private function findTargetLesson(int $lessonId): array
    {
        $lessonProgress = Course::join('enrolls', 'enrolls.course_id', '=', 'courses.id')
            ->join('lesson_progress', 'lesson_progress.course_id', '=', 'courses.id')
            ->join('lessons', 'lessons.course_id', '=', 'courses.id')
            ->where([
                ['lessons.id', '=', $lessonId],
                ['enrolls.user_id', '=', Auth::id()],
                ['lesson_progress.user_id', '=', Auth::id()],
                ['enrolls.status', '=', config('common.status.active')]
            ])
            ->select('lesson_progress.lessons', 'lessons.id as lesson_id', 'lessons.contentable_type')
            ->firstOrFail();

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn($lesson) =>
            $lesson['id'] == $lessonProgress->lesson_id &&
                $lesson['contentable_type'] === config('common.contentable_type.quiz')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested quiz not found'), Response::HTTP_BAD_REQUEST);
        }

        return $targetLesson;
    }
}
