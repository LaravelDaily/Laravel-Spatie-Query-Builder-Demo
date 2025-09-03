<x-layouts.admin title="User Details">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">{{ $user->name }}</h1>
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Details -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">User Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        @if($user->avatar)
                            <img class="h-16 w-16 rounded-full" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-lg font-medium text-gray-700">{{ $user->initials() }}</span>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->role->name === 'admin') bg-red-100 text-red-800 
                                    @elseif($user->role->name === 'editor') bg-blue-100 text-blue-800 
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->status->name === 'active') bg-green-100 text-green-800 
                                    @elseif($user->status->name === 'inactive') bg-red-100 text-red-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($user->status->name) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F j, Y') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->format('F j, Y g:i A') : 'Never' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->email_verified_at ? 'Yes (' . $user->email_verified_at->format('M j, Y') . ')' : 'No' }}
                            </dd>
                        </div>

                        @if($user->bio)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->bio }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Posts ({{ $user->posts->count() }} total)</h3>
                </div>
                <div class="p-6">
                    @if($user->posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->posts as $post)
                            <div class="border-l-4 border-indigo-400 pl-4">
                                <div class="flex justify-between items-start">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.posts.show', $post) }}" class="hover:text-indigo-600">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        @if($post->excerpt)
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($post->excerpt, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($post->status->name === 'published') bg-green-100 text-green-800 
                                            @elseif($post->status->name === 'draft') bg-yellow-100 text-yellow-800 
                                            @elseif($post->status->name === 'scheduled') bg-blue-100 text-blue-800 
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($post->status->name) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center mt-2 text-xs text-gray-500 space-x-4">
                                    <span>{{ $post->category->name }}</span>
                                    <span>{{ $post->created_at->format('M j, Y') }}</span>
                                    @if($post->published_at)
                                        <span>Published {{ $post->published_at->format('M j, Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($user->posts->count() >= 10)
                        <div class="mt-6">
                            <a href="{{ route('admin.posts.index', ['filter[user_id]' => $user->id]) }}" 
                               class="text-sm text-indigo-600 hover:text-indigo-500">
                                View all posts by {{ $user->name }} →
                            </a>
                        </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-8">
                            {{ $user->name }} hasn't created any posts yet.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
