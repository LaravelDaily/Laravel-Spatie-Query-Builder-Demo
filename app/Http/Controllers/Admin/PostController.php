<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = QueryBuilder::for(Post::class)
            ->allowedFilters([
                // Global search across title and content
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('title', 'like', "%{$value}%")
                              ->orWhere('content', 'like', "%{$value}%")
                              ->orWhereHas('author', function ($query) use ($value) {
                                  $query->where('name', 'like', "%{$value}%");
                              });
                    });
                }),
                
                // Exact filters for dropdowns
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('user_id'),
                
                // Date range filters
                AllowedFilter::callback('published_after', function ($query, $value) {
                    $query->where('published_at', '>=', $value);
                }),
                AllowedFilter::callback('published_before', function ($query, $value) {
                    $query->where('published_at', '<=', $value);
                }),
                
                // Custom scopes
                AllowedFilter::scope('published'),
                AllowedFilter::scope('featured'),
            ])
            ->allowedSorts([
                'title',
                'created_at', 
                'published_at',
                'status_id',
                'category_id',
                'user_id'
            ])
            ->allowedIncludes(['author', 'category', 'status'])
            ->defaultSort('-created_at')
            ->with(['author', 'category', 'status']) // Always load relationships
            ->paginate(15)
            ->appends(request()->query());

        // Get filter options for dropdowns
        $statuses = PostStatus::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $authors = User::whereIn('role_id', [2, 3])->orderBy('name')->get(); // editors and authors

        return view('admin.posts.index', compact('posts', 'statuses', 'categories', 'authors'));
    }

    public function show(Post $post)
    {
        $post->load(['author', 'category', 'status']);
        
        return view('admin.posts.show', compact('post'));
    }
}
