<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class CertificateController extends Controller
{
    /**
     * Display all events with certificate statistics
     */
    public function index()
    {
        $events = Event::withCount(['transactions' => function($query) {
            $query->where('status', 'success');
        }])
        ->orderBy('tanggal_event', 'desc')
        ->get();

        return view('admin.certificates.index', compact('events'));
    }

    /**
     * Show certificate management for a specific event
     */
    public function show(Event $event)
    {
        $transactions = Transaction::where('event_id', $event->id)
            ->where('status', 'success')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.certificates.show', compact('event', 'transactions'));
    }

    /**
     * Download certificate for a specific user/transaction (Admin POV)
     */
    public function download($transactionId)
    {
        $transaction = Transaction::with(['event', 'user'])
            ->where('id', $transactionId)
            ->where('status', 'success')
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', [
            'transaction' => $transaction,
            'user' => $transaction->user,
            'event' => $transaction->event,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat-' . $transaction->user->name . '-' . $transaction->event->nama_event . '.pdf');
    }
}
