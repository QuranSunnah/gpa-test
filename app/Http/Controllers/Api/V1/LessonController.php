<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LessonService;
use App\Traits\ApiResponse;

class LessonController extends Controller
{
    use ApiResponse;

    public function __construct(private LessonService $service)
    {
    }

    public function show(int $lessonId)
    {
        $quizInfo = $this->service->getContent($lessonId);

        return $this->response($quizInfo, __('Lesson Info'));
    }
}
