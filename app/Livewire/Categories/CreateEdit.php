<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateEdit extends Component
{
    public Category $category;
    public $name = '';
    public $slug = '';
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
    ];

    public function mount($category = null)
    {
        if ($category) {
            $this->category = $category;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->isEditing = true;
            
            // Update validation rules for editing
            $this->rules['slug'] = 'required|string|max:255|unique:categories,slug,' . $category->id;
        } else {
            $this->category = new Category();
        }
    }

    public function updatedName()
    {
        if (!$this->isEditing || empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                $this->category->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                ]);
                
                session()->flash('message', 'Category updated successfully.');
            } else {
                Category::create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                ]);
                
                session()->flash('message', 'Category created successfully.');
                
                // Reset form for new entry
                $this->reset(['name', 'slug']);
                $this->category = new Category();
            }

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the category.');
        }
    }

    public function render()
    {
        return view('livewire.categories.create-edit');
    }
} 