<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        return view('admin.sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('admin.sponsors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sponsor' => 'required',
            'jenis_sponsor' => 'required',
            'kontak' => 'required',
        ]);

        Sponsor::create($request->all());
        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sponsor' => 'required',
            'jenis_sponsor' => 'required',
            'kontak' => 'required',
        ]);

        Sponsor::findOrFail($id)->update($request->all());
        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil diperbarui');
    }

    public function destroy($id)
    {
        Sponsor::destroy($id);
        return redirect()->route('admin.sponsors.index')->with('success', 'Sponsor berhasil dihapus');
    }
}
