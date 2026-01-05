<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function download(Request $request, $transactionId)
    {
        $transaction = \App\Models\Transaction::with(['event', 'user'])
            ->where('id', $transactionId)
            ->where('user_id', auth()->id())
            ->where('status', 'success') // Assuming success is the payed status
            ->firstOrFail();

        // Check if event has passed (optional, usually certificates are for past events)
        // if ($transaction->event->tanggal_event > now()) {
        //    return back()->with('error', 'Sertifikat belum tersedia.');
        // }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', [
            'transaction' => $transaction,
            'user' => auth()->user(),
            'event' => $transaction->event,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat-' . $transaction->event->nama_event . '.pdf');
    }
}
