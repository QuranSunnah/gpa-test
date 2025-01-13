<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LessonProgress;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LessonService
{
    public function getContent(int $lessonId): array
    {
        $lessonProgress = LessonProgress::join('enrolls', 'enrolls.course_id', '=', 'lesson_progress.course_id')
            ->join('lessons', 'lessons.course_id', '=', 'enrolls.course_id')
            ->where([
                ['lessons.id', '=', $lessonId],
                ['enrolls.user_id', '=', Auth::id()],
                ['enrolls.status', '=', config('common.status.active')],
                ['lesson_progress.user_id', '=', Auth::id()]
            ])
            ->select('lessons.*', 'lesson_progress.lessons',)
            ->firstOrFail();

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn($lesson) =>
            $lesson['id'] == $lessonId &&
                $lesson['contentable_type'] == config('common.contentable_type.lesson')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested lesson not found'), Response::HTTP_BAD_REQUEST);
        }

        return [
            'id' => $lessonId,
            'title' => $lessonProgress->title ?? null,
            'media_info' => json_decode($lessonProgress->media_info ?? '', true),
        ];
    }
}
