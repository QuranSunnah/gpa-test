<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonProgressRequest;
use App\Models\LessonProgress;
use App\Services\Lesson\LessonProgressService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LessonProgressResource;

class LessonProgressController extends Controller
{
    use ApiResponse;

    private LessonProgressService $lessonProgressService;

    public function __construct(LessonProgressService $lessonProgressService)
    {
        $this->lessonProgressService = $lessonProgressService;
    }

    public function show(LessonProgressRequest $request, string $slug)
    {
        $studentId = Auth::id();

        $lessonProgress = LessonProgress::query()
            ->join('courses as C', 'lesson_progress.course_id', '=', 'C.id')
            ->where('lesson_progress.user_id', $studentId)
            ->where('C.slug', $slug)
            ->select('lesson_progress.*')
            ->firstOrFail();

        return $this->response(new LessonProgressResource($lessonProgress), __("Lesson progress data"));
    }

    public function save(LessonProgressRequest $request, string $slug)
    {
        $this->lessonProgressService->processLessonProgress($slug, $request);

        return $this->response([], __("Lesson progress updated"));
    }
}
