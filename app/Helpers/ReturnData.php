<?php

namespace App\Helpers;

class ReturnData
{
    /**
     * Create return data for ApiResponse
     */
    public static function create(array $returnData): array
    {
        return [
            'code' => $returnData['code'] ?? 200,
            'data' => $returnData['data'] ?? [],
            'message' => $returnData['message'] ?? '',
        ];
    }
}
