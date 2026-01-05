<?php

use App\Http\Controllers\Api\PaymentCallbackController;
use Illuminate\Support\Facades\Route;

// =====================
// WEBHOOK MIDTRANS (Jantung Otomatisasi)
// =====================
// Route ini dapat diakses publik oleh Midtrans (tanpa autentikasi)
// Midtrans akan memanggil URL ini saat pembayaran selesai
Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);