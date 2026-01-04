<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransApi;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getSnapToken($transaction)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->reference_number,
                'gross_amount' => (int) $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $transaction->customer_name,
                'email' => $transaction->customer_email,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Mengambil status terbaru dan langsung memetakan ke status lokal kita.
     * Mengembalikan string: 'success', 'pending', atau 'failed'.
     */
    public function getLatestStatus(string $referenceNumber): string
    {
        try {
            // Kita ambil response mentah
            $response = MidtransApi::status($referenceNumber);
            
            // Konversi ke array agar IDE tidak bingung/merah (Mixed Object ke Array)
            $resArray = (array) $response;
            $status = $resArray['transaction_status'] ?? 'pending';

            // Mapping di dalam Service (Encapsulation)
            return match ($status) {
                'capture', 'settlement' => 'success',
                'pending'               => 'pending',
                'deny', 'expire', 'cancel' => 'failed',
                default                 => 'pending',
            };
        } catch (\Exception $e) {
            throw new \Exception("Data transaksi belum ada di Midtrans.");
        }
    }
}