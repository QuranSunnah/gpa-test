<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Factories\LessonProgressFactory;
use App\Http\Requests\LessonProgressRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class LessonProgressService
{
    public function processLessonProgress(string $slug, LessonProgressRequest $request): array
    {
        $progressInfo = $this->getLessonProgressInfo($slug, $request->lesson_id, $request->quizzes);

        if (!$progressInfo->isLessonPassed) {
            $instance = LessonProgressFactory::create($progressInfo->contentableType);
            return $instance->process($progressInfo);
        }

        throw new \Exception(__('Already passed the lesson'), Response::HTTP_OK);
    }

    public function getLessonProgressInfo(string $slug, int $lessonId, ?array $quizzes): LessonProgressResource
    {
        $studentId = Auth::id();

        $courseInfo = Course::select([
            'lesson_progress.id as lesson_progress_id',
            'lesson_progress.lessons as lesson_progress',
            'lesson_progress.is_passed',
            'lesson_progress.total_marks',
            'courses.id as course_id',
            'courses.pass_marks',
            'lessons.id as lesson_id',
            'lessons.contentable_type',
            'lessons.contentable_id',
            'lessons.duration'
        ])
            ->join('lessons', function ($join) use ($lessonId) {
                $join->on('lessons.course_id', '=', 'courses.id')
                    ->where('lessons.id', '=', $lessonId);
            })
            ->join('lesson_progress', 'lesson_progress.course_id', '=', 'courses.id')
            ->where('lesson_progress.user_id', $studentId)
            ->where('courses.slug', $slug)
            ->firstOrFail();

        return new LessonProgressResource($lessonId, $courseInfo, $quizzes);
    }
}
