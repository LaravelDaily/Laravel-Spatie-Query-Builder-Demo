<x-layouts.admin title="Posts">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Posts</h1>
            <div class="text-sm text-gray-500">
                Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
            </div>
        </div>
    </x-slot>

    <div class="bg-white shadow rounded-lg">
        <!-- Search and Filters -->
        <form method="GET" class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <x-admin.search-form 
                        placeholder="Search posts..." 
                        :value="request('filter.search')" />
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <x-admin.filter-select 
                        name="status_id"
                        :options="$statuses->pluck('name', 'id')"
                        :selected="request('filter.status_id')"
                        placeholder="All Statuses" />
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <x-admin.filter-select 
                        name="category_id"
                        :options="$categories->pluck('name', 'id')"
                        :selected="request('filter.category_id')"
                        placeholder="All Categories" />
                </div>

                <!-- Author Filter -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                    <x-admin.filter-select 
                        name="user_id"
                        :options="$authors->pluck('name', 'id')"
                        :selected="request('filter.user_id')"
                        placeholder="All Authors" />
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Filter
                    </button>
                    <a href="{{ route('admin.posts.index') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Clear
                    </a>
                </div>
            </div>

            <!-- Quick Filter Buttons -->
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ request()->fullUrlWithQuery(['filter[published]' => '1']) }}" 
                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('filter.published') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                    Published Only
                </a>
                <a href="{{ request()->fullUrlWithQuery(['filter[featured]' => '1']) }}" 
                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('filter.featured') ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                    Featured Only
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => '-published_at']) }}" 
                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('sort') === '-published_at' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                    Recently Published
                </a>
            </div>
        </form>

        <!-- Active Filters Display -->
        @php
            $activeFilters = [];
            if(request('filter.search')) $activeFilters['Search'] = request('filter.search');
            if(request('filter.status_id')) $activeFilters['Status'] = $statuses->find(request('filter.status_id'))->name ?? '';
            if(request('filter.category_id')) $activeFilters['Category'] = $categories->find(request('filter.category_id'))->name ?? '';
            if(request('filter.user_id')) $activeFilters['Author'] = $authors->find(request('filter.user_id'))->name ?? '';
            if(request('filter.published')) $activeFilters['Published'] = 'Yes';
            if(request('filter.featured')) $activeFilters['Featured'] = 'Yes';
            if(request('sort')) $activeFilters['Sort'] = str_replace(['-', '_'], [' (desc)', ' '], request('sort'));
        @endphp
        <x-admin.active-filters :filters="$activeFilters" />

        <!-- Posts Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <x-admin.sortable-header field="title" :current-sort="request('sort')">
                                Post
                            </x-admin.sortable-header>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <x-admin.sortable-header field="user_id" :current-sort="request('sort')">
                                Author
                            </x-admin.sortable-header>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <x-admin.sortable-header field="category_id" :current-sort="request('sort')">
                                Category
                            </x-admin.sortable-header>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <x-admin.sortable-header field="status_id" :current-sort="request('sort')">
                                Status
                            </x-admin.sortable-header>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <x-admin.sortable-header field="published_at" :current-sort="request('sort')">
                                Published
                            </x-admin.sortable-header>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-start space-x-3">
                                @if($post->featured_image)
                                    <img class="h-12 w-12 rounded-lg object-cover flex-shrink-0" src="{{ $post->featured_image }}" alt="">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.posts.show', $post) }}" class="hover:text-indigo-600">
                                            {{ Str::limit($post->title, 60) }}
                                        </a>
                                    </div>
                                    @if($post->excerpt)
                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ Str::limit($post->excerpt, 80) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    @if($post->author->avatar)
                                        <img class="h-8 w-8 rounded-full" src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ $post->author->initials() }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $post->author->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                  style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">
                                {{ $post->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($post->status->name === 'published') bg-green-100 text-green-800 
                                @elseif($post->status->name === 'draft') bg-yellow-100 text-yellow-800 
                                @elseif($post->status->name === 'scheduled') bg-blue-100 text-blue-800 
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($post->status->name) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('M j, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.posts.show', $post) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No posts found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="bg-white px-6 py-3 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>
