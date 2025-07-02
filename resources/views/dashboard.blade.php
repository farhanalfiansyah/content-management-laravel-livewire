<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Posts Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path>
                                </svg>
                            </div>
                            <span class="text-gray-600 font-medium">Posts</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPosts }}</p>
                        <p class="text-sm text-gray-500">
                            <span class="text-green-600 font-medium">{{ $publishedPosts }} published</span> · 
                            <span class="text-amber-600">{{ $draftPosts }} drafts</span>
                        </p>
                    </div>
                    <div class="w-16 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-blue-400 rounded-full" style="width: {{ $totalPosts > 0 ? ($publishedPosts / $totalPosts) * 100 : 0 }}%;"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Manage Posts →
                    </a>
                </div>
            </div>

            <!-- Pages Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-600 font-medium">Pages</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPages }}</p>
                        <p class="text-sm text-gray-500">
                            <span class="text-green-600 font-medium">{{ $publishedPages }} published</span> · 
                            <span class="text-amber-600">{{ $draftPages }} drafts</span>
                        </p>
                    </div>
                    <div class="w-16 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-green-400 rounded-full" style="width: {{ $totalPages > 0 ? ($publishedPages / $totalPages) * 100 : 0 }}%;"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('pages.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                        Manage Pages →
                    </a>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-600 font-medium">Categories</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                        <p class="text-sm text-gray-500">Total categories created</p>
                    </div>
                    <div class="w-16 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-purple-400 rounded-full" style="width: 85%;"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('categories.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                        Manage Categories →
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Content Creation Chart -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Content Creation (Last 6 Months)</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Posts</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Pages</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Area -->
                    <div class="relative h-64 flex items-end justify-between space-x-2">
                        <!-- Chart Bars -->
                        <div class="flex items-end h-full space-x-2 w-full">
                            @foreach($monthlyStats as $stat)
                                <div class="flex items-end space-x-1 flex-1">
                                    <!-- Posts Bar -->
                                    <div class="bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-sm flex-1" 
                                         style="height: {{ $stat['posts'] > 0 ? max(10, ($stat['posts'] / max(array_column($monthlyStats, 'posts')) * 80)) : 5 }}%;"></div>
                                    <!-- Pages Bar -->
                                    <div class="bg-gradient-to-t from-green-500 to-green-400 rounded-t-sm flex-1" 
                                         style="height: {{ $stat['pages'] > 0 ? max(10, ($stat['pages'] / max(1, max(array_column($monthlyStats, 'pages'))) * 80)) : 5 }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Chart Labels -->
                    <div class="flex justify-between mt-4 text-xs text-gray-500">
                        @foreach($monthlyStats as $stat)
                            <span>{{ $stat['month'] }}</span>
                        @endforeach
                    </div>

                    <!-- Chart Stats -->
                    <div class="flex items-center justify-between mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ array_sum(array_column($monthlyStats, 'posts')) }}</p>
                            <p class="text-sm text-gray-600">Posts (6 months)</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ array_sum(array_column($monthlyStats, 'pages')) }}</p>
                            <p class="text-sm text-gray-600">Pages (6 months)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Recent Posts -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
                        <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($recentPosts as $post)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($post->image_url)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ Str::limit($post->title, 40) }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="text-xs px-2 py-1 rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500">No posts yet</p>
                                <a href="{{ route('posts.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">Create your first post</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Popular Categories -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Popular Categories</h3>
                        <a href="{{ route('categories.index') }}" class="text-sm text-purple-600 hover:text-purple-800">View All</a>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($popularCategories as $category)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $category->posts_count }} posts</p>
                                    </div>
                                </div>
                                <div class="w-12 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-purple-500 rounded-full" 
                                         style="width: {{ $popularCategories->first()->posts_count > 0 ? ($category->posts_count / $popularCategories->first()->posts_count) * 100 : 0 }}%;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500">No categories yet</p>
                                <a href="{{ route('categories.create') }}" class="text-purple-600 hover:text-purple-800 text-sm">Create your first category</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('posts.create') }}" 
                           class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">New Post</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('pages.create') }}" 
                           class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">New Page</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('categories.create') }}" 
                           class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">New Category</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
