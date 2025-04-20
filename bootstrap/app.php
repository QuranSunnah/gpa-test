<?php

use App\Exceptions\QuizFailedException;
use App\Http\Middleware\LogApiRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(LogApiRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if ($request->is('api/*')) {
                if ($exception instanceof UnauthorizedHttpException || $exception instanceof UnauthorizedException) {
                    $statusCode = Response::HTTP_UNAUTHORIZED;
                    return response()->json(
                        [
                            'status' => $statusCode,
                            'message' => $exception->getMessage()
                        ],
                        $statusCode
                    );
                } else if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                    $statusCode = $exception->getCode() && is_int($exception->getCode()) ? $exception->getCode() : Response::HTTP_NOT_FOUND;
                    return response()->json(
                        [
                            'status' => $statusCode,
                            'message' => config('app.debug') === true ? $exception->getMessage() : 'Data not found',
                        ],
                        $statusCode
                    );
                } elseif ($exception instanceof ValidationException) {
                    $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                    return response()->json([
                        'status' => $statusCode,
                        'message' => $exception->getMessage(),
                        'errors' => $exception->errors() ?? [],
                    ], $statusCode);
                } elseif ($exception instanceof QuizFailedException) {
                    $statusCode = $statusCode = $exception->getCode() && is_int($exception->getCode()) ? $exception->getCode() : Response::HTTP_FORBIDDEN;;
                    return response()->json([
                        'status' => $statusCode,
                        'message' => $exception->getMessage(),
                        'data' => [
                            'quiz_result' => $exception->getData()
                        ],
                    ], $statusCode);
                } else {
                    $statusCode = $exception->getCode() && is_int($exception->getCode()) ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

                    return response()->json(
                        [
                            'status' => $statusCode,
                            'message' => config('app.debug') === true || $statusCode === Response::HTTP_UNAUTHORIZED ? $exception->getMessage() : 'Your request can not process at this moment, please try again!',
                        ],
                        $statusCode
                    );
                }
            }
        });
    })->create();
