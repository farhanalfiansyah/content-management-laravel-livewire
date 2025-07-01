<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedPages = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPages = $this->pages->pluck('id')->toArray();
        } else {
            $this->selectedPages = [];
        }
    }

    public function updatedSelectedPages()
    {
        $this->selectAll = count($this->selectedPages) === $this->pages->count();
    }

    public function toggleStatus($pageId)
    {
        $page = Page::find($pageId);
        
        if ($page && $page->user_id === auth()->id()) {
            $page->update([
                'status' => $page->status === 'published' ? 'draft' : 'published'
            ]);
            
            session()->flash('success', 'Page status updated successfully.');
        }
    }

    public function deletePage($pageId)
    {
        $page = Page::find($pageId);
        
        if ($page && $page->user_id === auth()->id()) {
            $page->delete();
            session()->flash('success', 'Page deleted successfully.');
        }
    }

    public function bulkDelete()
    {
        if (empty($this->selectedPages)) {
            return;
        }

        $pages = Page::whereIn('id', $this->selectedPages)
            ->where('user_id', auth()->id())
            ->get();

        foreach ($pages as $page) {
            $page->delete();
        }

        $this->selectedPages = [];
        $this->selectAll = false;
        
        session()->flash('success', count($pages) . ' page(s) deleted successfully.');
    }

    public function getPagesProperty()
    {
        return Page::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('body', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);
    }

    public function getStatsProperty()
    {
        $baseQuery = Page::query();
        
        return [
            'total' => $baseQuery->count(),
            'published' => $baseQuery->where('status', 'published')->count(),
            'draft' => $baseQuery->where('status', 'draft')->count(),
            'my_pages' => $baseQuery->where('user_id', auth()->id())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.pages.index', [
            'pages' => $this->pages,
            'stats' => $this->stats
        ]);
    }
} 