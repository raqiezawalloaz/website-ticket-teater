<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * WEBHOOK HANDLER - Menerima notifikasi dari Midtrans
     * 
     * Midtrans akan POST ke route ini saat:
     * - Pembayaran berhasil (capture/settlement)
     * - Pembayaran gagal (deny/cancel)
     * - Pembayaran expired
     * - Dll
     */
    public function receive(Request $request)
    {
        // 1. LOG: Simpan semua callback yang masuk (Debugging & Audit)
        Log::info('===== MIDTRANS CALLBACK RECEIVED =====');
        Log::info('Callback Data: ', $request->all());

        // 2. Ambil data dari Midtrans
        $notification = $request->all();
        
        // 3. Verifikasi signature dari Midtrans (keamanan penting!)
        $isValid = $this->midtransService->verifySignature($notification);
        
        if (!$isValid) {
            Log::error('Invalid Signature from Midtrans', $notification);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 4. Cari transaksi di database berdasarkan order_id
        $orderId = $notification['order_id'] ?? null;
        $transaction = Transaction::where('reference_number', $orderId)->first();

        if (!$transaction) {
            Log::error("Transaction not found: {$orderId}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 5. Ambil status dari Midtrans
        $transactionStatus = $notification['transaction_status'] ?? null;
        $fraudStatus = $notification['fraud_status'] ?? null;
        $paymentType = $notification['payment_type'] ?? 'unknown';

        Log::info("Processing Transaction {$orderId}: Status={$transactionStatus}, FraudStatus={$fraudStatus}, PaymentType={$paymentType}");

        // 6. LOGIC MAPPING STATUS DARI MIDTRANS KE APLIKASI
        // =====================================================
        // STATUS: CAPTURE (Biasanya untuk Kartu Kredit)
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Pembayaran BERHASIL via Kartu Kredit
                $transaction->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);
                Log::info("✓ Transaction {$orderId} marked as SUCCESS (Capture accepted)");
            } else {
                // Pembayaran TERTUNDA (Fraud Detection)
                $transaction->update(['status' => 'pending']);
                Log::warning("⚠ Transaction {$orderId} marked as PENDING (Fraud check)");
            }
        }
        // STATUS: SETTLEMENT (Biasanya untuk GoPay, ShopeePay, Transfer Bank, Alfamart)
        elseif ($transactionStatus == 'settlement') {
            // Dana sudah masuk ke rekening BANK MERCHANT
            $transaction->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);
            Log::info("✓ Transaction {$orderId} marked as SUCCESS (Settlement - Dana masuk)");
        }
        // STATUS: PENDING (Menunggu pembayaran dari user)
        elseif ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
            Log::info("⏳ Transaction {$orderId} marked as PENDING (Waiting for payment)");
        }
        // STATUS: DENY (Pembayaran ditolak)
        elseif ($transactionStatus == 'deny') {
            $transaction->update(['status' => 'failed']);
            Log::warning("✗ Transaction {$orderId} marked as FAILED (Denied)");
        }
        // STATUS: EXPIRE (Waktu pembayaran habis)
        elseif ($transactionStatus == 'expire') {
            $transaction->update(['status' => 'expired']);
            Log::warning("⏰ Transaction {$orderId} marked as EXPIRED (Time limit exceeded)");
        }
        // STATUS: CANCEL (Pembayaran dibatalkan)
        elseif ($transactionStatus == 'cancel') {
            $transaction->update(['status' => 'failed']);
            Log::warning("✗ Transaction {$orderId} marked as FAILED (Cancelled)");
        }

        Log::info('===== CALLBACK PROCESSING COMPLETE =====');

        // 7. Response OK ke Midtrans (agar tahu webhook diterima dengan baik)
        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}