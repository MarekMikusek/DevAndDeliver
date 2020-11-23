<?php

namespace App\Helpers;

class ReturnData
{
    /**
     * Create return data for ApiResponse
     */
    public static function create(array $retrunData): array
    {
        return [
            'error' => $retrunData['error'] ?? '',
            'code' => $retrunData['code'] ?? 200,
            'data' => $retrunData['data'] ?? [],
            'message' => $retrunData['message'] ?? '',
        ];
    }
}
