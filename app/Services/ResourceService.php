<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Resource;
use App\Repositories\LessonRepository;
use Illuminate\Http\Response;

class ResourceService
{
    public function __construct(private LessonRepository $repository)
    {
    }

    public function getResource(int $lessonId): array
    {
        $resourceId = $this->getResourceId($lessonId);
        $resouceInfo = Resource::findOrFail($resourceId);

        return [
            'id' => $resouceInfo->id,
            'title' => $resouceInfo->title,
            'file' => $resouceInfo->file_path,
            'instructions' => $resouceInfo->instructions,
        ];
    }

    private function getResourceId(int $lessonId): int
    {
        $lessonProgress = $this->repository->getLessonProgress($lessonId);

        $lessons = collect(json_decode($lessonProgress->lessons, true));

        $targetLesson = $lessons->first(
            fn ($lesson) => $lesson['id'] == $lessonId
                && $lesson['contentable_type'] == config('common.contentable_type.resource')
        );

        if (!$targetLesson) {
            throw new \Exception(__('Invalid Request: Requested resource not found'), Response::HTTP_BAD_REQUEST);
        }

        return $lessonProgress->contentable_id;
    }
}
