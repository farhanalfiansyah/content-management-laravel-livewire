<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class CreateEdit extends Component
{
    use WithFileUploads;

    public ?Post $post = null;
    public $title = '';
    public $slug = '';
    public $content = '';
    public $short_description = '';
    public $image;
    public $existing_image = '';
    public $status = 'draft';
    public $published_at = '';
    public $remove_image = false;
    public $selectedCategories = [];

    public function mount(?Post $post = null)
    {
        if ($post && $post->exists) {
            $this->post = $post;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->content = $post->content;
            $this->short_description = $post->short_description ?? '';
            $this->existing_image = $post->image;
            $this->status = $post->status;
            $this->published_at = $post->published_at?->format('Y-m-d\TH:i') ?? '';
            $this->selectedCategories = $post->categories->pluck('id')->toArray();
        }
    }

    public function updatedTitle()
    {
        if (!$this->post || !$this->post->exists) {
            $this->slug = $this->title ? Str::slug($this->title) : '';
        }
    }

    public function updatedStatus()
    {
        if ($this->status === 'published' && !$this->published_at) {
            $this->published_at = now()->format('Y-m-d\TH:i');
        } elseif ($this->status === 'draft') {
            $this->published_at = '';
        }
    }

    public function removeExistingImage()
    {
        $this->remove_image = true;
        $this->existing_image = '';
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'slug' => 'required|string|max:255|unique:posts,slug' . ($this->post ? ',' . $this->post->id : ''),
            'content' => 'required|string|min:10',
            'short_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'selectedCategories' => 'nullable|array',
            'selectedCategories.*' => 'exists:categories,id',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'short_description' => 'short description',
            'published_at' => 'published date',
            'selectedCategories' => 'categories',
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'The post title is required.',
            'title.min' => 'The post title must be at least 3 characters.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'content.required' => 'The post content is required.',
            'content.min' => 'The post content must be at least 10 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }

    public function save($asDraft = false)
    {
        // Basic validation
        if (empty($this->title)) {
            session()->flash('error', 'Title is required');
            return;
        }

        if (empty($this->content)) {
            session()->flash('error', 'Content is required');
            return;
        }

        // Set status
        if ($asDraft) {
            $this->status = 'draft';
            $this->published_at = '';
        }

        // Generate slug if empty
        if (empty($this->slug)) {
            $this->slug = \Str::slug($this->title);
        }

        // Check for duplicate slug
        $existingPost = Post::where('slug', $this->slug);
        if ($this->post) {
            $existingPost->where('id', '!=', $this->post->id);
        }
        
        if ($existingPost->exists()) {
            $this->slug = $this->slug . '-' . time();
        }

        // Prepare data
        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'short_description' => $this->short_description ?: null,
            'status' => $this->status,
            'published_at' => $this->published_at ?: null,
        ];

        // Handle image upload
        if ($this->image) {
            try {
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/posts');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Delete old image if updating
                if ($this->existing_image) {
                    $oldImagePath = public_path('images/posts/' . $this->existing_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store new image
                $imageName = time() . '_' . \Str::random(10) . '.' . $this->image->getClientOriginalExtension();
                $destinationPath = $uploadPath . '/' . $imageName;
                
                // Save the uploaded file
                file_put_contents($destinationPath, file_get_contents($this->image->getRealPath()));
                $data['image'] = $imageName;

            } catch (\Exception $e) {
                session()->flash('error', 'Image upload failed: ' . $e->getMessage());
                return;
            }
        } elseif ($this->remove_image && $this->existing_image) {
            // Remove existing image
            $oldImagePath = public_path('images/posts/' . $this->existing_image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $data['image'] = null;
        }

        try {
            if ($this->post) {
                // Update existing post
                $this->post->update($data);
                $message = $asDraft ? 'Post saved as draft successfully.' : 'Post updated successfully.';
            } else {
                // Create new post
                $data['user_id'] = auth()->id();
                $this->post = Post::create($data);
                $message = $asDraft ? 'Post created as draft successfully.' : 'Post created successfully.';
            }

            // Sync categories
            if ($this->selectedCategories) {
                $this->post->categories()->sync($this->selectedCategories);
            } else {
                $this->post->categories()->detach();
            }

            session()->flash('success', $message);
            return redirect()->route('posts.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Database error: ' . $e->getMessage());
            return;
        }
    }

    public function saveAsDraft()
    {
        return $this->save(true);
    }

    private function preparePostData()
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'short_description' => $this->short_description,
            'status' => $this->status,
            'published_at' => $this->published_at ? $this->published_at : null,
        ];
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        
        return view('livewire.posts.create-edit', [
            'categories' => $categories,
        ]);
    }
} 