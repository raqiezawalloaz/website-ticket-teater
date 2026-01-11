<?php

use Illuminate\Support\Facades\Route;

// Import Controller
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController; // Kita pakai Controller Hybrid tadi
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {

    // =====================
    // 1. GUEST / PUBLIC AREA
    // =====================
    
    // Redirect root ke login (opsional, bisa diganti ke landing page)
    // Landing Page
    Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

    // Auth: Login & Logout
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Name disesuaikan standar Laravel
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Auth: Register
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.store');

    // Public Events (User bisa lihat event tanpa login)
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

    // Tenant & Sponsor Public
    Route::get('/tenants', [App\Http\Controllers\PublicTenantController::class, 'index'])->name('public.tenants.index');
    Route::get('/sponsors', [App\Http\Controllers\PublicSponsorController::class, 'index'])->name('public.sponsors.index');


    // =====================
    // 2. LOGGED IN USER AREA
    // =====================
    Route::middleware(['auth'])->group(function () {
        
        // Dashboard User Biasa
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- RUTE PENTING: CHECKOUT / PEMBAYARAN USER ---
        // Ini rute untuk memproses form "Beli Tiket" -> ke Midtrans
        Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
        
        // Halaman Finish (Opsional: redirect setelah bayar sukses)
        Route::get('/payment/finish', function() {
            return view('user.payment_finish'); // Pastikan view ini ada atau ganti redirect
        })->name('payment.finish');

        // Daftar Tiket Saya
        Route::get('/tickets', [TransactionController::class, 'myTickets'])->name('user.tickets.index');
        Route::get('/tickets/{id}/download', [TransactionController::class, 'downloadTicket'])->name('user.tickets.download');
        Route::delete('/tickets/{id}/cancel', [TransactionController::class, 'cancel'])->name('user.tickets.cancel');
    
    // Payment Simulation Routes
    Route::get('/payment/simulate/{id}', [TransactionController::class, 'showSimulation'])->name('payment.simulate');
    Route::post('/payment/simulate/process', [TransactionController::class, 'processSimulation'])->name('payment.simulate.process');

    // Feedback Routes
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    });


    // =====================
    // 3. ADMIN AREA (Backoffice)
    // =====================
    Route::middleware(['auth', EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->name('admin.') // Semua route di sini diawali "admin."
        ->group(function () {

            // Dashboard Admin
            // (Note: Jika controller dashboard sama, pastikan logic di dalamnya membedakan view admin/user)
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // EVENT MANAGEMENT
            Route::resource('events', AdminEventController::class)->except(['show']);
            Route::get('events/{event}/export', [AdminEventController::class, 'exportRundown'])
                ->name('events.export');

            // TICKET CATEGORIES (Nested Resource)
            Route::prefix('events/{event}')
                ->name('events.')
                ->group(function () {
                    Route::resource('categories', TicketCategoryController::class)->except(['show']);
                });

            // USER MANAGEMENT
            Route::resource('users', AdminUserController::class);

            // =====================
            // TENANT MANAGEMENT
            // =====================
            Route::resource('tenants', TenantController::class);

            // =====================
            // SPONSOR MANAGEMENT
            // =====================
            Route::resource('sponsors', SponsorController::class);

            // =====================
            // TRANSACTIONS MODULE
            // =====================
            Route::prefix('transactions')->name('transactions.')->group(function () {
                
                // List Transaksi
                Route::get('/', [TransactionController::class, 'index'])->name('index');
                
                // Export PDF
                Route::get('/export-pdf', [TransactionController::class, 'exportPdf'])->name('exportPdf');

                // Aksi Transaksi (Mockup & Real)
                Route::get('/{id}/mock-pay', [TransactionController::class, 'mockPayment'])->name('mockPay');
                Route::post('/{id}/check-in', [TransactionController::class, 'checkIn'])->name('checkIn');
                
                // --- Rute Check Status (Sesuai Tips Kamu) ---
                // URL: /admin/transactions/{id}/check
                Route::get('/{id}/check', [TransactionController::class, 'checkStatus'])->name('checkStatus');

                // Update Status Manual & Delete
                Route::put('/{id}/status', [TransactionController::class, 'updateStatus'])->name('updateStatus');
                Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
            });

        });
});