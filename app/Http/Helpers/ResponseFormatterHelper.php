<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseFormatterHelper
{
    /**
     * Format a successful API response.
     *
     * @param mixed $data
     * @param string|null $message
     * @return JsonResponse
     */
    public static function success($data = null, $message = null): JsonResponse
    {
        $response = [
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => $message
            ],
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Format a successful API response with empty data.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public static function successWithEmpty($data = null, $message = null, $code = 400): JsonResponse
    {
        $response = [
            'meta' => [
                'code' => $code,
                'status' => 'error',
                'message' => $message
            ],
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    /**
     * Format an error API response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    public static function error($data = null, $message = null, $code = 400): JsonResponse
    {
        $response = [
            'meta' => [
                'code' => $code,
                'status' => 'error',
                'message' => $message
            ],
            'data' => $data
        ];

        return response()->json($response, $code);
    }
}