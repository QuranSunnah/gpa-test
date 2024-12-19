<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Enroll;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Section;
use Exception;
use Illuminate\Support\Facades\DB;

class EnrollRepository
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

            if (!$lesson) {
                throw new Exception("No lessons found for course ID: $courseId");
            }

            $this->createLessonProgress($userId, $courseId, $lesson);

            $enroll->fill([
                'user_id' => $userId,
                'course_id' => $courseId,
                'start_at' => now(),
                'end_at' => now(),
                'status' => config('common.status.active'),
            ]);
            $enroll->save();

            DB::commit();

            return $enroll;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Enrollment failed: ' . $e->getMessage(), 0, $e);
        }
    }

    private function getFirstLesson(int $courseId): ?Lesson
    {
        return Lesson::where('course_id', $courseId)
            ->where('section_id', function ($query) use ($courseId) {
                $query->select('id')
                    ->from('sections')
                    ->where('course_id', $courseId)
                    ->orderBy('order', 'ASC')
                    ->limit(1);
            })
            ->orderBy('order', 'ASC')
            ->first();
    }

    private function createLessonProgress(int $userId, int $courseId, Lesson $lesson): void
    {
        $lessons = [
            [
                'id' => $lesson->id,
                'contentable_id' => $lesson->contentable_id,
                'contentable_type' => $lesson->contentable_type,
                'running_time' => 0,
                'is_pass' => 0,
                'start_time' => now(),
                'end_time' => now(),
                'created_at' => now(),
            ],
        ];

        LessonProgress::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lessons' => json_encode($lessons),
            'is_passed' => 0,
        ]);
    }
}
