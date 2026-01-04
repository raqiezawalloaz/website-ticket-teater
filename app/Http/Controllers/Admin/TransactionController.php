<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * FITUR API & UPDATE: Sinkronisasi Status
     */
    public function checkStatus($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            // Controller cukup panggil satu baris ini. Sangat Clean!
            $newStatus = $this->midtransService->getLatestStatus($transaction->reference_number);

            $transaction->update(['status' => $newStatus]);

            return back()->with('success', "Update Berhasil: Tiket kini berstatus " . strtoupper($newStatus));

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * FITUR CRUD (Read) & RELASI: Daftar Transaksi
     */
    public function index()
    {
        // Syarat Tubes: Eager Loading relasi (User & Event)
        $transactions = Transaction::with(['user', 'event'])->latest()->paginate(10);

        $stats = [
            'total'   => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed'  => Transaction::whereIn('status', ['failed', 'expired', 'deny'])->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * FITUR CRUD (Create) & VALIDASI
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
        ]);

        try {
            // Harga statis atau dinamis dari event
            $total = 50000 * $request->quantity;

            $transaction = Transaction::create([
                'user_id'          => Auth::id(), // Relasi 1
                'event_id'         => $request->event_id, // Relasi 2
                'reference_number' => 'TRX-' . time(),
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'ticket_code'      => 'TKT-' . strtoupper(Str::random(6)),
                'price_per_ticket' => 50000,
                'quantity'         => $request->quantity,
                'total_price'      => $total,
                'status'           => 'pending',
                'is_checked_in'    => false,
            ]);

            $snapToken = $this->midtransService->getSnapToken($transaction);
            $transaction->update(['snap_token' => $snapToken]);

            return view('user.payment_page', compact('snapToken', 'transaction'));

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * FITUR CETAK: PDF
     */
    public function exportPdf()
    {
        $transactions = Transaction::with(['user', 'event'])->get();
        $pdf = Pdf::loadView('admin.transactions.report', compact('transactions'));
        return $pdf->download('Laporan-Tiket.pdf');
    }

    /**
     * FITUR CRUD: Delete
     */
    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();
        return back()->with('success', 'Transaksi dihapus.');
    }

    public function checkIn($id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status !== 'success') return back()->with('error', 'Belum lunas!');
        $transaction->update(['is_checked_in' => true]);
        return back()->with('success', 'Check-in berhasil!');
    }
}