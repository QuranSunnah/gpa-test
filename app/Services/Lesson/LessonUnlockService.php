<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Models\Certificate;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Repositories\LessonRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LessonUnlockService
{
    private LessonRepository $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function updateAndUnlockNextLesson(LessonProgressResource $progressInfo, array $lessonProgress): void
    {
        $lessons = $this->lessonRepository->getLessons($progressInfo->courseId);
        $nextLesson = $this->getNextLessonData($lessons, $lessonProgress);

        $response = $this->updateLessonProgress($lessons,  $nextLesson, $lessonProgress);

        DB::beginTransaction();
        try {
            LessonProgress::where('id', $progressInfo->progressId)->update($response);
            if ($response['is_passed']) {
                Certificate::firstOrCreate([
                    "user_id" => Auth::id(),
                    "course_id" => $progressInfo->courseId
                ], [
                    'uuid' => Str::uuid()
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Progress save failed" . $e->getMessage());
        }
    }

    private function updateLessonProgress(Collection $lessons, ?Lesson $nextLesson, array $lessonProgress): array
    {
        $totalLessons = $lessons->count();

        if ($nextLesson) {
            $lessonProgress[] = [
                'id' => $nextLesson->id,
                'contentable_id' => $nextLesson->contentable_id,
                'contentable_type' => $nextLesson->contentable_type,
                'is_pass' => 0,
                'start_time' => Carbon::now()->timestamp,
                'end_time' => null,
            ];
        }

        $passedLessons = collect($lessonProgress)
            ->where('is_pass', true)
            ->count();

        $totalMarks = $totalLessons > 0 ? (int) round((100 / $totalLessons) * $passedLessons) : 0;

        $isPassed = ($totalLessons === $passedLessons) ? 1 : 0;

        return [
            'is_passed' => $isPassed,
            'total_marks' => $totalMarks,
            'lessons' => $lessonProgress
        ];
    }

    public function getNextLessonData(Collection $lessons, array $lessonProgress): ?Lesson
    {
        if ($this->getIncompleteLessons($lessonProgress) === 0) {
            $keyMap = array_fill_keys(array_column($lessonProgress, 'id'), true);

            return $lessons->first(fn($lesson) => !isset($keyMap[$lesson->id]));
        }
        return null;
    }


    public static function getIncompleteLessons(array $lessonProgress): int
    {
        $totalIncompleteLessons = array_reduce(
            $lessonProgress,
            function ($count, $progress) {
                $isLesson = $progress['contentable_type'] === config('common.contentable_type.lesson');
                $isNotPass = !$progress['is_pass'];

                return $count + ($isNotPass && $isLesson ? 1 : 0);
            },
            0
        );

        return $totalIncompleteLessons;
    }
}
