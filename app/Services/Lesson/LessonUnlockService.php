<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Models\Certificate;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LessonUnlockService
{

    public function updateAndUnlockNextLesson(LessonProgressResource $progressInfo, array $lessonProgress): void
    {
        $response = $this->updateLessonProgress($progressInfo->courseId, $lessonProgress);

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

    private function updateLessonProgress(int $courseId, array $lessonProgress): array
    {
        $lessons = Lesson::select('id', 'contentable_type', 'contentable_id', 'duration')
            ->where("course_id", $courseId)
            ->whereNot('contentable_type', config('common.contentable_type.resource'))
            ->orderBy("order", "ASC")
            ->get();

        $totalLessons = $lessons->count();
        $nextLesson = $this->getNextLesson($lessons, $lessonProgress);

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

        $passedLessons = collect($lessonProgress)->where('is_pass', true)->count();
        return [
            'is_passed' => ($totalLessons === $passedLessons) ? 1 : 0,
            'total_marks' => $totalLessons > 0 ? (int) round((100 / $totalLessons) * $passedLessons) : 0,
            'lessons' => $lessonProgress
        ];
    }

    public function getNextLesson(Collection $lessons, array $lessonProgress): ?Lesson
    {
        if ($this->getIncompleteLessonsCount($lessonProgress) === 0) {
            $keyMap = array_fill_keys(array_column($lessonProgress, 'id'), true);

            return $lessons->first(fn($lesson) => !isset($keyMap[$lesson->id]));
        }
        return null;
    }

    public static function getIncompleteLessonsCount(array $lessonProgress): int
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
