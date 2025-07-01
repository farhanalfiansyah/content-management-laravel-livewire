<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Posts
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">
            {{ $post ? 'Edit Post' : 'Create New Post' }}
        </h1>
        <p class="mt-2 text-gray-600">
            {{ $post ? 'Update your post details below.' : 'Fill in the details to create a new post.' }}
        </p>
    </div>

    <!-- Error Messages -->
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Post Content</h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input wire:model.live="title" type="text" id="title" 
                           class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-300 @enderror"
                           placeholder="Enter post title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="slug" type="text" id="slug" 
                           class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('slug') border-red-300 @enderror"
                           placeholder="post-slug">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">This will be automatically generated from the title</p>
                </div>

                <!-- Short Description -->
                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Short Description
                    </label>
                    <textarea wire:model="short_description" id="short_description" rows="3"
                              class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('short_description') border-red-300 @enderror"
                              placeholder="A brief description of your post..."></textarea>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Maximum 500 characters</p>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <textarea wire:model.defer="content" id="content" rows="12"
                                  class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('content') border-red-300 @enderror"
                                  placeholder="Write your post content here...">{{ $content }}</textarea>
                    </div>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Use the rich text editor to format your content with bold, italic, lists, and more</p>
                </div>
            </div>
        </div>

        <!-- Media & Settings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Featured Image -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Featured Image</h2>
                </div>
                <div class="p-6">
                    <!-- Current Image -->
                    @if($existing_image && !$remove_image)
                        <div class="mb-4">
                            <img src="{{ asset('images/posts/' . $existing_image) }}" alt="Current image" 
                                 class="w-full h-48 object-cover rounded-lg">
                            <button type="button" wire:click="removeExistingImage"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors duration-150">
                                Remove current image
                            </button>
                        </div>
                    @endif

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $existing_image && !$remove_image ? 'Replace Image' : 'Upload Image' }}
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors duration-150">
                            <input wire:model="image" type="file" id="image" accept="image/*" class="sr-only">
                            <label for="image" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="mt-2">
                                    <span class="text-sm font-medium text-gray-900">Click to upload</span>
                                    <span class="text-sm text-gray-500">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    @if($image)
                        <div class="mt-4">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif

                    <div wire:loading wire:target="image" class="mt-2">
                        <p class="text-sm text-gray-500">Uploading...</p>
                    </div>
                </div>
            </div>

            <!-- Post Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Post Settings</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="status" id="status" 
                                class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Date -->
                    @if($status === 'published')
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Published Date
                            </label>
                            <input wire:model="published_at" type="datetime-local" id="published_at" 
                                   class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('published_at') border-red-300 @enderror">
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Leave empty to use current date/time</p>
                        </div>
                    @endif

                    <!-- Categories -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <label class="block text-sm font-medium text-orange-800">
                                    Categories
                                </label>
                            </div>
                            @if(count($selectedCategories) > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ count($selectedCategories) }} selected
                                </span>
                            @endif
                        </div>

                        @if($categories->count() > 0)
                            <!-- Category Search (if many categories) -->
                            @if($categories->count() > 5)
                                <div class="mb-3">
                                    <div class="relative">
                                        <input type="text" 
                                               placeholder="Search categories..."
                                               class="block w-full pl-10 pr-3 py-2 border border-orange-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 text-sm bg-white">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Categories Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-64 overflow-y-auto bg-white rounded-lg border border-orange-200 p-3">
                                @foreach($categories as $category)
                                    <label class="flex items-center p-2 rounded-lg hover:bg-orange-50 transition-colors duration-150 cursor-pointer group">
                                        <input type="checkbox" 
                                               wire:model="selectedCategories" 
                                               value="{{ $category->id }}"
                                               class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <span class="text-sm font-medium text-gray-900 group-hover:text-orange-700">{{ $category->name }}</span>
                                            <p class="text-xs text-gray-500 truncate">{{ $category->slug }}</p>
                                        </div>
                                        <div class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Selected Categories Preview -->
                            @if(count($selectedCategories) > 0)
                                <div class="mt-3 p-3 bg-white rounded-lg border border-orange-200">
                                    <p class="text-xs font-medium text-orange-800 mb-2">Selected Categories:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($categories->whereIn('id', $selectedCategories) as $category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="mt-3 flex items-center justify-between text-xs">
                                <p class="text-orange-700">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Select categories to help organize and filter your content
                                </p>
                                <a href="{{ route('categories.create') }}" 
                                   class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add New Category
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-orange-900 mb-2">No Categories Available</h3>
                                <p class="text-orange-700 mb-4">Create categories to organize your posts effectively</p>
                                <a href="{{ route('categories.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create Your First Category
                                </a>
                            </div>
                        @endif
                        
                        @error('selectedCategories')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Card -->
                    @if($title)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Preview</h3>
                            <div class="bg-white rounded-lg p-3 border">
                                <h4 class="font-medium text-gray-900 truncate">{{ $title }}</h4>
                                @if($short_description)
                                    <p class="text-sm text-gray-600 mt-1 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $short_description }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $slug }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <a href="{{ route('posts.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-150">
                Cancel
            </a>
            
            <div class="flex space-x-3">
                <button type="button" wire:click="saveAsDraft"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                    <svg wire:loading wire:target="saveAsDraft" class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="saveAsDraft">Save as Draft</span>
                    <span wire:loading wire:target="saveAsDraft">Saving...</span>
                </button>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">
                        {{ $post ? 'Update Post' : 'Create Post' }}
                    </span>
                    <span wire:loading wire:target="save">
                        {{ $post ? 'Updating...' : 'Creating...' }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Quill.js JavaScript -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide the original textarea and create Quill container
    const textarea = document.getElementById('content');
    textarea.style.display = 'none';
    
    // Create Quill editor container
    const editorContainer = document.createElement('div');
    editorContainer.id = 'quill-editor';
    editorContainer.style.height = '400px';
    editorContainer.style.backgroundColor = 'white';
    editorContainer.style.border = '1px solid #d1d5db';
    editorContainer.style.borderRadius = '0.5rem';
    textarea.parentNode.insertBefore(editorContainer, textarea);
    
    // Initialize Quill
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link'],
                ['clean']
            ]
        },
        placeholder: 'Write your post content here...',
        formats: [
            'header', 'bold', 'italic', 'underline', 'strike',
            'color', 'background', 'list', 'bullet', 'align',
            'blockquote', 'code-block', 'link'
        ]
    });
    
    // Store quill instance globally for access
    window.quill = quill;
    
    // Set initial content - properly handle HTML content
    const initialContent = @json($content);
    if (initialContent && initialContent.trim() !== '') {
        quill.root.innerHTML = initialContent;
    }
    
    // Function to update Livewire with current content
    function updateLivewireContent() {
        const content = quill.root.innerHTML;
        // Only update if there's actual content (not just empty paragraphs)
        const textContent = quill.getText().trim();
        
        if (textContent.length > 0) {
            @this.set('content', content);
            textarea.value = content;
        } else {
            @this.set('content', '');
            textarea.value = '';
        }
    }
    
    // Update Livewire when content changes
    quill.on('text-change', function(delta, oldDelta, source) {
        // Debounce the update to avoid too many calls
        clearTimeout(window.quillTimeout);
        window.quillTimeout = setTimeout(updateLivewireContent, 300);
    });
    
    // Also update on blur to ensure content is saved
    quill.on('selection-change', function(range, oldRange, source) {
        if (range === null) {
            // Editor lost focus
            updateLivewireContent();
        }
    });
    
    // Custom styling for the editor
    const toolbar = document.querySelector('.ql-toolbar');
    if (toolbar) {
        toolbar.style.border = '1px solid #d1d5db';
        toolbar.style.borderBottom = 'none';
        toolbar.style.borderTopLeftRadius = '0.5rem';
        toolbar.style.borderTopRightRadius = '0.5rem';
        toolbar.style.backgroundColor = '#f9fafb';
    }
    
    const editor = document.querySelector('.ql-container');
    if (editor) {
        editor.style.border = '1px solid #d1d5db';
        editor.style.borderTop = 'none';
        editor.style.borderBottomLeftRadius = '0.5rem';
        editor.style.borderBottomRightRadius = '0.5rem';
        editor.style.fontSize = '14px';
        editor.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    }
    
    // Ensure content is synced before form submission
    document.addEventListener('livewire:before-send', function() {
        if (window.quill) {
            updateLivewireContent();
        }
    });
});

