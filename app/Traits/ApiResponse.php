<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    public function paginateResponse(LengthAwarePaginator $paginateData): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'paginate_data' => $paginateData,
        ], Response::HTTP_OK);
    }

    public function response($data, string $message = 'Data found', $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function msgResponse(string $message = 'Data found', $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $status);
    }
}
