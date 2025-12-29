<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController;
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

    // CRUD lainnya
    Route::put('/{id}/status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
});
            });
        });

