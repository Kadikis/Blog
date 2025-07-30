<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'is-admin']], function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('admin.dashboard');

    Route::resource('posts', AdminPostController::class)->names('admin.posts');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
