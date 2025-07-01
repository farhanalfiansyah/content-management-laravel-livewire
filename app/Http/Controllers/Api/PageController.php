<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Page",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="About Us"),
 *     @OA\Property(property="slug", type="string", example="about-us"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:25:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(
 *         property="author",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john@example.com")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PageDetailed",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Page"),
 *         @OA\Schema(
 *             @OA\Property(property="body", type="string", example="<p>Full page content with HTML...</p>")
 *         )
 *     }
 * )
 * 
 * @OA\Schema(
 *     schema="PageCreateRequest",
 *     required={"title", "body", "status"},
 *     @OA\Property(property="title", type="string", maxLength=255, example="Privacy Policy"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="privacy-policy"),
 *     @OA\Property(property="body", type="string", example="<p>Full page content with HTML...</p>"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published")
 * )
 * 
 * @OA\Schema(
 *     schema="PageUpdateRequest",
 *     @OA\Property(property="title", type="string", maxLength=255, example="Updated Page Title"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="updated-page-title"),
 *     @OA\Property(property="body", type="string", example="<p>Updated page content...</p>"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published")
 * )
 */
class PageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/pages",
     *     tags={"Pages"},
     *     summary="Get all pages",
     *     description="Retrieve a paginated list of pages with filtering and search capabilities",
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/status"),
     *     @OA\Parameter(
     *         name="include_drafts",
     *         in="query",
     *         description="Include draft pages",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_order"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="Pages retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Page")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Page::with(['user:id,name,email'])
                ->select(['id', 'title', 'slug', 'body', 'status', 'created_at', 'updated_at', 'user_id']);

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('body', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter by author
            if ($request->has('author') && !empty($request->author)) {
                $query->where('user_id', $request->author);
            }

            // Only published pages for public API (unless specifically requesting all)
            if (!$request->has('include_drafts') || !$request->include_drafts) {
                $query->where('status', 'published');
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if (in_array($sortBy, ['title', 'slug', 'created_at', 'updated_at'])) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
            $pages = $query->paginate($perPage);

            // Transform the response
            $pages->getCollection()->transform(function ($page) {
                return $this->formatPageResponse($page);
            });

            return response()->json([
                'success' => true,
                'data' => $pages->items(),
                'meta' => [
                    'current_page' => $pages->currentPage(),
                    'last_page' => $pages->lastPage(),
                    'per_page' => $pages->perPage(),
                    'total' => $pages->total(),
                    'from' => $pages->firstItem(),
                    'to' => $pages->lastItem(),
                ],
                'links' => [
                    'first' => $pages->url(1),
                    'last' => $pages->url($pages->lastPage()),
                    'prev' => $pages->previousPageUrl(),
                    'next' => $pages->nextPageUrl(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pages',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/pages",
     *     tags={"Pages"},
     *     summary="Create a new page",
     *     description="Create a new static page with auto-generated slug",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PageCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Page created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PageDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:pages,slug',
                'body' => 'required|string',
                'status' => 'required|in:draft,published',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
                
                // Ensure unique slug
                $originalSlug = $data['slug'];
                $counter = 1;
                while (Page::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Set user_id to authenticated user (you'll need authentication middleware)
            $data['user_id'] = auth()->id() ?? 1; // Fallback for testing

            $page = Page::create($data);

            // Load relationships for response
            $page->load(['user:id,name,email']);

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'data' => $this->formatPageResponse($page, true)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pages/{slug}",
     *     tags={"Pages"},
     *     summary="Get a specific page",
     *     description="Retrieve a single page by its slug, including full body content",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Page slug",
     *         @OA\Schema(type="string", example="about-us")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PageDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $page = Page::with(['user:id,name,email'])
                ->where('slug', $slug)
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatPageResponse($page, true) // Include full body content
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/pages/{id}",
     *     tags={"Pages"},
     *     summary="Update a page",
     *     description="Update an existing page by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Page ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PageUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page updated successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PageDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $page = Page::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|nullable|string|max:255|unique:pages,slug,' . $page->id,
                'body' => 'sometimes|required|string',
                'status' => 'sometimes|required|in:draft,published',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Generate slug if title is updated but slug is not provided
            if (isset($data['title']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
                
                // Ensure unique slug
                $originalSlug = $data['slug'];
                $counter = 1;
                while (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $page->update($data);

            // Load relationships for response
            $page->load(['user:id,name,email']);

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'data' => $this->formatPageResponse($page, true)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/pages/{id}",
     *     tags={"Pages"},
     *     summary="Delete a page",
     *     description="Delete a page by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Page ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $page = Page::findOrFail($id);
            
            // Delete page
            $page->delete();

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Format page response
     */
    private function formatPageResponse($page, $includeBody = false): array
    {
        $response = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'status' => $page->status,
            'created_at' => $page->created_at->toISOString(),
            'updated_at' => $page->updated_at->toISOString(),
            'author' => [
                'id' => $page->user->id,
                'name' => $page->user->name,
                'email' => $page->user->email,
            ],
        ];

        if ($includeBody) {
            $response['body'] = $page->body;
        } else {
            // For listing, show excerpt
            $response['excerpt'] = Str::limit(strip_tags($page->body), 150);
        }

        return $response;
    }
} 