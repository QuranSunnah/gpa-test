<?php

declare(strict_types=1);

namespace App\DTO;

class LessonProgressResource
{
    public int $lessonId;
    public int $startTime;
    public int $isLessonPassed;
    public ?int $contentableId;
    public string $contentableType;
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
        int $startTime,
        int $isLessonPassed,
        ?int $contentableId,
        string $contentableType,
        int $progressId,
        int $courseId,
        int $isProgressPassed,
        int $passMarks,
        float $totalMarks,
        float $duration,
        array $lessonProgress,
        $quizzes = []
    ) {
        $this->lessonId = $lessonId;
        $this->startTime = $startTime;
        $this->isLessonPassed = $isLessonPassed;
        $this->contentableId = $contentableId;
        $this->contentableType = $contentableType;
        $this->progressId = $progressId;
        $this->courseId = $courseId;
        $this->isProgressPassed  = $isProgressPassed;
        $this->passMarks = $passMarks;
        $this->totalMarks = $totalMarks;
        $this->duration = $duration;
        $this->lessonProgress = $lessonProgress;
        $this->quizzes = $quizzes;
    }
}
