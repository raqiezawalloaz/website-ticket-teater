<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tenant' => 'required',
            'jenis_usaha' => 'required',
            'kontak'      => 'required',
        ]);

        Tenant::create($request->all());

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant berhasil ditambahkan');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'nama_tenant' => 'required',
            'jenis_usaha' => 'required',
            'kontak'      => 'required',
        ]);

        $tenant->update($request->all());

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant berhasil diperbarui');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant berhasil dihapus');
    }
}
