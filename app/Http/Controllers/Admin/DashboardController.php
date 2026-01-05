<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Query statistik transaksi dari database
        $stats = [
            'total'   => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed'  => Transaction::whereIn('status', ['failed', 'expired', 'deny'])->count(),
        ];

        return view('dashboard.dashboard', compact('stats'));
    }
}
