<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// IMPORT CONTROLLERS
// ==========================================

// Auth & Base
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\FeedbackController; // Controller Hybrid

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController; // Controller yg kita edit sebelumnya
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;

// User / Public Controllers
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\PublicTenantController;
use App\Http\Controllers\PublicSponsorController;
// Note: Kita gunakan AdminTransactionController untuk user juga karena method myTickets ada disana
// use App\Http\Controllers\User\TransactionController as UserTransactionController; 

// Middleware
use App\Http\Middleware\EnsureUserIsAdmin;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web'])->group(function () {

    // ==========================================
    // 1. GUEST / PUBLIC AREA
    // ==========================================
    
    // LANDING PAGE (Pilih versi Arrival karena lebih proper dibanding redirect)
    Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

    // AUTHENTICATION
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.store');

    // PUBLIC EVENTS
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

    // PUBLIC TENANTS & SPONSORS
    Route::get('/tenants', [PublicTenantController::class, 'index'])->name('public.tenants.index');
    Route::get('/sponsors', [PublicSponsorController::class, 'index'])->name('public.sponsors.index');


    // ==========================================
    // 2. LOGGED IN USER AREA
    // ==========================================
    Route::middleware(['auth'])->group(function () {
        
        // DASHBOARD (Redirect logic Admin/User ada di dalam controller ini)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- TRANSAKSI & TIKET USER ---
        // Menggunakan AdminTransactionController karena method myTickets, checkout, dll ada disana (sesuai merge sebelumnya)
        
        // Proses Checkout
        Route::post('/checkout', [AdminTransactionController::class, 'checkout'])->name('checkout'); // Nama route disamakan dengan view
        
        // Halaman Finish (Terima Kasih)
        Route::get('/payment/finish', function() {
            return view('user.payment_finish'); 
        })->name('payment.finish');

        // Tiket Saya (List & Download PDF)
        Route::get('/tickets', [AdminTransactionController::class, 'myTickets'])->name('user.tickets.index');
        Route::get('/tickets/{id}/download', [AdminTransactionController::class, 'downloadTicket'])->name('user.tickets.download');
        Route::delete('/tickets/{id}/cancel', [AdminTransactionController::class, 'cancel'])->name('user.tickets.cancel');

        // Simulasi Pembayaran (Fitur Arrival)
        Route::get('/payment/simulate/{id}', [AdminTransactionController::class, 'showSimulation'])->name('payment.simulate');
        Route::post('/payment/simulate/process', [AdminTransactionController::class, 'processSimulation'])->name('payment.simulate.process');

        // --- SERTIFIKAT USER (Fitur Main) ---
        // Asumsi logic download sertifikat user ada di AdminTransactionController atau controller khusus
        // Jika belum ada controller user certificate, kita bisa arahkan sementara
        Route::get('/certificates/{transactionId}', [AdminTransactionController::class, 'downloadCertificate'])->name('certificates.download');

        // --- FEEDBACK (Hybrid) ---
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index'); // List Feedback (User lihat punya sendiri)
        Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store'); // Simpan Feedback
        
        // Delete Feedback (User boleh hapus punya sendiri jika logic controller mengizinkan)
        Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    });


    // ==========================================
    // 3. ADMIN AREA (Backoffice)
    // ==========================================
    Route::middleware(['auth', EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // DASHBOARD
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // EVENT MANAGEMENT
            Route::resource('events', AdminEventController::class);
            Route::get('events/{event}/export', [AdminEventController::class, 'exportRundown'])->name('events.export');

            // TICKET CATEGORIES
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
                Route::get('/', [AdminCertificateController::class, 'index'])->name('index');
                Route::get('/event/{event}', [AdminCertificateController::class, 'show'])->name('show');
                // Admin bisa download sertifikat siapa saja untuk testing
                Route::get('/download/{transactionId}', [AdminCertificateController::class, 'download'])->name('download');
            });

            // FEEDBACK MANAGEMENT (Admin View All)
            // Menggunakan controller yang sama, tapi logic index membedakan role admin
            Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
            
            // TRANSACTIONS MANAGEMENT
            Route::prefix('transactions')->name('transactions.')->group(function () {
                Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
                Route::get('/export-pdf', [AdminTransactionController::class, 'exportPdf'])->name('exportPdf');
                
                // Fitur Verifikasi Manual & Check-in
                Route::get('/{id}/mock-pay', [AdminTransactionController::class, 'mockPayment'])->name('mockPay');
                Route::post('/{id}/check-in', [AdminTransactionController::class, 'checkIn'])->name('checkIn');
                Route::get('/{id}/check-status', [AdminTransactionController::class, 'checkStatus'])->name('checkStatus');
                
                Route::put('/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('updateStatus');
                Route::delete('/{id}', [AdminTransactionController::class, 'destroy'])->name('destroy');
            });
        });
});