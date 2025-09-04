<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $viewStats = $this->getViewStats();
        $topPosts = $this->getTopPosts();
        $recentViews = $this->getRecentViews();
        
        return view('admin.analytics.index', compact('viewStats', 'topPosts', 'recentViews'));
    }
    
    private function getViewStats(): array
    {
        $now = Carbon::now();
        
        return [
            'last_7_days' => PostView::where('viewed_at', '>=', $now->copy()->subDays(7))->count(),
            'last_30_days' => PostView::where('viewed_at', '>=', $now->copy()->subDays(30))->count(),
            'last_90_days' => PostView::where('viewed_at', '>=', $now->copy()->subDays(90))->count(),
        ];
    }
    
    private function getTopPosts()
    {
        return Post::select('posts.*', DB::raw('COUNT(post_views.id) as views_count'))
            ->leftJoin('post_views', 'posts.id', '=', 'post_views.post_id')
            ->where('post_views.viewed_at', '>=', Carbon::now()->subDays(30))
            ->with(['user', 'category', 'status'])
            ->groupBy('posts.id')
            ->orderByDesc('views_count')
            ->limit(5)
            ->get();
    }
    
    private function getRecentViews()
    {
        return PostView::with(['post', 'user'])
            ->orderByDesc('viewed_at')
            ->limit(20)
            ->get();
    }
}
