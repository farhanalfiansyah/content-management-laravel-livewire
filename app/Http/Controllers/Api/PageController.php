<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Page API Controller
 * 
 * Handles CRUD operations for pages with validation
 */
class PageController extends Controller
{
    /**
     * Get all pages
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
     * Create a new page
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
     * Get a specific page
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
     * Update a page
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
     * Delete a page
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