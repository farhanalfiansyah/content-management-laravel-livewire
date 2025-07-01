<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectAll = false;

    protected $listeners = ['categoryDeleted' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedCategories = Category::when($this->search, function ($query) {
                $query->search($this->search);
            })->pluck('id')->toArray();
        } else {
            $this->selectedCategories = [];
        }
    }

    public function deleteSelected()
    {
        if (empty($this->selectedCategories)) {
            return;
        }

        $categories = Category::whereIn('id', $this->selectedCategories)->get();
        
        foreach ($categories as $category) {
            // Detach all posts before deleting
            $category->posts()->detach();
            $category->delete();
        }

        $this->selectedCategories = [];
        $this->selectAll = false;
        
        session()->flash('message', count($categories) . ' categories deleted successfully.');
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);
        
        if ($category) {
            // Detach all posts before deleting
            $category->posts()->detach();
            $category->delete();
            
            session()->flash('message', 'Category deleted successfully.');
        }
    }

    public function render()
    {
        $categories = Category::when($this->search, function ($query) {
            $query->search($this->search);
        })
        ->withCount('posts')
        ->latest()
        ->paginate(10);

        $totalCategories = Category::count();
        $totalWithPosts = Category::has('posts')->count();

        return view('livewire.categories.index', [
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'totalWithPosts' => $totalWithPosts,
        ]);
    }
} 