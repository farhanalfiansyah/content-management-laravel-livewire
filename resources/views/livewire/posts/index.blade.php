<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Posts</h1>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Manage your blog posts and articles</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('posts.create') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105 text-sm sm:text-base w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Post
                </a>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mx-1 sm:mx-0">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-green-700 text-sm sm:text-base">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4 mx-1 sm:mx-0">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-red-700 text-sm sm:text-base">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 lg:gap-6 mb-4 sm:mb-6 px-1 sm:px-0">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-2 sm:ml-3 lg:ml-4 min-w-0 flex-1">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 truncate">{{ $this->stats['total'] }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Total Posts</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-2 sm:ml-3 lg:ml-4 min-w-0 flex-1">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 truncate">{{ $this->stats['published'] }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Published</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-2 sm:ml-3 lg:ml-4 min-w-0 flex-1">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 truncate">{{ $this->stats['draft'] }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">Drafts</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-2 sm:ml-3 lg:ml-4 min-w-0 flex-1">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 truncate">{{ $this->stats['my_posts'] }}</h3>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">My Posts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 sm:mb-6 mx-1 sm:mx-0">
        <div class="p-3 sm:p-4 lg:p-6">
            <div class="flex flex-col space-y-3 sm:space-y-4 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4">
                <!-- Search -->
                <div class="w-full lg:flex-1 lg:max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="block w-full pl-9 sm:pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 text-sm sm:text-base" 
                               placeholder="Search posts...">
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 lg:space-x-4">
                    <!-- Status Filter -->
                    <div class="relative min-w-0 flex-1 sm:flex-none">
                        <select wire:model.live="statusFilter" 
                                class="bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 pr-8 focus:ring-orange-500 focus:border-orange-500 focus:outline-none text-xs sm:text-sm font-medium text-gray-700 hover:border-gray-400 transition-colors duration-150 w-full cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none; background-repeat: no-repeat;">
                            <option value="">All Status</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:pr-3 pointer-events-none">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="relative min-w-0 flex-1 sm:flex-none">
                        <select wire:model.live="categoryFilter" 
                                class="bg-white border border-gray-300 rounded-lg px-3 sm:px-4 py-2 pr-8 focus:ring-orange-500 focus:border-orange-500 focus:outline-none text-xs sm:text-sm font-medium text-gray-700 hover:border-gray-400 transition-colors duration-150 w-full cursor-pointer"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none; background-repeat: no-repeat;">
                            <option value="">All Categories</option>
                            @foreach($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:pr-3 pointer-events-none">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>

                    @if(count($selectedPosts) > 0)
                        <button wire:click="bulkDelete" 
                                wire:confirm="Are you sure you want to delete selected posts?"
                                class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg transition duration-150 min-w-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="hidden sm:inline truncate">Delete Selected ({{ count($selectedPosts) }})</span>
                            <span class="sm:hidden truncate">Delete ({{ count($selectedPosts) }})</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Cards View (Hidden on larger screens) -->
    <div class="block lg:hidden space-y-4 px-1 sm:px-0">
        @forelse($posts as $post)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 overflow-hidden">
                <!-- Header with checkbox and status -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" wire:model.live="selectedPosts" value="{{ $post->id }}" 
                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-500 h-4 w-4 flex-shrink-0">
                        <span class="text-xs text-gray-500">#{{ $post->id }}</span>
                    </div>
                    <button wire:click="toggleStatus({{ $post->id }})" 
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all duration-150 hover:shadow-md flex-shrink-0
                                   @if($post->status === 'published') 
                                       bg-green-100 text-green-800 hover:bg-green-200
                                   @else 
                                       bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                   @endif">
                        <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        {{ ucfirst($post->status) }}
                    </button>
                </div>

                <!-- Post content -->
                <div class="flex space-x-3">
                    @if($post->image)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" 
                             class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm sm:text-base font-medium text-gray-900 line-clamp-2 mb-1">{{ $post->title }}</h3>
                        @if($post->short_description)
                            <p class="text-xs sm:text-sm text-gray-500 mt-1 line-clamp-2">{{ $post->short_description }}</p>
                        @endif
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-2 space-y-1 sm:space-y-0">
                            <p class="text-xs text-gray-400 truncate">By {{ $post->user->name }}</p>
                            <p class="text-xs text-gray-400 flex-shrink-0">
                                @if($post->published_at)
                                    {{ $post->published_at->format('M j, Y') }}
                                @else
                                    {{ $post->created_at->format('M j, Y') }}
                                @endif
                            </p>
                        </div>
                        
                        <!-- Categories -->
                        @if($post->categories->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($post->categories->take(2) as $category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                                @if($post->categories->count() > 2)
                                    <span class="text-xs text-gray-500">+{{ $post->categories->count() - 2 }} more</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('posts.edit', $post) }}" 
                       class="text-orange-600 hover:text-orange-900 font-medium text-xs sm:text-sm transition-colors duration-150">
                        Edit
                    </a>
                    <button wire:click="deletePost({{ $post->id }})" 
                            wire:confirm="Are you sure you want to delete this post?"
                            class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm transition-colors duration-150">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No posts found</h3>
                <p class="text-gray-500 mb-4 text-sm sm:text-base">Get started by creating your first post.</p>
                <a href="{{ route('posts.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Post
                </a>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (Hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll" 
                                   class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selectedPosts" value="{{ $post->id }}" 
                                       class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-4">
                                    @if($post->image)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" 
                                             class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</h3>
                                        @if($post->short_description)
                                            <p class="text-sm text-gray-500 mt-1 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $post->short_description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $post->slug }}</p>
                                        
                                        <!-- Categories -->
                                        @if($post->categories->count() > 0)
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach($post->categories as $category)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 713 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $post->id }})" 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-150 hover:shadow-sm
                                               @if($post->status === 'published') 
                                                   bg-green-100 text-green-800 hover:bg-green-200
                                               @else 
                                                   bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                               @endif">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    {{ ucfirst($post->status) }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $post->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($post->published_at)
                                    {{ $post->published_at->format('M j, Y') }}
                                @else
                                    {{ $post->created_at->format('M j, Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('posts.edit', $post) }}" 
                                       class="text-orange-600 hover:text-orange-900 font-medium text-sm transition-colors duration-150">
                                        Edit
                                    </a>
                                    <button wire:click="deletePost({{ $post->id }})" 
                                            wire:confirm="Are you sure you want to delete this post?"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm transition-colors duration-150">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No posts found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first post.</p>
                                    <a href="{{ route('posts.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Pagination -->
    @if($posts->hasPages())
        <div class="block lg:hidden mt-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
                {{ $posts->links() }}
            </div>
        </div>
    @endif
</div> 