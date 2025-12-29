<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\Auth\UserAuthController;

Route::middleware(['web'])->group(function () {

    // 1. Root & Guest Routes
    Route::get('/', function () {
        return redirect('/login');
    });

    // Auth Routes (Admin & User)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.store');

    // 2. Public Routes
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

    // 3. Authenticated Dashboard (Umum)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // 4. ADMIN ROUTES (Area Kerja Kita)
    Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // Dashboard Utama Admin
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // --- Modul Event (Teman Lain) ---
            Route::resource('events', AdminEventController::class)->except(['show']);
            Route::get('/events/{event}/export', [AdminEventController::class, 'exportRundown'])->name('events.export');

            // --- Modul Kategori Tiket (Teman Lain) ---
            Route::prefix('events/{event}')->name('events.')->group(function () {
                Route::resource('categories', TicketCategoryController::class)->except(['show']);
            });

            // --- MODUL 5: MANAJEMEN TRANSAKSI (SINKRON TOTAL) ---
            // --- MODUL 5: MANAJEMEN TRANSAKSI & TIKET ---
Route::prefix('transactions')->name('transactions.')->group(function () {
    // Halaman Utama
    Route::get('/', [TransactionController::class, 'index'])->name('index'); 
    
    // Export PDF
    Route::get('/export-pdf', [TransactionController::class, 'exportPdf'])->name('exportPdf');
    
    // Fitur API & Mock
    Route::get('/{id}/mock-pay', [TransactionController::class, 'mockPayment'])->name('mockPay');
    Route::get('/{id}/check', [TransactionController::class, 'checkStatus'])->name('checkStatus');
    
    // FIX: TAMBAHKAN BARIS INI (Rute untuk tombol Check-in)
    Route::post('/{id}/check-in', [TransactionController::class, 'checkIn'])->name('checkIn');


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

    Route::put('/admin/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
    Route::delete('/admin/transactions/{id}', [TransactionController::class, 'destroy'])->name('admin.transactions.destroy');
    
});
            });
        });


// Public events listing
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');


