<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Midtrans\Config; // Tambahkan ini agar lebih rapi
use Midtrans\Snap;   // Tambahkan ini

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'total_amount' => 'required|numeric|min:0',
            'ticket_category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        // Pastikan user login (Safety Check)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu');
        }

        // 1. Ambil data Event dan Kategori untuk mendapatkan namanya
        $event = Event::findOrFail($request->event_id);
        $category = TicketCategory::findOrFail($request->ticket_category_id);

        // 2. Simpan transaksi ke DB lokal
        $transaction = Transaction::create([
            'user_id'                => Auth::id(),
            'event_id'               => $event->id,
            'ticket_category_id'     => $category->id,
            'reference_number'       => 'CAMPUS-' . time() . rand(10, 99),
            'customer_name'          => Auth::user()->name,
            'customer_email'         => Auth::user()->email,
            'event_name'             => $event->nama_event,
            'ticket_category'        => $category->name,
            'ticket_category_name'   => $category->name,
            'total_price'            => $request->total_amount,
            'status'                 => 'pending',
        ]);

        // 3. Konfigurasi Midtrans (Gunakan Aliasing agar rapi)
        Config::$serverKey    = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->reference_number,
                'gross_amount' => (int) $transaction->total_price, // Pastikan integer
            ],
            'customer_details' => [
                'first_name' => $transaction->customer_name,
                'email'      => $transaction->customer_email,
            ],
            'callbacks' => [
                'finish' => route('user.transactions.index'), // Balik ke Tiket Saya setelah bayar
            ]
        ];

        try {
            // 3. Dapatkan Snap Redirect URL
            $paymentUrl = Snap::createTransaction($params)->redirect_url;

            // 4. Update transaksi dengan payment_url
            $transaction->update(['payment_url' => $paymentUrl]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            // Jika gagal, hapus transaksi yang sudah dibuat
            $transaction->delete();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}