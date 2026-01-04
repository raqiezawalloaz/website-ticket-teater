<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Sponsor;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dashboard');
    }
}
