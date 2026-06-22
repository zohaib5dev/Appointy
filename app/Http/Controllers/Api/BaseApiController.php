<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function success(array|string $data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([ 'message' => $message, 'data' => $data ], $status);
    }

    protected function error(string $message = 'Error', int $status = 422, array $errors = []): JsonResponse
    {
        return response()->json([ 'message' => $message, 'errors' => $errors ], $status);
    }
}
