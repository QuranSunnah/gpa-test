<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class Lesson implements LessonProgressInterface
{
    public function __construct(private LessonUnlockService $lessonUnlockService)
    {
    }

    public function process(LessonProgressResource $progressInfo): array
    {
        $this->validateTimeDuration($progressInfo);

        $updatedProgressResource = array_map(
            fn ($progress) => (int) $progress['id'] === $progressInfo->lessonId
                ? array_merge($progress, [
                    'is_pass' => 1,
                    'end_time' => Carbon::now()->timestamp,
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
        $startTimeDiffWithCurrent = Carbon::parse($progressInfo->startTime)->diffInSeconds(now());

        $lessonWatchRatio = ($lessonDuration / 100) * 50;

        if (($lessonDuration && $lessonWatchRatio <= $startTimeDiffWithCurrent) || !$lessonDuration) {
            return true;
        }
        throw new ValidationException('Invalid Request: duration');
    }
}
