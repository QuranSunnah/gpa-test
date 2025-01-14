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
    public function updateAndUnlockNextLesson(LessonProgressResource $progressInfo, array $lessonProgress): array
    {
        DB::beginTransaction();
        try {
            $response = $this->updateLessonProgress($progressInfo, $lessonProgress);
            if ($response['is_course_passed']) {
                Certificate::firstOrCreate([
                    'user_id' => Auth::id(),
                    'course_id' => $progressInfo->courseId,
                ], [
                    'uuid' => Str::uuid(),
                ]);
            }

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Progress save failed.');
        }
    }

    private function updateLessonProgress(LessonProgressResource $progressInfo, array $lessonProgress): array
    {
        $lessons = Lesson::select('lessons.id', 'lessons.contentable_type', 'lessons.contentable_id', 'lessons.duration', 'lessons.media_info')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->where('sections.course_id', $progressInfo->courseId)
            ->orderBy('sections.order', 'ASC')
            ->orderBy('lessons.order', 'ASC')
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
        $totalMraks = (int) round((100 / $totalLessons) * $passedLessons);
        $isCoursePassed = ($totalLessons === $passedLessons) ? 1 : 0;

        LessonProgress::where('id', $progressInfo->progressId)
            ->update([
                'is_passed' => $isCoursePassed,
                'total_marks' => $totalMraks,
                'lessons' => $lessonProgress
            ]);

        $currentLesson = collect($lessonProgress)->where("id", $progressInfo->lessonId)->first();

        if ($currentLesson) {
            unset($currentLesson['start_time'], $currentLesson['end_time']);
        }

        return [
            'is_course_passed' => $isCoursePassed,
            'current_lesson' => $currentLesson,
            'next_lesson' => $nextLesson
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
