<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait CanSendJsonResponse
{
    protected function sendResponse(mixed $data, ?string $message = null, ?int $status = null): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data,
                'message' => $message ?: 'Success',
                'errors' => null,
            ],
            $status ?? 200
        );
    }

    protected function sendError(
        null|string|array $errors = null,
        string $message = '',
        int $status = 404
    ): JsonResponse {
        return response()->json(
            [
                'success' => false,
                'data' => null,
                'message' => $message,
                'errors' => $errors,
            ],
            $status
        );
    }
}
