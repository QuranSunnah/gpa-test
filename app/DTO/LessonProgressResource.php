<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Course;
use Illuminate\Http\Response;

class LessonProgressResource
{
    public int $lessonId;
    public int $startTime;
    public bool $isLessonPassed;
    public ?int $contentableId;
    public int $contentableType;
    public int $progressId;
    public int $courseId;
    public int $isProgressPassed;
    public int $passMarks;
    public float $totalMarks;
    public float $duration;
    public array $lessonProgress;
    public array $quizzes;

    public function __construct(
        int $lessonId,
        Course $courseInfo,
        ?array $quizzes,
    ) {
        $lessonProgress = json_decode($courseInfo->lesson_progress, true, 512, JSON_THROW_ON_ERROR);
        $lesson = collect($lessonProgress)->firstWhere('id', $lessonId);

        if (!$lesson) {
            throw new \Exception(__('Invalid Request: Lesson not found'), Response::HTTP_BAD_REQUEST);
        }

        $this->lessonId = $lessonId;
        $this->startTime = $lesson['start_time'];
        $this->isLessonPassed = $lesson['is_passed'];
        $this->contentableId = $lesson['contentable_id'];
        $this->contentableType = (int) $lesson['contentable_type'];
        $this->progressId = $courseInfo->lesson_progress_id;
        $this->courseId = $courseInfo->course_id;
        $this->isProgressPassed = $courseInfo->is_passed;
        $this->passMarks = $courseInfo->pass_marks;
        $this->totalMarks = (float) $courseInfo->total_marks;
        $this->duration = (float) $courseInfo->duration;
        $this->lessonProgress = $lessonProgress;
        $this->quizzes = $quizzes ?? [];
    }
}
