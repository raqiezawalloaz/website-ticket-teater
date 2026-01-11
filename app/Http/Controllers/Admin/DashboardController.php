<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Event;     // Diambil dari branch fitur (penting untuk welcome & admin dashboard)
use App\Models\Tenant;
use App\Models\Sponsor;   // Diambil dari branch main (penting untuk admin dashboard)
use Illuminate\Support\Facades\Auth; // Diambil dari branch main

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
        // Menggunakan logic pengecekan role yang lebih aman dari branch main, tapi fallback ke manual check
        if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
            
            // Mengambil Logic Transaksi dari Branch Main (Fitur Galih) karena statusnya lebih lengkap (paid/settlement)
            $stats = [
                'total'   => Transaction::count(),
                'success' => Transaction::whereIn('status', ['success', 'paid', 'settlement'])->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'failed'  => Transaction::whereIn('status', ['failed', 'expired', 'deny', 'cancel'])->count(),
            ];

            // Tambahan data Tenant & Sponsor dari Branch Main
            $tenantCount = Tenant::count();
            $sponsorCount = Sponsor::count();

            // Tambahan data Upcoming Events dari Branch Fitur-Arrival (agar dashboard tidak kosong)
            $upcomingEvents = Event::where('tanggal_event', '>', now())
                ->orderBy('tanggal_event', 'asc')
                ->take(5)
                ->get();

            return view('dashboard.dashboard', compact('stats', 'upcomingEvents', 'tenantCount', 'sponsorCount'));
        }

        // 2. DATA UNTUK USER BIASA
        // Menggunakan Logic dari Branch Fitur-Arrival karena lebih lengkap (ada available events & stats user)
        $myStats = [
            'total_tickets' => Transaction::where('user_id', $user->id)->whereIn('status', ['success', 'paid', 'settlement'])->count(), // Update status agar match dengan logic admin
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

        // Pastikan nama view sesuai dengan struktur folder terbaru kamu. 
        // Saya gunakan dashboard.user_dashboard (dari branch fitur)
        return view('dashboard.user_dashboard', compact('myStats', 'myTransactions', 'availableEvents'));
    }
}