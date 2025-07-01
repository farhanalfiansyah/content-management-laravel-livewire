<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $page->exists ? 'Edit Page' : 'Create New Page' }}
                </h1>
                <p class="mt-2 text-gray-600">
                    {{ $page->exists ? 'Update your page content and settings' : 'Create a new page for your website' }}
                </p>
            </div>
            <a href="{{ route('pages.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Pages
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

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
                <h2 class="text-lg font-medium text-gray-900">Page Content</h2>
                <p class="text-sm text-gray-500">Fill in the page information below</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input wire:model.live="title" type="text" id="title" 
                           class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-300 @enderror"
                           placeholder="Enter page title">
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
                           placeholder="page-slug">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">This will be automatically generated from the title</p>
                </div>

                <!-- Body Content -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Page Body <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <textarea wire:model.defer="body" id="body" rows="12"
                                  class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 @error('body') border-red-300 @enderror"
                                  placeholder="Write your page content here...">{{ $body }}</textarea>
                    </div>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Use the rich text editor to format your content with bold, italic, lists, and more</p>
                </div>
            </div>
        </div>

        <!-- Page Settings -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Status Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Page Settings</h2>
                </div>
                <div class="p-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="status" id="status" 
                                class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            @if($title)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Preview</h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="bg-white rounded-lg p-3 border">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 truncate">{{ $title }}</h4>
                                        <p class="text-xs text-gray-400">{{ $slug ?: 'page-slug' }}</p>
                                    </div>
                                </div>
                                @if($body)
                                    <div class="text-sm text-gray-600 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                        {{ strip_tags($body) }}
                                    </div>
                                @endif
                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                    <span>Status: 
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                     @if($status === 'published') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </span>
                                    <span>{{ now()->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <a href="{{ route('pages.index') }}" 
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
                        {{ $page->exists ? 'Update Page' : 'Create Page' }}
                    </span>
                    <span wire:loading wire:target="save">
                        {{ $page->exists ? 'Updating...' : 'Creating...' }}
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
    const textarea = document.getElementById('body');
    textarea.style.display = 'none';
    
    // Create Quill editor container
    const editorContainer = document.createElement('div');
    editorContainer.id = 'quill-editor-body';
    editorContainer.style.height = '400px';
    editorContainer.style.backgroundColor = 'white';
    editorContainer.style.border = '1px solid #d1d5db';
    editorContainer.style.borderRadius = '0.5rem';
    textarea.parentNode.insertBefore(editorContainer, textarea);
    
    // Initialize Quill
    const quill = new Quill('#quill-editor-body', {
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
        placeholder: 'Write your page content here...',
        formats: [
            'header', 'bold', 'italic', 'underline', 'strike',
            'color', 'background', 'list', 'bullet', 'align',
            'blockquote', 'code-block', 'link'
        ]
    });
    
    // Store quill instance globally for access
    window.quillBody = quill;
    
    // Set initial content - properly handle HTML content
    const initialContent = @json($body);
    if (initialContent && initialContent.trim() !== '') {
        quill.root.innerHTML = initialContent;
    }
    
    // Function to update Livewire with current content
    function updateLivewireContent() {
        const content = quill.root.innerHTML;
        // Only update if there's actual content (not just empty paragraphs)
        const textContent = quill.getText().trim();
        
        if (textContent.length > 0) {
            @this.set('body', content);
            textarea.value = content;
        } else {
            @this.set('body', '');
            textarea.value = '';
        }
    }
    
    // Update Livewire when content changes
    quill.on('text-change', function(delta, oldDelta, source) {
        // Debounce the update to avoid too many calls
        clearTimeout(window.quillBodyTimeout);
        window.quillBodyTimeout = setTimeout(updateLivewireContent, 300);
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
        if (window.quillBody) {
            updateLivewireContent();
        }
    });
});

// Handle Livewire updates
document.addEventListener('livewire:navigated', function () {
    // Reinitialize if needed after navigation
    if (!window.quillBody) {
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