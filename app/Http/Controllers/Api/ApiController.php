<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Base API Controller
 * 
 * Provides common functionality for API controllers
 */
abstract class ApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * Items per page for pagination
     */
    protected int $perPage = 15;

    /**
     * Maximum items per page
     */
    protected int $maxPerPage = 50;

    /**
     * Get pagination parameters from request
     */
    protected function getPaginationParams($request): array
    {
        return [
            'per_page' => min($request->get('per_page', $this->perPage), $this->maxPerPage),
            'page' => max($request->get('page', 1), 1)
        ];
    }

    /**
     * Get search parameters from request
     */
    protected function getSearchParams($request): array
    {
        return [
            'search' => $request->get('search', ''),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];
    }

    /**
     * Validate sort parameters
     */
    protected function validateSortParams(string $sortBy, array $allowedFields): string
    {
        return in_array($sortBy, $allowedFields) ? $sortBy : 'created_at';
    }

    /**
     * Validate sort order
     */
    protected function validateSortOrder(string $sortOrder): string
    {
        return in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
    }
} 