<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\QuizService;
use App\Traits\ApiResponse;

class QuizController extends Controller
{
    use ApiResponse;

    public function __construct(private QuizService $service)
    {
    }

    public function show(int $lessonId)
    {
        $quizInfo = $this->service->getQuizzes($lessonId);

        return $this->response($quizInfo, __('Quiz Info'));
    }
}
