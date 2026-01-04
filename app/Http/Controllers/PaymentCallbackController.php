<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function receive()
    {
        // 1. Konfigurasi Midtrans dulu (PENTING!)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            // 2. Tangkap notifikasi dari Midtrans
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // 3. Cari Transaksi di Database kita
            // Pastikan kolom di databasemu namanya 'code' atau 'reference_number'
            $localTransaction = Transaction::where('code', $order_id)->first();

            if (!$localTransaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // 4. Logika Update Status
            if ($transaction == 'capture') {
                // Untuk pembayaran kartu kredit
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $localTransaction->update(['status' => 'pending']);
                    } else {
                        $localTransaction->update(['status' => 'success']);
                    }
                }
            } elseif ($transaction == 'settlement') {
                // Untuk pembayaran selain kartu kredit (GoPay, Transfer Bank, dll)
                $localTransaction->update(['status' => 'success']);
            } elseif ($transaction == 'pending') {
                $localTransaction->update(['status' => 'pending']);
            } elseif ($transaction == 'deny') {
                $localTransaction->update(['status' => 'failed']);
            } elseif ($transaction == 'expire') {
                $localTransaction->update(['status' => 'failed']);
            } elseif ($transaction == 'cancel') {
                $localTransaction->update(['status' => 'failed']);
            }

            return response()->json(['message' => 'Payment status updated']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}