<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserStatus;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                // Global search across multiple fields
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%")
                              ->orWhere('email', 'like', "%{$value}%");
                    });
                }),
                
                // Exact filters for dropdowns
                AllowedFilter::exact('role_id'),
                AllowedFilter::exact('status_id'),
                
                // Custom filter for registration date range
                AllowedFilter::callback('registered_after', function ($query, $value) {
                    $query->where('created_at', '>=', $value);
                }),
                AllowedFilter::callback('registered_before', function ($query, $value) {
                    $query->where('created_at', '<=', $value);
                }),
                
                // Filter for active users only
                AllowedFilter::scope('active'),
            ])
            ->allowedSorts([
                'name', 
                'email', 
                'created_at', 
                'last_login_at',
                'role_id',
                'status_id'
            ])
            ->allowedIncludes(['role', 'status', 'posts'])
            ->defaultSort('-created_at')
            ->with(['role', 'status']) // Always load relationships
            ->withCount('posts') // Include post count
            ->paginate(15)
            ->appends(request()->query()); // Preserve query parameters

        // Get filter options for dropdowns
        $roles = UserRole::orderBy('name')->get();
        $statuses = UserStatus::orderBy('name')->get();
        
        return view('admin.users.index', compact('users', 'roles', 'statuses'));
    }

    public function show(User $user)
    {
        $user->load(['role', 'status', 'posts' => function ($query) {
            $query->latest()->take(10);
        }]);
        
        return view('admin.users.show', compact('user'));
    }
}
