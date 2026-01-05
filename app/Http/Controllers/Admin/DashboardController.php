<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Tenant;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. LOGIC UNTUK ADMIN
        if (auth()->user()->isAdmin()) { // atau gunakan role === 'admin' sesuai sistem Anda
            
            // Query statistik transaksi untuk dashboard admin (Fitur Galih)
            $stats = [
                'total'   => Transaction::count(),
                'success' => Transaction::whereIn('status', ['success', 'paid', 'settlement'])->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'failed'  => Transaction::whereIn('status', ['failed', 'expired', 'deny', 'cancel'])->count(),
            ];

            // Tambahkan stats lain jika perlu (Tenant/Sponsor dari branch main)
            $tenantCount = Tenant::count();
            $sponsorCount = Sponsor::count();

            return view('dashboard.dashboard', compact('stats', 'tenantCount', 'sponsorCount'));
        }

        // 2. LOGIC UNTUK USER (My Events / My Tickets)
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['event', 'event.feedbacks' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', compact('transactions'));
    }
}