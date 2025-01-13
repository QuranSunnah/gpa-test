<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\QuestionResource;
use App\Models\Course;
use Illuminate\Http\Response;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuizService
{
    public function getQuizzes(Request $request, string $slug): array
    {
        $targetLesson = $this->findTargetLesson($slug, $request->lesson_id);

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

    private function findTargetLesson(string $slug, $lessonId): array
    {
        $lessonProgress = Course::join('lesson_progress', 'lesson_progress.course_id', '=', 'courses.id')
            ->where('courses.slug', $slug)
            ->where('lesson_progress.user_id', Auth::id())
            ->select('lesson_progress.*')
            ->firstOrFail();

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson =  $lessons->first(function ($lesson) use ($lessonId) {
            return $lesson['id'] == $lessonId &&
                $lesson['contentable_type'] === config('common.contentable_type.quiz');
        });

        if (!$targetLesson) {
            throw new \Exception(__("Invalid Request: Request quiz not found"), Response::HTTP_BAD_REQUEST);
        }

        return $targetLesson;
    }
}
