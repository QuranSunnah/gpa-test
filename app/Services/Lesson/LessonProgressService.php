<?php

declare(strict_types=1);

namespace App\Services\Lesson;

use App\DTO\LessonProgressResource;
use App\Factories\LessonProgressFactory;
use App\Http\Requests\LessonProgressRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LessonProgressService
{
    public function processLessonProgress(string $slug, LessonProgressRequest $request): void
    {
        $progressData = $this->extractLessonProgress($slug, $request);

        if (!$progressData->isPassed) {
            $instance = LessonProgressFactory::create($progressData->contentableType);
            $instance->process($progressData);
        }
    }

    private function extractLessonProgress(string $slug, LessonProgressRequest $request): LessonProgressResource
    {
        $studentId = Auth::id();
        $course = Course::where("slug", $slug)->firstOrFail();

        $lesson = Lesson::where('id', $request->lesson_id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $lessonProgress = LessonProgress::where("user_id", $studentId)
            ->where("course_id", $course->id)
            ->firstOrFail();

        $lessons = json_decode($lessonProgress->lessons, true, 512, JSON_THROW_ON_ERROR);

        $lessonData = collect($lessons)->firstWhere('id', $request->lesson_id);

        try {
            return new LessonProgressResource(
                $lessonData['start_time'],
                $lessonData['contentable_type'],
                $lessonData['contentable_id'],
                $lessonData['is_pass'] ?? false,
                $course,
                $lesson,
                $lessonProgress,
                ...($request->quizzes ? [$request->quizzes] : [])
            );
        } catch (\Exception $e) {
            Log::error("Lesson with ID {$lesson->id} not found in progress data.");
        }

        throw ValidationException::withMessages([
            'error' => "Lesson with ID {$lesson->id} not found in progress data."
        ]);
    }
}
