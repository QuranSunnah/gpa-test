<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizShowRequest;
use App\Http\Resources\QuizResource;

use App\Services\QuizService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;


class QuizController extends Controller
{
    use ApiResponse;

    public function __construct(private QuizService $service) {}

    public function show(int $lessonId)
    {
        $questions = $this->service->getQuizzes($lessonId);

        return $this->response($questions, __('Lesson progress data'));
    }
}
