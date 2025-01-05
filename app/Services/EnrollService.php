<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enroll;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Section;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class EnrollService
{
    public function enrollStudent(int $courseId, int $userId): Enroll
    {
        $enroll = Enroll::firstOrNew([
            'user_id' => $userId,
            'course_id' => $courseId,
        ]);

        if ($enroll->exists) {
            return $this->handleExistingEnrollment($enroll);;
        }

        return $this->handleNewEnrollment($enroll, $userId, $courseId);
    }

    private function handleExistingEnrollment(Enroll $enroll): Enroll
    {
        if ($enroll->status === config('common.status.inactive')) {
            $enroll->update([
                'start_at' => now(),
                'end_at' => now(),
                'status' => config('common.status.active'),
            ]);
        }

        return $enroll;
    }

    private function handleNewEnrollment(Enroll $enroll, int $userId, int $courseId): Enroll
    {
        DB::beginTransaction();

        try {
            $lesson = $this->getFirstLesson($courseId);

            $this->createLessonProgress($userId, $courseId, $lesson);

            $enroll->fill([
                'user_id' => $userId,
                'course_id' => $courseId,
                'start_at' => now(),
                'end_at' => now(),
                'status' => config('common.status.active'),
            ])->save();

            DB::commit();

            return $enroll;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Enrollment failed: ' . $e->getMessage());
        }
    }

    private function getFirstLesson(int $courseId): Lesson
    {
        $lesson = Lesson::select('lessons.*')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $courseId)
            ->where('lessons.course_id', $courseId)
            ->orderBy('sections.order', 'ASC')
            ->orderBy('lessons.order', 'ASC')
            ->first();

        if (!$lesson) {
            throw new Exception("No lessons found for course");
        }

        return $lesson;
    }


    private function createLessonProgress(int $userId, int $courseId, Lesson $lesson): void
    {
        LessonProgress::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lessons' => json_encode([
                [
                    'id' => $lesson->id,
                    'contentable_id' => $lesson->contentable_id,
                    'contentable_type' => $lesson->contentable_type,
                    'is_pass' => 0,
                    'start_time' => Carbon::now()->timestamp,
                    'end_time' => null,
                ],
            ]),
            'is_passed' => 0,
        ]);
    }
}
