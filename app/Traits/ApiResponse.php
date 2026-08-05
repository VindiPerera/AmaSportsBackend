<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Every API controller uses this trait so every endpoint returns the same
 * envelope shape: { success, message, data } or { success, message, errors }.
 * The mobile app's ApiError / ApiResponse types mirror this exactly.
 */
trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        $payload = $data instanceof JsonResource ? $data->resolve() : $data;

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $payload,
        ], $status);
    }

    protected function error(
        string $message = 'Something went wrong',
        int $status = 400,
        ?array $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
