<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Helpers\LessonHelper;
use App\Models\Lesson;
use App\Repositories\CourseRepository;
use App\Repositories\LessonRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LessonUnlockService
{
    private LessonRepository $lessonRepository;
    private CourseRepository $courseRepository;

    public function __construct(
        LessonRepository $lessonRepository,
        CourseRepository $courseRepository,
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->courseRepository = $courseRepository;
    }

    public function updateAndUnlockNextLesson(LessonProgressResource $progressData, array $lessonProgress): void
    {
        $courseId = $progressData->lesson->course_id;
        $lessonData = $this->lessonRepository->getLessons($courseId);

        if (LessonHelper::validateIncompleteLessons($lessonProgress) === 0) {
            $nextLessonData = $this->getNextLessonData($lessonData, $lessonProgress);

            if ($nextLessonData) {
                $lessonProgress[] = $this->prepareNextLessonProgress($nextLessonData);
            }
        }

        $response = $this->determinePassStatus($lessonData, $lessonProgress, $courseId);

        $this->lessonRepository->updateLessonProgress($progressData->lessonProgress->id, [
            'lessons' => $lessonProgress,
            ...$response,
        ]);
    }


    public function getNextLessonData(Collection $lessonData, array $lessonProgress): ?Lesson
    {
        $keyMap = array_fill_keys(array_column($lessonProgress, 'id'), true);

        return $lessonData->first(fn($lesson) => !isset($keyMap[$lesson->id]));
    }

    private function prepareNextLessonProgress(Lesson $nextLessonData): array
    {
        return [
            'id' => $nextLessonData->id,
            'contentable_id' => $nextLessonData->contentable_id,
            'contentable_type' => $nextLessonData->contentable_type,
            'is_pass' => 0,
            'start_time' => Carbon::now()->timestamp,
            'end_time' => null,
        ];
    }

    private function determinePassStatus(Collection $lessonData, array $lessonProgress, int $courseId): array
    {
        $totalLessons = $lessonData->count();

        $passedLessons = collect($lessonProgress)
            ->where('is_pass', true)
            ->count();

        $totalMarks = $totalLessons > 0 ? (int) round((100 / $totalLessons) * $passedLessons) : 0;

        // $course = $this->courseRepository->findById($courseId);
        $isPasseded = ($totalLessons === $passedLessons) ? 1 : 0;

        return [
            'is_passed' => $isPasseded,
            'total_marks' => $totalMarks,
        ];
    }
}
