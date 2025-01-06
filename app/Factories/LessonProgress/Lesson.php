<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;

class Lesson implements LessonProgressInterface
{
    public function __construct(private LessonUnlockService $lessonUnlockService) {}

    public function process(LessonProgressResource $progressInfo): array
    {
        $this->validateTimeDuration($progressInfo);

        $updatedProgressResource = array_map(
            fn($progress) => (int)$progress['id'] === $progressInfo->lessonId
                ? array_merge($progress, [
                    'is_pass' => 1,
                    'end_time' => Carbon::now()->timestamp
                ])
                : $progress,
            $progressInfo->lessonProgress
        );

        $this->lessonUnlockService->updateAndUnlockNextLesson($progressInfo, $updatedProgressResource);

        return collect($updatedProgressResource)->firstWhere('id', $progressInfo->lessonId);
    }

    private function validateTimeDuration(LessonProgressResource $progressInfo): bool
    {
        $lessonDuration = $progressInfo->duration;
        $previousTimeCarbon = Carbon::parse($progressInfo->startTime);
        $currentTime = now();
        $diffInSeconds = $previousTimeCarbon->diffInSeconds($currentTime);

        $ration = ($lessonDuration / 100) * 50;

        if (($lessonDuration && $ration <= $diffInSeconds) || !$lessonDuration) {
            return true;
        }
        throw new \Exception('Invalid Request: duration');
    }
}
