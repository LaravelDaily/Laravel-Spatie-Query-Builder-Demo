<x-layouts.admin title="Post Details">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">{{ Str::limit($post->title, 50) }}</h1>
            <a href="{{ route('admin.posts.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Back to Posts
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Post Content -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow rounded-lg">
                <!-- Featured Image -->
                @if($post->featured_image)
                <div class="aspect-w-16 aspect-h-9">
                    <img class="w-full h-64 object-cover rounded-t-lg" src="{{ $post->featured_image }}" alt="{{ $post->title }}">
                </div>
                @endif

                <div class="p-6">
                    <!-- Title -->
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                    <!-- Meta Information -->
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            @if($post->author->avatar)
                                <img class="h-8 w-8 rounded-full" src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-700">{{ $post->author->initials() }}</span>
                                </div>
                            @endif
                            <span>by <a href="{{ route('admin.users.show', $post->author) }}" class="text-indigo-600 hover:text-indigo-500">{{ $post->author->name }}</a></span>
                        </div>
                        <span>•</span>
                        <span>{{ $post->created_at->format('F j, Y') }}</span>
                        @if($post->published_at)
                            <span>•</span>
                            <span>Published {{ $post->published_at->format('F j, Y') }}</span>
                        @endif
                    </div>

                    <!-- Excerpt -->
                    @if($post->excerpt)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Excerpt</h3>
                        <p class="text-gray-700">{{ $post->excerpt }}</p>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Post Details</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($post->status->name === 'published') bg-green-100 text-green-800 
                                    @elseif($post->status->name === 'draft') bg-yellow-100 text-yellow-800 
                                    @elseif($post->status->name === 'scheduled') bg-blue-100 text-blue-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($post->status->name) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                      style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                                    {{ $post->category->name }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Author</dt>
                            <dd class="mt-1">
                                <a href="{{ route('admin.users.show', $post->author) }}" 
                                   class="text-sm text-indigo-600 hover:text-indigo-500">
                                    {{ $post->author->name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $post->slug }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $post->created_at->format('F j, Y g:i A') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $post->updated_at->format('F j, Y g:i A') }}</dd>
                        </div>

                        @if($post->published_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Published At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $post->published_at->format('F j, Y g:i A') }}</dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Featured Image</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $post->featured_image ? 'Yes' : 'No' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.posts.index', ['filter[user_id]' => $post->author->id]) }}" 
                       class="block w-full px-4 py-2 text-sm text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        View all posts by {{ $post->author->name }}
                    </a>
                    <a href="{{ route('admin.posts.index', ['filter[category_id]' => $post->category->id]) }}" 
                       class="block w-full px-4 py-2 text-sm text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        View all posts in {{ $post->category->name }}
                    </a>
                    <a href="{{ route('admin.posts.index', ['filter[status_id]' => $post->status->id]) }}" 
                       class="block w-full px-4 py-2 text-sm text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        View all {{ $post->status->name }} posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
