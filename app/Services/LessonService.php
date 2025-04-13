<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\LessonRepository;
use Illuminate\Http\Response;

class LessonService
{
    public function __construct(private LessonRepository $repository) {}

    public function getContent(int $lessonId): array
    {
        $lessonProgress = $this->repository->getLessonProgress($lessonId);

        $lessons = collect(json_decode($lessonProgress->lesson_progress, true));

        $targetLesson = $lessons->first(
            fn($lesson) => $lesson['id'] == $lessonId
                && $lesson['contentable_type'] == config('common.contentable_type.lesson')
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
