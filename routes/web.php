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


    // =====================
    // 2. LOGGED IN USER AREA
    // =====================
    Route::middleware(['auth'])->group(function () {
        
        // Dashboard User (Controller akan cek jika Admin -> redirect ke admin dashboard)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- RIWAYAT TRANSAKSI USER (POV USER) ---
        // User hanya bisa melihat transaksi mereka sendiri
        Route::get('/my-transactions', [UserTransactionController::class, 'index'])->name('user.transactions.index');
        Route::get('/my-transactions/{id}', [UserTransactionController::class, 'show'])->name('user.transactions.show');
        Route::get('/my-transactions/{id}/download', [UserTransactionController::class, 'downloadTicket'])->name('user.transactions.download');

        // --- CHECKOUT & PEMBAYARAN ---
        // Route untuk memproses checkout dan membuat transaksi
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        
        // Halaman Finish (Redirect dari Midtrans)
        Route::get('/payment/finish', function() {
            return view('user.payment_finish'); 
        })->name('payment.finish');
    });


    // =====================
    // 3. ADMIN AREA (Backoffice)
    // =====================
    Route::middleware(['auth', EnsureUserIsAdmin::class])
        ->prefix('admin')
        ->name('admin.') // Prefix Nama Route (misal: admin.dashboard)
        ->group(function () {

            // Dashboard Admin
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Events
            Route::resource('events', AdminEventController::class)->except(['show']);
            Route::get('events/{event}/export', [AdminEventController::class, 'exportRundown'])
                ->name('events.export');

            // Ticket Categories
            Route::prefix('events/{event}')
                ->name('events.')
                ->group(function () {
                    Route::resource('categories', TicketCategoryController::class)->except(['show']);
                });

            // User Management
            Route::resource('users', AdminUserController::class);

            // Transactions Module (Admin POV)
            Route::prefix('transactions')->name('transactions.')->group(function () {
                Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
                Route::get('/export-pdf', [AdminTransactionController::class, 'exportPdf'])->name('exportPdf');
                
                Route::get('/{id}/mock-pay', [AdminTransactionController::class, 'mockPayment'])->name('mockPay');
                Route::post('/{id}/check-in', [AdminTransactionController::class, 'checkIn'])->name('checkIn');
                Route::get('/{id}/check', [AdminTransactionController::class, 'checkStatus'])->name('checkStatus');
                
                Route::put('/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('updateStatus');
                Route::delete('/{id}', [AdminTransactionController::class, 'destroy'])->name('destroy');
            });
        });
});