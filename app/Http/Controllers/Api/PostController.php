<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="Post",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Getting Started with Laravel"),
 *     @OA\Property(property="slug", type="string", example="getting-started-with-laravel"),
 *     @OA\Property(property="short_description", type="string", example="Learn the basics of Laravel framework"),
 *     @OA\Property(property="image_url", type="string", example="http://domain.com/images/posts/image.jpg"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:25:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00.000000Z"),
 *     @OA\Property(
 *         property="author",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john@example.com")
 *     ),
 *     @OA\Property(
 *         property="categories",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="slug", type="string", example="technology")
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PostDetailed",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Post"),
 *         @OA\Schema(
 *             @OA\Property(property="content", type="string", example="<p>Full post content with HTML...</p>")
 *         )
 *     }
 * )
 * 
 * @OA\Schema(
 *     schema="PostCreateRequest",
 *     required={"title", "content", "status"},
 *     @OA\Property(property="title", type="string", maxLength=255, example="My New Post"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="my-new-post"),
 *     @OA\Property(property="short_description", type="string", maxLength=500, example="Brief description of the post"),
 *     @OA\Property(property="content", type="string", example="<p>Full post content with HTML...</p>"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
 *     @OA\Property(property="published_at", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(
 *         property="category_ids",
 *         type="array",
 *         @OA\Items(type="integer"),
 *         example={1, 2, 3}
 *     ),
 *     @OA\Property(property="image", type="string", format="binary", description="Image file upload")
 * )
 * 
 * @OA\Schema(
 *     schema="PostUpdateRequest",
 *     @OA\Property(property="title", type="string", maxLength=255, example="Updated Post Title"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="updated-post-title"),
 *     @OA\Property(property="short_description", type="string", maxLength=500, example="Updated description"),
 *     @OA\Property(property="content", type="string", example="<p>Updated post content...</p>"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="published"),
 *     @OA\Property(property="published_at", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(
 *         property="category_ids",
 *         type="array",
 *         @OA\Items(type="integer"),
 *         example={1, 2}
 *     ),
 *     @OA\Property(property="image", type="string", format="binary", description="Image file upload")
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/posts",
     *     tags={"Posts"},
     *     summary="Get all posts",
     *     description="Retrieve a paginated list of posts with filtering and search capabilities",
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/status"),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Filter by author ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="include_drafts",
     *         in="query",
     *         description="Include draft posts",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/sort_order"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Post")
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
            $query = Post::with(['user:id,name,email', 'categories:id,name,slug'])
                ->select(['id', 'title', 'slug', 'short_description', 'image', 'status', 'published_at', 'created_at', 'updated_at', 'user_id']);

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->has('category') && !empty($request->category)) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('categories.id', $request->category);
                });
            }

            // Filter by author
            if ($request->has('author') && !empty($request->author)) {
                $query->where('user_id', $request->author);
            }

            // Only published posts for public API (unless specifically requesting all)
            if (!$request->has('include_drafts') || !$request->include_drafts) {
                $query->where('status', 'published');
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'published_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if (in_array($sortBy, ['title', 'created_at', 'updated_at', 'published_at'])) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
            $posts = $query->paginate($perPage);

            // Transform the response
            $posts->getCollection()->transform(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'short_description' => $post->short_description,
                    'image_url' => $post->image_url,
                    'status' => $post->status,
                    'published_at' => $post->published_at?->toISOString(),
                    'created_at' => $post->created_at->toISOString(),
                    'updated_at' => $post->updated_at->toISOString(),
                    'author' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                    ],
                    'categories' => $post->categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'from' => $posts->firstItem(),
                    'to' => $posts->lastItem(),
                ],
                'links' => [
                    'first' => $posts->url(1),
                    'last' => $posts->url($posts->lastPage()),
                    'prev' => $posts->previousPageUrl(),
                    'next' => $posts->nextPageUrl(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch posts',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     description="Create a new blog post with categories and optional image upload",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/PostCreateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PostDetailed")
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
                'slug' => 'nullable|string|max:255|unique:posts,slug',
                'short_description' => 'nullable|string|max:500',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'status' => 'required|in:draft,published',
                'published_at' => 'nullable|date',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
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
                while (Post::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/posts');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = $imageName;
            }

            // Set published_at if status is published and not already set
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            // Set user_id to authenticated user (you'll need authentication middleware)
            $data['user_id'] = auth()->id() ?? 1; // Fallback for testing

            $post = Post::create($data);

            // Attach categories if provided
            if (!empty($data['category_ids'])) {
                $post->categories()->attach($data['category_ids']);
            }

            // Load relationships for response
            $post->load(['user:id,name,email', 'categories:id,name,slug']);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $this->formatPostResponse($post)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/posts/{slug}",
     *     tags={"Posts"},
     *     summary="Get a specific post",
     *     description="Retrieve a single post by its slug, including full content",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Post slug",
     *         @OA\Schema(type="string", example="getting-started-with-laravel")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PostDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
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
            $post = Post::with(['user:id,name,email', 'categories:id,name,slug'])
                ->where('slug', $slug)
                ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatPostResponse($post, true) // Include full content
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     description="Update an existing post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/PostUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(property="data", ref="#/components/schemas/PostDetailed")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
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
            $post = Post::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'slug' => 'sometimes|nullable|string|max:255|unique:posts,slug,' . $post->id,
                'short_description' => 'nullable|string|max:500',
                'content' => 'sometimes|required|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'status' => 'sometimes|required|in:draft,published',
                'published_at' => 'nullable|date',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'exists:categories,id',
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
                while (Post::where('slug', $data['slug'])->where('id', '!=', $post->id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($post->image) {
                    $oldImagePath = public_path('images/posts/' . $post->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('images/posts');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $data['image'] = $imageName;
            }

            // Set published_at if status is changed to published and not already set
            if (isset($data['status']) && $data['status'] === 'published' && !$post->published_at && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            $post->update($data);

            // Sync categories if provided
            if (isset($data['category_ids'])) {
                $post->categories()->sync($data['category_ids']);
            }

            // Load relationships for response
            $post->load(['user:id,name,email', 'categories:id,name,slug']);

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => $this->formatPostResponse($post, true)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/posts/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     description="Delete a post by ID, including associated image and category relationships",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
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
            $post = Post::findOrFail($id);

            // Delete image if exists
            if ($post->image) {
                $imagePath = public_path('images/posts/' . $post->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Detach categories
            $post->categories()->detach();
            
            // Delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{slug}/posts",
     *     tags={"Posts"},
     *     summary="Get posts by category",
     *     description="Retrieve all posts belonging to a specific category",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Category slug",
     *         @OA\Schema(type="string", example="technology")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Posts retrieved successfully",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/PaginatedResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(ref="#/components/schemas/Post")
     *                     ),
     *                     @OA\Property(
     *                         property="category",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Technology"),
     *                         @OA\Property(property="slug", type="string", example="technology")
     *                     )
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
    public function byCategory(string $categorySlug): JsonResponse
    {
        try {
            $category = Category::where('slug', $categorySlug)->first();
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $posts = Post::with(['user:id,name,email', 'categories:id,name,slug'])
                ->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->where('status', 'published')
                ->latest('published_at')
                ->paginate(15);

            $posts->getCollection()->transform(function ($post) {
                return $this->formatPostResponse($post);
            });

            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch posts by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Format post response
     */
    private function formatPostResponse($post, $includeContent = false): array
    {
        $response = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'short_description' => $post->short_description,
            'image_url' => $post->image_url,
            'status' => $post->status,
            'published_at' => $post->published_at?->toISOString(),
            'created_at' => $post->created_at->toISOString(),
            'updated_at' => $post->updated_at->toISOString(),
            'author' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
                'email' => $post->user->email,
            ],
            'categories' => $post->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];
            }),
        ];

        if ($includeContent) {
            $response['content'] = $post->content;
        }

        return $response;
    }
}
