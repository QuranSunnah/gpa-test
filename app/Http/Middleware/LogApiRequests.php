<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    public function handle(Request $request, \Closure $next): Response
    {
        $requestStartTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);

        try {
            $response = $next($request);
            $user = Auth::user();
            $endTime = microtime(true);
            $duration = number_format($endTime - $requestStartTime, 3);

            $responseData = json_decode($response->getContent(), true);
            $logData = $this->buildLogData($request, [
                'status' => $response->getStatusCode(),
                'message' => $responseData['message'] ?? '',
                'api_has_error' => $response->getStatusCode() >= 400 ? 1 : 0,
                'request_start_time' => $requestStartTime,
                'request_end_time' => $endTime,
                'response_time' => $duration,
                'user_id' => $user?->email ?? '',
                'debug_info' => [],
            ]);

            Log::channel('api')->info('API Log', $logData);

            return $response;
        } catch (\Throwable $e) {
            $endTime = microtime(true);
            $duration = number_format($endTime - $requestStartTime, 3);
            $user = Auth::user();
            $logData = $this->buildLogData($request, [
                'message' => $e->getMessage(),
                'api_has_error' => 1,
                'request_start_time' => $requestStartTime,
                'request_end_time' => $endTime,
                'response_time' => $duration,
                'user_id' => $user?->email ?? '',
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->take(5),
                ],
            ]);

            Log::channel('api')->error('API Exception', $logData);

            throw $e;
        }
    }

    private function buildLogData(Request $request, array $overrides = []): array
    {
        return array_merge([
            'remote_host' => $request->getClientIp(),
            'method' => $request->getMethod(),
            'uri' => $request->getRequestUri(),
            'protocol' => $request->getScheme(),
            'referer' => $request->headers->get('referer', '-'),
            'user_agent' => $request->userAgent(),
            'forwarded_for' => explode(',', $request->header('X-Forwarded-For', $request->ip())),
            'ip_address' => [$request->ip()],
            'corelation_id' => $request->header('corelation_id'),
            'device_id' => $request->header('device_id'),
            'hostanme' => gethostname(),
            'datetime' => now()->toIso8601String(),
        ], $overrides);
    }
}
