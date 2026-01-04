<?php

use App\Http\Controllers\PaymentCallbackController;
use Illuminate\Support\Facades\Route;

// ... kodingan lain ...

// Ini alamat yang akan dikunjungi Midtrans
Route::post('midtrans/callback', [PaymentCallbackController::class, 'receive']);