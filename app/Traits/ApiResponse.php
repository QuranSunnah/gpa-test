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

    public function response($data, string $message = 'Data found'): JsonResponse
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_OK);
    }
}
