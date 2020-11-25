<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class ReturnData
{
    /**
     * Create return data for ApiResponse
     */
    public static function create(array $returnData): JsonResponse
    {
        $code = $returnData['code'] ?? 200;
        return response()->json(
            [
            'code' => $code,
            'data' => $returnData['data'] ?? [],
            'message' => $returnData['message'] ?? '',
            ],
            $code
        );
    }
}
