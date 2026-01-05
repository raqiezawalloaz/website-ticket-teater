<?php

use Illuminate\Support\Facades\Route;

// Import Controller
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\User\TransactionController as UserTransactionController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SponsorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {

    // =====================
    // 1. GUEST / PUBLIC AREA
    // =====================
    
    Route::get('/', fn () => redirect('/login'));

    // Auth Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.store');

    // Public Events
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

    // Tenant & Sponsor Public
    Route::get('/tenants', [App\Http\Controllers\PublicTenantController::class, 'index'])->name('public.tenants.index');
    Route::get('/sponsors', [App\Http\Controllers\PublicSponsorController::class, 'index'])->name('public.sponsors.index');


    // =====================
    // 2. LOGGED IN USER AREA
    // =====================
    Route::middleware(['auth'])->group(function () {
        
        // Dashboard Dinamis (Redirect Admin/User otomatis)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- RIWAYAT TRANSAKSI USER (POV USER) ---
        Route::get('/my-transactions', [UserTransactionController::class, 'index'])->name('user.transactions.index');
        Route::get('/my-transactions/{id}', [UserTransactionController::class, 'show'])->name('user.transactions.show');
        Route::get('/my-transactions/{id}/download', [UserTransactionController::class, 'downloadTicket'])->name('user.transactions.download');

        // --- CHECKOUT & PEMBAYARAN ---
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/payment/finish', function() {
            return view('user.payment_finish'); 
        })->name('payment.finish');

        // --- Feedback & Certificate User ---
        Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/certificates/{transactionId}', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
    });


    // =====================
    // 3. ADMIN AREA (Backoffice)
    // =====================
    Route::middleware(['auth', EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard Admin
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // EVENT MANAGEMENT
            Route::resource('events', AdminEventController::class);
            Route::get('events/{event}/export', [AdminEventController::class, 'exportRundown'])->name('events.export');

            // TICKET CATEGORIES (Sub-resource dari Events)
            Route::prefix('events/{event}')
                ->name('events.')
                ->group(function () {
                    Route::resource('categories', TicketCategoryController::class)->except(['show']);
                });

            // USER MANAGEMENT
            Route::resource('users', AdminUserController::class);

            // TENANT & SPONSOR MANAGEMENT
            Route::resource('tenants', TenantController::class);
            Route::resource('sponsors', SponsorController::class);

            // CERTIFICATE MANAGEMENT (Admin)
            Route::prefix('certificates')->name('certificates.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\CertificateController::class, 'index'])->name('index');
                Route::get('/event/{event}', [App\Http\Controllers\Admin\CertificateController::class, 'show'])->name('show');
                Route::get('/download/{transactionId}', [App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('download');
            });

            // FEEDBACK MANAGEMENT (Admin)
            Route::get('feedbacks', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedbacks.index');
            Route::get('feedbacks/export-pdf', [App\Http\Controllers\Admin\FeedbackController::class, 'exportPdf'])->name('feedbacks.exportPdf');

            // TRANSACTIONS MODULE (Admin POV)
            Route::prefix('transactions')->name('transactions.')->group(function () {
                Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
                Route::get('/export-pdf', [AdminTransactionController::class, 'exportPdf'])->name('exportPdf');
                
                // Fitur Verifikasi & Monitoring
                Route::get('/{id}/mock-pay', [AdminTransactionController::class, 'mockPayment'])->name('mockPay');
                Route::post('/{id}/check-in', [AdminTransactionController::class, 'checkIn'])->name('checkIn');
                Route::get('/{id}/check', [AdminTransactionController::class, 'checkStatus'])->name('checkStatus');
                
                Route::put('/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('updateStatus');
                Route::delete('/{id}', [AdminTransactionController::class, 'destroy'])->name('destroy');
            });
        });
});