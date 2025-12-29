<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\Admin\TicketCategoryController;

// Root route - redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

// Login Routes (tanpa middleware auth)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [LoginController::class, 'login'])->name('admin.login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// User auth (separate from admin)
Route::get('/register', [\App\Http\Controllers\Auth\UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\UserAuthController::class, 'register'])->name('register.store');
Route::get('/login/user', [\App\Http\Controllers\Auth\UserAuthController::class, 'showLoginForm'])->name('user.login');
Route::post('/login/user', [\App\Http\Controllers\Auth\UserAuthController::class, 'login'])->name('user.login.store');
Route::post('/logout/user', [\App\Http\Controllers\Auth\UserAuthController::class, 'logout'])->name('user.logout');

// Dashboard (authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin Routes (dengan middleware auth + admin check)
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    // Admin Event CRUD
    Route::get('/admin/events', [AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/admin/events/create', [AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/admin/events', [AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{event}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{event}', [AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [AdminEventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/admin/events/{event}/export', [AdminEventController::class, 'exportRundown'])->name('admin.events.export');

    // Admin User Management
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Ticket Categories (nested)
    Route::get('/admin/events/{event}/categories', [TicketCategoryController::class, 'index'])->name('admin.events.categories.index');
    Route::get('/admin/events/{event}/categories/create', [TicketCategoryController::class, 'create'])->name('admin.events.categories.create');
    Route::post('/admin/events/{event}/categories', [TicketCategoryController::class, 'store'])->name('admin.events.categories.store');
    Route::get('/admin/events/{event}/categories/{category}/edit', [TicketCategoryController::class, 'edit'])->name('admin.events.categories.edit');
    Route::put('/admin/events/{event}/categories/{category}', [TicketCategoryController::class, 'update'])->name('admin.events.categories.update');
    Route::delete('/admin/events/{event}/categories/{category}', [TicketCategoryController::class, 'destroy'])->name('admin.events.categories.destroy');
});

// Public events listing
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

