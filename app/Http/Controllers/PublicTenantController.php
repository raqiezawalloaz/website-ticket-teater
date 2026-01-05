<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class PublicTenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('public.tenants.index', compact('tenants'));
    }
}
