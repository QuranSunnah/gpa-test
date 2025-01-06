<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Factories\LessonProgressFactory;
use App\Http\Requests\LessonProgressRequest;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LessonProgressService
{
    public function processLessonProgress(string $slug, LessonProgressRequest $request): void
    {
        $progressInfo = $this->getLessonProgressInfo($slug, $request->lesson_id, $request->quizzes);

        if (!$progressInfo->isLessonPassed) {
            $instance = LessonProgressFactory::create($progressInfo->contentableType);
            $instance->process($progressInfo);
        } else {
            // exception
        }
    }

    public function getLessonProgressInfo(string $slug, int $lessonId, ?array $quizzes): LessonProgressResource
    {
        $studentId = Auth::id();

        $data = LessonProgress::query()
            ->join('courses', 'lesson_progress.course_id', '=', 'courses.id')
            ->join('lessons', function ($join) use ($lessonId) {
                $join->on('lessons.course_id', '=', 'courses.id')
                    ->where('lessons.id', '=', $lessonId);
            })
            ->where('lesson_progress.user_id', $studentId)
            ->where('courses.slug', $slug)
            ->select([
                'lesson_progress.id',
                'lesson_progress.lessons as progress_lessons',
                'lesson_progress.is_passed',
                'lesson_progress.total_marks',
                'courses.id as course_id',
                'courses.pass_marks',
                'lessons.id as lesson_id',
                'lessons.contentable_type',
                'lessons.contentable_id',
                'lessons.duration'
            ])
            ->firstOrFail();

        $progressLessons = json_decode($data->progress_lessons, true, 512, JSON_THROW_ON_ERROR);

        $lesson = collect($progressLessons)->firstWhere('id', $lessonId);

        return $this->buildLessonProgressResource($data, $lesson, $progressLessons, $quizzes);
    }

    private function buildLessonProgressResource(LessonProgress $progress, array $lesson, array $progressLessons, ?array $quizzes): LessonProgressResource
    {
        return new LessonProgressResource(
            $lesson['id'],
            $lesson['start_time'],
            $lesson['is_pass'],
            $lesson['contentable_id'],
            $lesson['contentable_type'],
            $progress->id,
            $progress->course_id,
            $progress->is_passed,
            $progress->pass_marks,
            (float) $progress->total_marks,
            (float) $progress->duration,
            $progressLessons,
            $quizzes ?? []
        );
    }
}
