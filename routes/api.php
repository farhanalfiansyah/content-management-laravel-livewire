<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PageController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Simple test endpoint
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'version' => 'v1.0',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment()
    ]);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
    ]);
});

// API Version 1 with rate limiting (60 requests per minute)
Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    
    // Posts API endpoints
    Route::apiResource('posts', PostController::class)->except(['show']);
    
    // Custom routes for posts
    Route::get('posts/{slug}', [PostController::class, 'show'])->name('api.posts.show');
    Route::get('categories/{slug}/posts', [PostController::class, 'byCategory'])->name('api.posts.by-category');
    
    // Categories API endpoints
    Route::apiResource('categories', CategoryController::class)->except(['show']);
    Route::get('categories/{slug}', [CategoryController::class, 'show'])->name('api.categories.show');
    
    // Pages API endpoints
    Route::apiResource('pages', PageController::class)->except(['show']);
    Route::get('pages/{slug}', [PageController::class, 'show'])->name('api.pages.show');
    
}); 