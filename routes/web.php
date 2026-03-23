<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AnalyticalSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware(['auth', 'lastseen'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

    Route::get('/sessions', [AnalyticalSessionController::class, 'index'])->name('sessions.index');
    // Важно: ограничиваем параметр, чтобы /sessions/create не матчился как {session}
    Route::get('/sessions/{session}', [AnalyticalSessionController::class, 'show'])
        ->whereNumber('session')
        ->name('sessions.show');

    Route::get('/sessions/{session}/export/csv', [AnalyticalSessionController::class, 'exportCsv'])
        ->whereNumber('session')
        ->name('sessions.export.csv');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'lastseen', 'role:admin,analyst'])->group(function () {
    Route::get('/sessions/create', [AnalyticalSessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions', [AnalyticalSessionController::class, 'store'])->name('sessions.store');
    Route::post('/sessions/{session}/rerun', [AnalyticalSessionController::class, 'rerun'])
        ->whereNumber('session')
        ->name('sessions.rerun');
    Route::delete('/sessions/{session}', [AnalyticalSessionController::class, 'destroy'])
        ->whereNumber('session')
        ->name('sessions.destroy');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'lastseen', 'role:admin'])
    ->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('sources', AdminSourceController::class)->except(['show']);
        Route::resource('topics', AdminTopicController::class)->except(['show']);
    });