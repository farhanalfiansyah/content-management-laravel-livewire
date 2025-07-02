<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Category API Controller
 * 
 * Handles CRUD operations for categories with validation and relationships
 */
class CategoryController extends Controller
{
    /**
     * Get all categories
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
     * Create a new category
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
     * Get a specific category
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        try {
            $category = Category::where('slug', $slug)->withCount('posts')->first();

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

            // Include posts if requested
            if ($request->has('with_posts') && $request->with_posts) {
                $category->load(['posts' => function($q) {
                    $q->where('status', 'published')
                      ->with(['user:id,name,email'])
                      ->select(['id', 'title', 'slug', 'short_description', 'image', 'status', 'published_at', 'user_id'])
                      ->latest('published_at');
                }]);

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
     * Update a category
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

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'posts_count' => $category->posts_count,
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
     * Delete a category
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has posts
            if ($category->posts()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category that has posts. Please move or delete the posts first.'
                ], 422);
            }

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