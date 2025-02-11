<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\Enroll;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnrollService
{
    public function enrollStudent(string $slug): Enroll
    {
        $studentId = Auth::id();

        $course = Course::select('id')->where('slug', $slug)->first();

        if (!$course) {
            throw new NotFoundHttpException(_('Invalid Request: course not found'));
        }

        $enroll = Enroll::where([
            'user_id' => $studentId,
            'course_id' => $course->id,
        ])
            ->first();

        if ($enroll) {
            return $this->handleExistingEnrollment($enroll);
        }

        return $this->handleNewEnrollment($studentId, $course->id);
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

    private function handleNewEnrollment(int $studentId, int $courseId): Enroll
    {
        DB::beginTransaction();

        try {
            $lesson = $this->getLesson($courseId);
            $this->createLessonProgress($studentId, $courseId, $lesson);

            $enroll = Enroll::create([
                'user_id' => $studentId,
                'course_id' => $courseId,
                'start_at' => now(),
                'end_at' => now(),
                'status' => config('common.status.active'),
            ]);

            DB::commit();

            return $enroll;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Enrollment failed: ' . $e->getMessage());
        }
    }

    private function getLesson(int $courseId): Lesson
    {
        $lesson = Lesson::select('lessons.*')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $courseId)
            ->where('lessons.course_id', $courseId)
            ->orderBy('sections.order', 'ASC')
            ->orderBy('lessons.order', 'ASC')
            ->first();

        if (!$lesson) {
            throw new ModelNotFoundException('No lessons found for this course');
        }

        return $lesson;
    }

    private function createLessonProgress(int $studentId, int $courseId, Lesson $lesson): void
    {
        LessonProgress::firstOrCreate(
            [
                'user_id' => $studentId,
                'course_id' => $courseId,
            ],
            [
                'lessons' => json_encode([
                    [
                        'id' => $lesson->id,
                        'contentable_id' => $lesson->contentable_id,
                        'contentable_type' => $lesson->contentable_type,
                        'is_passed' => false,
                        'start_time' => Carbon::now()->timestamp,
                        'end_time' => null,
                    ],
                ]),
                'is_passed' => 0,
            ]
        );
    }
}
