<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Lesson;
use App\Models\LessonProgress;

class LessonProgressResource
{
    public int $startTime;
    public string $contentableType;
    public ?int $contentableId;
    public int $isPassed;
    public Lesson $lesson;
    public LessonProgress $lessonProgress;
    public array $quizzes;

    public function __construct(
        int $startTime,
        string $contentableType,
        ?int $contentableId,
        int $isPassed,
        Lesson $lesson,
        LessonProgress $lessonProgress,
        $quizzes = []
    ) {
        $this->startTime = $startTime;
        $this->contentableType = $contentableType;
        $this->contentableId = $contentableId;
        $this->isPassed = $isPassed;
        $this->lesson = $lesson;
        $this->lessonProgress = $lessonProgress;
        $this->quizzes = $quizzes;
    }
}
