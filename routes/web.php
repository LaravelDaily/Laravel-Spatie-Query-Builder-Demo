<?php

use App\Http\Controllers\Settings;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('analytics', [Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::get('posts', [Admin\PostController::class, 'index'])->name('posts.index');
        Route::get('posts/{post}', [Admin\PostController::class, 'show'])->name('posts.show');
    });
});

require __DIR__.'/auth.php';
