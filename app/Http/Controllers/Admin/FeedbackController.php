<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = \App\Models\Feedback::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function exportPdf()
    {
        $feedbacks = \App\Models\Feedback::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.feedback_report', [
            'feedbacks' => $feedbacks,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Feedback-' . now()->format('Ymd') . '.pdf');
    }
}
