<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;


class ResponseHelper
{
    public static function jsonResponse($succes, $message, $data, $statusCode): JsonResponse
    {
        return response()->json([
            'success' => $succes,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}