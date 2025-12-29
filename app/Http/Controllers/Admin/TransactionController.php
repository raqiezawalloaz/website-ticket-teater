<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Library PDF

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi dan statistik ringkas.
     */
    public function index()
    {
        // Mengambil semua data transaksi terbaru
        $transactions = Transaction::latest()->get();

        // MENGHITUNG STATISTIK (Agar sinkron dengan kotak-kotak di dashboard/index)
        $stats = [
            'total'   => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed'  => Transaction::whereIn('status', ['failed', 'expired'])->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * MINGGU 2: Simulasi Tombol "Bayar" (Mock Payment).
     */
    public function mockPayment($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Update status jadi success
        $transaction->update(['status' => 'success']);

        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Simulasi Pembayaran Berhasil! Pesanan #' . $transaction->reference_number . ' kini Lunas.');
    }

    /**
     * MINGGU 2: Simulasi Cek Status API (Random Status).
     */
    public function checkStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Simulasi respon dari server Payment Gateway
        $statuses = ['success', 'pending', 'failed', 'expired'];
        $randomStatus = $statuses[array_rand($statuses)];

        $transaction->update(['status' => $randomStatus]);

        return redirect()->route('admin.transactions.index')
                         ->with('success', 'API Midtrans Check: Status terbaru untuk #' . $transaction->reference_number . ' adalah ' . strtoupper($randomStatus));
    }

    /**
     * MINGGU 2: Fitur Validasi Tiket (Check-in).
     */
    public function checkIn($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Cek apakah pembayaran sudah sukses
        if ($transaction->status !== 'success') {
            return back()->with('error', 'Tiket tidak bisa Check-in karena belum lunas!');
        }

        // Cek apakah sudah pernah check-in sebelumnya (mencegah tiket ganda)
        if ($transaction->is_checked_in) {
            return back()->with('error', 'Tiket ini sudah melakukan Check-in sebelumnya!');
        }

        $transaction->update(['is_checked_in' => true]);

        return back()->with('success', 'Check-in Berhasil! Selamat menonton untuk #' . $transaction->customer_name);
    }

    /**
     * MINGGU 3: Export ke PDF.
     */
    public function exportPdf()
    {
        $transactions = Transaction::latest()->get();
        
        // Memanggil view khusus PDF (Pastikan file resources/views/admin/transactions/report.blade.php ada)
        $pdf = Pdf::loadView('admin.transactions.report', compact('transactions'));
        
        return $pdf->download('Laporan_Transaksi_Teater_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Update Status Manual (Jika dibutuhkan admin).
     */
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Status transaksi #' . $transaction->reference_number . ' berhasil diubah secara manual.');
    }

    /**
     * Hapus Transaksi.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $ref = $transaction->reference_number;
        $transaction->delete();

        return back()->with('success', 'Data transaksi #' . $ref . ' telah dihapus dari sistem.');
    }
}