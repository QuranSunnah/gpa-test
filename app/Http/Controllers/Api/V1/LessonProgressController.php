<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonProgressRequest;
use App\Models\LessonProgress;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LessonProgressResource;

class LessonProgressController extends Controller
{
    use ApiResponse;

    public function show(LessonProgressRequest $request, int $id)
    {
        $studentId = Auth::id();
        $lessonProgress =  LessonProgress::where("user_id", $studentId)
            ->where("course_id", $id)
            ->firstOrFail();

        return $this->response(new LessonProgressResource($lessonProgress), __("Lesson progress data"));
    }
}
