<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonProgressRequest;
use App\Http\Resources\LessonProgressResource;
use App\Models\Enroll;
use App\Services\Lesson\LessonProgressService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class LessonProgressController extends Controller
{
    use ApiResponse;

    public function __construct(private LessonProgressService $service)
    {
    }

    public function show(LessonProgressRequest $request, string $slug)
    {
        $lessonProgress = Enroll::join('courses', 'enrolls.course_id', '=', 'courses.id')
            ->where('enrolls.user_id', Auth::id())
            ->where('courses.slug', $slug)
            ->select('enrolls.*')
            ->firstOrFail();

        return $this->response(new LessonProgressResource($lessonProgress), __('Lesson progress data'));
    }

    public function save(LessonProgressRequest $request, string $slug)
    {
        $response = $this->service->processLessonProgress($slug, $request);

        return $this->response($response, __('Lesson progress updated'));
    }
}
