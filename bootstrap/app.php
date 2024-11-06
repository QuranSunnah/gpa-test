<?php

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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if ($request->is('api/*')) {
                if ($exception instanceof UnauthorizedHttpException || $exception instanceof UnauthorizedException) {
                    return response()->json(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
                } else if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                    return response()->json(
                        [
                            'message' => config('app.debug') === true ? $exception->getMessage() : 'Data not found',
                        ],
                        $exception->getCode() && is_int($exception->getCode()) ? $exception->getCode() : Response::HTTP_NOT_FOUND
                    );
                } elseif ($exception instanceof ValidationException) {
                    return response()->json([
                        'message' => $exception->getMessage(),
                        'errors' => $exception->errors() ?? [],
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $errorCode = $exception->getCode() && is_int($exception->getCode()) ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

                    return response()->json(
                        [
                            'message' => config('app.debug') === true || $errorCode === Response::HTTP_UNAUTHORIZED ? $exception->getMessage() : 'Your request can not process at this moment, please try again!',
                        ],
                        $errorCode
                    );
                }
            }
        });
    })->create();
