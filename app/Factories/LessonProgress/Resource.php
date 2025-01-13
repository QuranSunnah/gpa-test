<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;

class Resource implements LessonProgressInterface
{
    public function __construct(private LessonUnlockService $lessonUnlockService) {}

    public function process(LessonProgressResource $progressInfo): array
    {
        $updatedProgressResource = array_map(
            fn($progress) => (int) $progress['id'] === $progressInfo->lessonId
                ? array_merge($progress, [
                    'is_pass' => 1,
                    'end_time' => Carbon::now()->timestamp,
                ])
                : $progress,
            $progressInfo->lessonProgress
        );

        return $this->lessonUnlockService->updateAndUnlockNextLesson($progressInfo, $updatedProgressResource);
    }
}
