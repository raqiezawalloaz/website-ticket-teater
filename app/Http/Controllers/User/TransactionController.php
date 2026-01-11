<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi user yang login
     */
    public function index(Request $request)
    {
        // Ambil transaksi milik user yang login, diurutkan dari yang terbaru
        $query = Transaction::where('user_id', Auth::id())
                        ->with('event');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(9);

        return view('user.transactions.index', compact('transactions'));
    }

    /**
     * Tampilkan detail transaksi spesifik
     */
    public function show($id)
    {
        // Pastikan user hanya bisa lihat transaksi mereka sendiri (Security Check)
        $transaction = Transaction::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->with('event')
                                ->firstOrFail();

        return view('user.transactions.show', compact('transaction'));
    }

    /**
     * Download E-Tiket PDF
     * Hanya bisa didownload jika status = paid (sudah bayar)
     */
    public function downloadTicket($id)
    {
        // Validasi: User hanya bisa download tiket milik mereka sendiri
        $transaction = Transaction::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->with('event')
                                ->firstOrFail();

        /** 
         * Catatan Senior: Sesuaikan status di bawah ini. 
         * Jika di database Anda menggunakan 'paid', maka gunakan 'paid'. 
         * Jika menggunakan 'success', ubah menjadi 'success'.
         */
        if ($transaction->status !== 'paid' && $transaction->status !== 'success') {
            return back()->with('error', 'Tiket belum tersedia. Selesaikan pembayaran Anda terlebih dahulu.');
        }

        // Generate PDF E-Tiket menggunakan view spesifik
        // Pastikan Anda sudah membuat file: resources/views/user/transactions/ticket-pdf.blade.php
        $pdf = Pdf::loadView('user.transactions.ticket-pdf', compact('transaction'));

        // Atur ukuran kertas (opsional, misal: A4 atau landscape)
        $pdf->setPaper('a4', 'portrait');

        // Download file dengan nama yang rapi
        $fileName = "E-TIKET-" . str_replace(' ', '-', $transaction->event_name) . "-" . $transaction->reference_number . ".pdf";
        
        return $pdf->download($fileName);
    }
} // Penutup Class