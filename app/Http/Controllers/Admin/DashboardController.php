<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Sponsor;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return view('dashboard.dashboard');
        }

        // Get User Transactions for "My Events"
        $transactions = \App\Models\Transaction::where('user_id', auth()->id())
            ->with(['event', 'event.feedbacks' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', compact('transactions'));
    }
}
