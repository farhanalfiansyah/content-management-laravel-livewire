<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Category",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Technology"),
 *     @OA\Property(property="slug", type="string", example="technology"),
 *     @OA\Property(property="posts_count", type="integer", example=15),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:25:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z")
 * )
 * 
 * @OA\Schema(
 *     schema="CategoryDetailed",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Category"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="posts",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Post")
 *             )
 *         )
 *     }
 * )
 * 
 * @OA\Schema(
 *     schema="CategoryCreateRequest",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="Web Development"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="web-development")
 * )
 * 
 * @OA\Schema(
 *     schema="CategoryUpdateRequest",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Updated Category Name"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="updated-category-name")
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     description="Retrieve a list of categories with post counts and optional search filtering",
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(
     *         name="with_posts",
     *         in="query",
     *         description="Include posts relationship",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_order"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Category")
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
            $query = Category::withCount('posts');

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            // Include posts if requested
            if ($request->has('with_posts') && $request->with_posts) {
                $query->with(['posts' => function($q) {
                    $q->where('status', 'published')
                      ->with(['user:id,name,email'])
                      ->select(['id', 'title', 'slug', 'short_description', 'image', 'status', 'published_at', 'user_id'])
                      ->latest('published_at');
                }]);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            if (in_array($sortBy, ['name', 'created_at', 'updated_at'])) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $categories = $query->paginate($perPage);

            // Transform response
            $categories->getCollection()->transform(function ($category) use ($request) {
                $response = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'posts_count' => $category->posts_count,
                    'created_at' => $category->created_at->toISOString(),
                    'updated_at' => $category->updated_at->toISOString(),
                ];

                if ($request->has('with_posts') && $request->with_posts) {
                    $response['posts'] = $category->posts->map(function ($post) {
                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                            'slug' => $post->slug,
                            'short_description' => $post->short_description,
                            'image_url' => $post->image_url,
                            'status' => $post->status,
                            'published_at' => $post->published_at?->toISOString(),
                            'author' => [
                                'id' => $post->user->id,
                                'name' => $post->user->name,
                                'email' => $post->user->email,
                            ],
                        ];
                    });
                }

                return $response;
            });

            return response()->json([
                'success' => true,
                'data' => $categories->items(),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'from' => $categories->firstItem(),
                    'to' => $categories->lastItem(),
                ],
                'links' => [
                    'first' => $categories->url(1),
                    'last' => $categories->url($categories->lastPage()),
                    'prev' => $categories->previousPageUrl(),
                    'next' => $categories->nextPageUrl(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     description="Create a new category with auto-generated slug",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/Category")
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
                'name' => 'required|string|max:255|unique:categories,name',
                'slug' => 'nullable|string|max:255|unique:categories,slug',
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
                $data['slug'] = Str::slug($data['name']);
                
                // Ensure unique slug
                $originalSlug = $data['slug'];
                $counter = 1;
                while (Category::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $category = Category::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'posts_count' => 0,
                    'created_at' => $category->created_at->toISOString(),
                    'updated_at' => $category->updated_at->toISOString(),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{slug}",
     *     tags={"Categories"},
     *     summary="Get a specific category",
     *     description="Retrieve a single category by slug with optional posts",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Category slug",
     *         @OA\Schema(type="string", example="technology")
     *     ),
     *     @OA\Parameter(
     *         name="with_posts",
     *         in="query",
     *         description="Include posts relationship",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/CategoryDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        try {
            $query = Category::withCount('posts')->where('slug', $slug);

            if ($request->has('with_posts') && $request->with_posts) {
                $query->with(['posts' => function($q) {
                    $q->where('status', 'published')
                      ->with(['user:id,name,email'])
                      ->select(['id', 'title', 'slug', 'short_description', 'image', 'status', 'published_at', 'user_id'])
                      ->latest('published_at');
                }]);
            }

            $category = $query->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $response = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'posts_count' => $category->posts_count,
                'created_at' => $category->created_at->toISOString(),
                'updated_at' => $category->updated_at->toISOString(),
            ];

            if ($request->has('with_posts') && $request->with_posts) {
                $response['posts'] = $category->posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'short_description' => $post->short_description,
                        'image_url' => $post->image_url,
                        'status' => $post->status,
                        'published_at' => $post->published_at?->toISOString(),
                        'author' => [
                            'id' => $post->user->id,
                            'name' => $post->user->name,
                            'email' => $post->user->email,
                        ],
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     description="Update an existing category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/Category")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
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
            $category = Category::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
                'slug' => 'sometimes|nullable|string|max:255|unique:categories,slug,' . $category->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Generate slug if name is updated but slug is not provided
            if (isset($data['name']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
                
                // Ensure unique slug
                $originalSlug = $data['slug'];
                $counter = 1;
                while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            $category->update($data);
            $category = $category->fresh(['posts']);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'posts_count' => $category->posts->count(),
                    'created_at' => $category->created_at->toISOString(),
                    'updated_at' => $category->updated_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     description="Delete a category by ID and detach from all associated posts",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
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
            $category = Category::findOrFail($id);

            // Detach all posts before deleting
            $category->posts()->detach();
            
            // Delete category
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
} 