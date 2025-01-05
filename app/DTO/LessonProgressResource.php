<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;

class LessonProgressResource
{
    public int $startTime;
    public string $contentableType;
    public ?int $contentableId;
    public int $isPassed;
    public Course $course;
    public Lesson $lesson;
    public LessonProgress $lessonProgress;
    public array $quizzes;

    public function __construct(
        int $startTime,
        string $contentableType,
        ?int $contentableId,
        int $isPassed,
        Course $course,
        Lesson $lesson,
        LessonProgress $lessonProgress,
        $quizzes = []
    ) {
        $this->startTime = $startTime;
        $this->contentableType = $contentableType;
        $this->contentableId = $contentableId;
        $this->isPassed = $isPassed;
        $this->course = $course;
        $this->lesson = $lesson;
        $this->lessonProgress = $lessonProgress;
        $this->quizzes = $quizzes;
    }
}
