<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Helpers\LessonHelper;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;

class Quiz implements LessonProgressInterface
{
    private LessonUnlockService $lessonUnlockService;

    public function __construct(LessonUnlockService $lessonUnlockService)
    {
        $this->lessonUnlockService = $lessonUnlockService;
    }

    public function process(LessonProgressResource $progressData): void
    {
        if (!LessonHelper::validateQuizData($progressData)) {
            throw new \Exception('Invalid lesson progress request.');
        }

        $updatedProgressResource = $this->updateLessonProgressObj($progressData);
        $this->lessonUnlockService->UpdateAndunLockNextLesson($progressData, $updatedProgressResource);
    }

    private function updateLessonProgressObj(LessonProgressResource $progressData): array
    {
        return array_map(
            fn($progress) => (int)$progress['id'] === $progressData->lesson->id
                ? array_merge($progress, [
                    'is_pass' => 1,
                    'end_time' => Carbon::now()->timestamp
                ])
                : $progress,
            json_decode($progressData->lessonProgress->lessons, true, 512, JSON_THROW_ON_ERROR)
        );
    }
}
