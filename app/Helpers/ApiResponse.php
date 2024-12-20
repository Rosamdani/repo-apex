<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data = [], $extra = [], $code = 200)
    {
        return response()->json(array_merge([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $extra), $code);
    }

    public static function error($message, $code = 500, $errors = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
