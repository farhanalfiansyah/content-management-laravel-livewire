<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for dashboard cards
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        
        $totalPages = Page::count();
        $publishedPages = Page::where('status', 'published')->count();
        $draftPages = Page::where('status', 'draft')->count();
        
        $totalCategories = Category::count();
        
        // Recent posts
        $recentPosts = Post::with(['user', 'categories'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent pages
        $recentPages = Page::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Most used categories
        $popularCategories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly stats (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M'),
                'posts' => Post::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'pages' => Page::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        return view('dashboard', compact(
            'totalPosts',
            'publishedPosts', 
            'draftPosts',
            'totalPages',
            'publishedPages',
            'draftPages',
            'totalCategories',
            'recentPosts',
            'recentPages',
            'popularCategories',
            'monthlyStats'
        ));
    }
} 