<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isEditing ? 'Edit Category' : 'Create Category' }}
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $isEditing ? 'Update the category information.' : 'Create a new category for organizing your content.' }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Categories
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form wire:submit="save" class="space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Category Name
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input wire:model.live="name" 
                               type="text" 
                               id="name"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror" 
                               placeholder="Enter category name">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Enter a descriptive name for your category.</p>
                </div>

                <!-- Slug Field -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">
                        Slug
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input wire:model.defer="slug" 
                               type="text" 
                               id="slug"
                               class="flex-1 focus:ring-orange-500 focus:border-orange-500 block w-full min-w-0 rounded-none rounded-l-md sm:text-sm border-gray-300 @error('slug') border-red-300 @enderror" 
                               placeholder="category-slug">
                        <button type="button" 
                                wire:click="generateSlug"
                                class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 text-sm hover:bg-gray-100 focus:ring-orange-500 focus:border-orange-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Generate
                        </button>
                    </div>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        URL-friendly version of the name. Auto-generated from the name, but you can customize it.
                    </p>
                </div>

                <!-- Live Preview -->
                @if($name || $slug)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $name ?: 'Category Name' }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        Slug: {{ $slug ?: 'category-slug' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                        <svg wire:loading.remove class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <svg wire:loading class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>
                            {{ $isEditing ? 'Update Category' : 'Create Category' }}
                        </span>
                        <span wire:loading>
                            {{ $isEditing ? 'Updating...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 