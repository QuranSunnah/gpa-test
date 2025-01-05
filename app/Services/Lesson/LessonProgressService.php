<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Factories\LessonProgressFactory;
use App\Http\Requests\LessonProgressRequest;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\returnValue;

class LessonProgressService
{
    public function processLessonProgress(int $courseId, LessonProgressRequest $request): void
    {
        $progressData = $this->extractLessonProgress($courseId, $request);

        if (!$progressData->isPassed) {
            $instance = LessonProgressFactory::create($progressData->contentableType);
            $instance->process($progressData);
        }
    }

    private function extractLessonProgress(int $courseId, LessonProgressRequest $request): LessonProgressResource
    {
        $studentId = Auth::id();
        $currentLesson = Lesson::where('id', $request->lesson_id)
            ->where('course_id', $courseId)
            ->firstOrFail();

        $lessonProgress = LessonProgress::where("user_id", $studentId)
            ->where("course_id", $courseId)
            ->firstOrFail();

        $lessons = json_decode($lessonProgress->lessons, true, 512, JSON_THROW_ON_ERROR);

        $lessonData = collect($lessons)->firstWhere('id', $request->lesson_id);

        try {
            return new LessonProgressResource(
                $lessonData['start_time'],
                $lessonData['contentable_type'],
                $lessonData['contentable_id'],
                $lessonData['is_pass'] ?? false,
                $currentLesson,
                $lessonProgress,
                ...($request->quizzes ? [$request->quizzes] : [])
            );
        } catch (\Exception $e) {
            Log::error("Lesson with ID {$currentLesson->id} not found in progress data.");
        }

        throw ValidationException::withMessages([
            'error' => "Lesson with ID {$currentLesson->id} not found in progress data."
        ]);
    }
}
