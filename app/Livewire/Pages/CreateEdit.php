<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateEdit extends Component
{
    public Page $page;

    public $title = '';
    public $slug = '';
    public $body = '';
    public $status = 'draft';

    public function mount(?Page $page = null)
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->body = $page->body;
            $this->status = $page->status;
        } else {
            $this->page = new Page();
        }
    }

    public function updatedTitle()
    {
        if (!$this->page->exists) {
            $this->slug = $this->title ? Str::slug($this->title) : '';
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'slug' => 'required|string|max:255|unique:pages,slug' . ($this->page->exists ? ',' . $this->page->id : ''),
            'body' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'body' => 'page body',
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'The page title is required.',
            'title.min' => 'The page title must be at least 3 characters.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'body.required' => 'The page body is required.',
            'body.min' => 'The page body must be at least 10 characters.',
        ];
    }

    public function save($asDraft = false)
    {
        // Basic validation
        if (empty($this->title)) {
            session()->flash('error', 'Title is required');
            return;
        }

        if (empty($this->body)) {
            session()->flash('error', 'Page body is required');
            return;
        }

        // Set status
        if ($asDraft) {
            $this->status = 'draft';
        }

        // Generate slug if empty
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }

        // Check for duplicate slug
        $existingPage = Page::where('slug', $this->slug);
        if ($this->page->exists) {
            $existingPage->where('id', '!=', $this->page->id);
        }
        
        if ($existingPage->exists()) {
            $this->slug = $this->slug . '-' . time();
        }

        // Prepare data
        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'status' => $this->status,
        ];

        try {
            if ($this->page->exists) {
                // Update existing page
                $this->page->update($data);
                $message = $asDraft ? 'Page saved as draft successfully.' : 'Page updated successfully.';
            } else {
                // Create new page
                $data['user_id'] = auth()->id();
                $this->page = Page::create($data);
                $message = $asDraft ? 'Page created as draft successfully.' : 'Page created successfully.';
            }

            session()->flash('success', $message);
            return redirect()->route('pages.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Database error: ' . $e->getMessage());
            return;
        }
    }

    public function saveAsDraft()
    {
        return $this->save(true);
    }

    public function render()
    {
        return view('livewire.pages.create-edit');
    }
} 