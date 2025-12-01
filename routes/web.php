<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::prefix('user')->group(function () {
    Route::get('/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/{user}/posts', [UserController::class, 'posts'])->name('user.posts');
});

Route::get('dashboard', function () {
    return Inertia::render('Welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
