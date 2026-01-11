<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\Tenant;

class DashboardController extends Controller
{
    public function welcome()
    {
        $stats = [
            'events' => Event::count(),
            'tickets' => Transaction::where('status', 'success')->sum('quantity'),
            'tenants' => Tenant::count(),
        ];

        return view('welcome', compact('stats'));
    }

    public function index()
    {
        $user = auth()->user();

        // 1. DATA UNTUK ADMIN
        if ($user->role === 'admin') {
            $stats = [
                'total'   => Transaction::count(),
                'success' => Transaction::where('status', 'success')->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'failed'  => Transaction::whereIn('status', ['failed', 'expired', 'deny'])->count(),
            ];

            $upcomingEvents = Event::where('tanggal_event', '>', now())
                ->orderBy('tanggal_event', 'asc')
                ->take(5)
                ->get();

            return view('dashboard.dashboard', compact('stats', 'upcomingEvents'));
        }

        // 2. DATA UNTUK USER BIASA
        $myStats = [
            'total_tickets' => Transaction::where('user_id', $user->id)->where('status', 'success')->count(),
            'pending_payments' => Transaction::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        $myTransactions = Transaction::where('user_id', $user->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $availableEvents = Event::where('status_event', 'aktif')
            ->where('tanggal_event', '>', now())
            ->orderBy('tanggal_event', 'asc')
            ->take(3)
            ->get();

        return view('dashboard.user_dashboard', compact('myStats', 'myTransactions', 'availableEvents'));
    }
}
