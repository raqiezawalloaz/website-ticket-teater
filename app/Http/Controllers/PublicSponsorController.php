<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;

class PublicSponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        return view('public.sponsors.index', compact('sponsors'));
    }
}
