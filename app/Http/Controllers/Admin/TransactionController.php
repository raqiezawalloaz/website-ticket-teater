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
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
        ]);

        try {
            $category = \App\Models\TicketCategory::findOrFail($request->ticket_category_id);
            $event = \App\Models\Event::findOrFail($request->event_id);
            
            // 1. Validasi Stok Kategori Tiket
            if ($category->remaining_stock < $request->quantity) {
                return back()->with('error', "Maaf, stok tiket {$category->name} tidak mencukupi. Tersisa {$category->remaining_stock} tiket.");
            }

            // 2. Validasi Kapasitas Total Event (Hanya jika diatur Admin)
            if ($event->total_capacity !== null && $event->total_capacity > 0) {
                $totalSold = Transaction::where('event_id', $event->id)
                    ->whereIn('status', ['success', 'pending'])
                    ->sum('quantity');
                
                if (($totalSold + $request->quantity) > $event->total_capacity) {
                    $available = max(0, $event->total_capacity - $totalSold);
                    return back()->with('error', "Maaf, kapasitas acara ini sudah penuh. Sisa kapasitas total: {$available} slot.");
                }
            }

            $total = $category->price * $request->quantity;

            $transaction = Transaction::create([
                'user_id'          => Auth::id(),
                'event_id'         => $request->event_id,
                'ticket_category_id' => $request->ticket_category_id,
                'reference_number' => 'TRX-' . time() . '-' . rand(100, 999),
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'event_name'       => $event->nama_event,
                'ticket_category'  => $category->name,
                'ticket_code'      => 'TKT-' . strtoupper(Str::random(6)),
                'price_per_ticket' => $category->price,
                'quantity'         => $request->quantity,
                'total_price'      => $total,
                'status'           => 'pending',
                'is_checked_in'    => false,
            ]);

            try {
                $snapToken = $this->midtransService->getSnapToken($transaction);
                $transaction->update(['snap_token' => $snapToken]);
                return view('user.payment_page', compact('snapToken', 'transaction'));
            } catch (Exception $midtransError) {
                // Jika Midtrans Error (misal 401), arahkan ke simulator sebagai fallback pengujian
                return redirect()->route('payment.simulate', $transaction->id)
                    ->with('error', 'Gagal inisiasi Midtrans (Error 401). Anda dialihkan ke simulator untuk pengujian.');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
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

    public function mockPayment($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'success']);
        
        return back()->with('success', "Simulasi Pembayaran Berhasil! Transaksi #{$transaction->reference_number} kini berstatus LUNAS.");
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

    /**
     * FITUR SIMULASI USER (Bypass Midtrans)
     */
    public function showSimulation($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        
        if ($transaction->status !== 'pending') {
            return redirect()->route('user.tickets.index')->with('error', 'Transaksi ini sudah selesai atau tidak dapat disimulasikan.');
        }

        return view('user.simulate_payment', compact('transaction'));
    }

    public function processSimulation(Request $request)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($request->transaction_id);
        
        if ($transaction->status === 'pending') {
            $transaction->update(['status' => 'success']);
            return redirect()->route('user.tickets.index')->with('success', 'Pembayaran Simulasi Berhasil! Tiket Anda kini aktif.');
        }

        return redirect()->route('user.tickets.index')->with('error', 'Gagal memproses simulasi.');
    }

    /**
     * FITUR USER: Riwayat Tiket Saya
     */
    public function myTickets()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('event')
            ->latest()
            ->paginate(6);

        return view('user.tickets.index', compact('transactions'));
    }

    /**
     * FITUR USER: Download E-Ticket PDF
     */
    public function downloadTicket($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('status', 'success')
            ->findOrFail($id);

        $pdf = Pdf::loadView('user.tickets.pdf', compact('transaction'));
        
        return $pdf->download("Tiket-{$transaction->ticket_code}.pdf");
    }

    /**
     * FITUR USER: Batalkan Pesanan Pending
     */
    public function cancel($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $transaction->delete();
        
        return redirect()->route('user.tickets.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
