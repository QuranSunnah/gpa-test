<?php

declare(strict_types=1);

namespace App\Factories\LessonProgress;

use App\DTO\LessonProgressResource;
use App\Factories\Interfaces\LessonProgressInterface;
use App\Services\Lesson\LessonUnlockService;
use Carbon\Carbon;
use Illuminate\Http\Response;

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
                    'is_passed' => true,
                    'end_time' => Carbon::now()->timestamp,
                ])
                : $progress,
            $progressInfo->lessonProgress
        );

        return $this->lessonUnlockService->updateAndUnlockNextLesson($progressInfo, $updatedProgressResource);
    }

    private function validateTimeDuration(LessonProgressResource $progressInfo): bool
    {
        $lessonDuration = $progressInfo->duration;

        $startTimeDiffWithCurrent = Carbon::parse($progressInfo->startTime)->diffInSeconds(now());

        $lessonWatchRatio = ($lessonDuration / 100) * (int) env('LESSON_MIN_WATCH_PERCENTAGE');

        if (($lessonDuration && $lessonWatchRatio <= $startTimeDiffWithCurrent) || !$lessonDuration) {
            return true;
        }
        throw new \Exception(__('Invalid Request: duration'), Response::HTTP_BAD_REQUEST);
    }
}
