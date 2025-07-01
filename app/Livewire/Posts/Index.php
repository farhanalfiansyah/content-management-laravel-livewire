<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'status')]
    public $statusFilter = '';

    #[Url(as: 'category')]
    public $categoryFilter = '';

    public $selectedPosts = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPosts = $this->posts->pluck('id')->toArray();
        } else {
            $this->selectedPosts = [];
        }
    }

    public function deletePost($postId)
    {
        try {
            $post = Post::find($postId);
            
            if (!$post) {
                session()->flash('error', 'Post not found.');
                return;
            }
            
            // Check if user owns the post or is admin
            if ($post->user_id !== auth()->id()) {
                session()->flash('error', 'You can only delete your own posts.');
                return;
            }
            
            if ($post->image) {
                $imagePath = public_path('images/posts/' . $post->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Detach categories before deleting
            $post->categories()->detach();
            $post->delete();
            $this->selectedPosts = array_diff($this->selectedPosts, [$postId]);
            
            session()->flash('success', 'Post deleted successfully.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the post.');
            \Log::error('Post delete error: ' . $e->getMessage());
        }
    }

    public function toggleStatus($postId)
    {
        try {
            $post = Post::find($postId);
            
            if (!$post) {
                session()->flash('error', 'Post not found.');
                return;
            }
            
            // Check if user owns the post
            if ($post->user_id !== auth()->id()) {
                session()->flash('error', 'You can only modify your own posts.');
                return;
            }
            
            $newStatus = $post->status === 'published' ? 'draft' : 'published';
            
            $post->update([
                'status' => $newStatus,
                'published_at' => $newStatus === 'published' ? now() : null,
            ]);
            
            session()->flash('success', "Post status changed to {$newStatus}.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the post status.');
            \Log::error('Post status toggle error: ' . $e->getMessage());
        }
    }

    public function bulkDelete()
    {
        try {
            if (empty($this->selectedPosts)) {
                session()->flash('error', 'No posts selected.');
                return;
            }

            $posts = Post::whereIn('id', $this->selectedPosts)
                         ->where('user_id', auth()->id()) // Only delete user's own posts
                         ->get();
            
            if ($posts->isEmpty()) {
                session()->flash('error', 'No posts found or you can only delete your own posts.');
                return;
            }
            
            foreach ($posts as $post) {
                if ($post->image) {
                    $imagePath = public_path('images/posts/' . $post->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                // Detach categories before deleting
                $post->categories()->detach();
            }
            
            $deletedCount = $posts->count();
            Post::whereIn('id', $posts->pluck('id'))->delete();
            
            $this->selectedPosts = [];
            $this->selectAll = false;
            
            session()->flash('success', "{$deletedCount} post(s) deleted successfully.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the selected posts.');
            \Log::error('Bulk delete error: ' . $e->getMessage());
        }
    }

    public function getPostsProperty()
    {
        return Post::with(['user', 'categories'])
            ->when($this->search, fn($query) => 
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%')
                      ->orWhere('short_description', 'like', '%' . $this->search . '%')
            )
            ->when($this->statusFilter, fn($query) => 
                $query->where('status', $this->statusFilter)
            )
            ->when($this->categoryFilter, fn($query) => 
                $query->whereHas('categories', function($q) {
                    $q->where('categories.id', $this->categoryFilter);
                })
            )
            ->latest()
            ->paginate(10);
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Post::count(),
            'published' => Post::published()->count(),
            'draft' => Post::draft()->count(),
            'my_posts' => Post::where('user_id', auth()->id())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.posts.index', [
            'posts' => $this->posts,
        ]);
    }
} 