// Handle Livewire updates
document.addEventListener('livewire:navigated', function () {
    // Reinitialize if needed after navigation
    if (!window.quill) {
        setTimeout(() => {
            location.reload();
        }, 100);
    }
});
</script>

<style>
/* Custom Quill styling to match your theme */
.ql-toolbar .ql-stroke {
    fill: none;
    stroke: #6b7280;
}

.ql-toolbar .ql-fill {
    fill: #6b7280;
    stroke: none;
}

.ql-toolbar .ql-picker {
    color: #6b7280;
}

.ql-toolbar .ql-picker-options {
    background-color: white;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}

.ql-toolbar button:hover,
.ql-toolbar button:focus {
    color: #f97316;
}

.ql-toolbar button:hover .ql-stroke {
    stroke: #f97316;
}

.ql-toolbar button:hover .ql-fill {
    fill: #f97316;
}

.ql-toolbar .ql-active {
    color: #f97316;
}

.ql-toolbar .ql-active .ql-stroke {
    stroke: #f97316;
}

.ql-toolbar .ql-active .ql-fill {
    fill: #f97316;
}

.ql-editor.ql-blank::before {
    color: #9ca3af;
    font-style: normal;
}

.ql-editor {
    line-height: 1.6;
}

.ql-editor h1 {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 0.5em;
    color: #1f2937;
}

.ql-editor h2 {
    font-size: 1.5em;
    font-weight: 600;
    margin-bottom: 0.5em;
    color: #374151;
}

.ql-editor h3 {
    font-size: 1.25em;
    font-weight: 500;
    margin-bottom: 0.5em;
    color: #4b5563;
}

.ql-editor blockquote {
    border-left: 4px solid #f97316;
    padding-left: 16px;
    margin: 16px 0;
    font-style: italic;
    color: #6b7280;
}

.ql-editor .ql-code-block-container {
    background-color: #f3f4f6;
    border-radius: 0.375rem;
    padding: 12px;
    margin: 8px 0;
}

.ql-editor code {
    background-color: #f3f4f6;
    color: #f97316;
    padding: 2px 4px;
    border-radius: 0.25rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875em;
}
</style>
@endpush 