<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="Content Management System API",
 *     version="1.0.0",
 *     description="Comprehensive REST API for managing posts, categories, and pages. Built with Laravel and following REST best practices.",
 *     @OA\Contact(
 *         email="api@example.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.example.com",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 * 
 * @OA\Tag(
 *     name="Posts",
 *     description="Operations related to blog posts"
 * )
 * 
 * @OA\Tag(
 *     name="Categories",
 *     description="Operations related to post categories"
 * )
 * 
 * @OA\Tag(
 *     name="Pages",
 *     description="Operations related to static pages"
 * )
 * 
 * @OA\Tag(
 *     name="Utility",
 *     description="Utility endpoints for health checks and testing"
 * )
 * 
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation completed successfully"),
 *     @OA\Property(property="data", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="An error occurred"),
 *     @OA\Property(property="errors", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validation failed"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="field_name",
 *             type="array",
 *             @OA\Items(type="string", example="The field is required.")
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=5),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=75),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="to", type="integer", example=15)
 * )
 * 
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     @OA\Property(property="first", type="string", example="http://domain.com/api/v1/posts?page=1"),
 *     @OA\Property(property="last", type="string", example="http://domain.com/api/v1/posts?page=5"),
 *     @OA\Property(property="prev", type="string", nullable=true, example=null),
 *     @OA\Property(property="next", type="string", example="http://domain.com/api/v1/posts?page=2")
 * )
 * 
 * @OA\Schema(
 *     schema="PaginatedResponse",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example=""),
 *     @OA\Property(property="data", type="array", @OA\Items()),
 *     @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
 *     @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
 * )
 * 
 * @OA\Parameter(
 *     parameter="search",
 *     name="search",
 *     in="query",
 *     description="Search term to filter results",
 *     required=false,
 *     @OA\Schema(type="string", example="laravel")
 * )
 * 
 * @OA\Parameter(
 *     parameter="status",
 *     name="status",
 *     in="query",
 *     description="Filter by status",
 *     required=false,
 *     @OA\Schema(type="string", enum={"draft", "published"}, example="published")
 * )
 * 
 * @OA\Parameter(
 *     parameter="sort_by",
 *     name="sort_by",
 *     in="query",
 *     description="Field to sort by",
 *     required=false,
 *     @OA\Schema(type="string", enum={"title", "created_at", "updated_at", "published_at"}, example="created_at")
 * )
 * 
 * @OA\Parameter(
 *     parameter="sort_order",
 *     name="sort_order",
 *     in="query",
 *     description="Sort order",
 *     required=false,
 *     @OA\Schema(type="string", enum={"asc", "desc"}, example="desc")
 * )
 * 
 * @OA\Parameter(
 *     parameter="per_page",
 *     name="per_page",
 *     in="query",
 *     description="Number of items per page (max 50)",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1, maximum=50, example=15)
 * )
 * 
 * @OA\Parameter(
 *     parameter="page",
 *     name="page",
 *     in="query",
 *     description="Page number",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1, example=1)
 * )
 */
class SwaggerController extends Controller
{
    // This controller only exists for Swagger annotations
} 