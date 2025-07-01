<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{
    /**
     * Success response method
     */
    protected function successResponse($data = null, string $message = '', int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error response method
     */
    protected function errorResponse(string $message = '', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     */
    protected function validationErrorResponse($errors, string $message = 'Validation failed')
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found')
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Paginated response method
     */
    protected function paginatedResponse($data, string $message = '')
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            'links' => [
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage()),
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * Created response method
     */
    protected function createdResponse($data = null, string $message = 'Resource created successfully')
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Updated response method
     */
    protected function updatedResponse($data = null, string $message = 'Resource updated successfully')
    {
        return $this->successResponse($data, $message, 200);
    }

    /**
     * Deleted response method
     */
    protected function deletedResponse(string $message = 'Resource deleted successfully')
    {
        return $this->successResponse(null, $message, 200);
    }
} 