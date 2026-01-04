@extends('layouts.admin')

@section('title', 'Manajemen Mitra')
@section('header_title', 'Manajemen Mitra')
@section('header_subtitle', 'Kelola daftar tenant dan sponsor pendukung acara')

@section('styles')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 25px;
    }

    .partner-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        position: relative;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .partner-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        border-color: var(--primary-blue);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: #eff6ff;
        color: var(--primary-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .card-subtitle {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 15px;
    }

    .card-contact {
        background: #f8fafc;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }

    .btn-action {
        flex: 1;
        padding: 8px;
        border-radius: 8px;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-edit { background: #eff6ff; color: var(--primary-blue); }
    .btn-edit:hover { background: #dbeafe; }

    .btn-delete { background: #fef2f2; color: #ef4444; border: none; cursor: pointer; }
    .btn-delete:hover { background: #fee2e2; }

    .add-btn {
        background: linear-gradient(135deg, #0061ff 0%, #6366f1 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        transition: 0.3s;
    }
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }
</style>
@endsection

@section('content')

<!-- Navigasi Tab Local -->
<div class="nav-tabs">
    <a href="{{ route('admin.tenants.index') }}" class="nav-tab active">Tenant</a>
    <a href="{{ route('admin.sponsors.index') }}" class="nav-tab">Sponsor</a>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <h3 style="color: #1e293b; font-size: 1.3rem;">Daftar Tenant</h3>
        <p style="color: #64748b; font-size: 0.9rem;">Mitra yang menyewa booth di event</p>
    </div>
    <a href="{{ route('admin.tenants.create') }}" class="add-btn">
        <i class="fas fa-plus"></i> Tambah Tenant
    </a>
</div>

<div class="grid-container">
    @forelse($tenants as $tenant)
        <div class="partner-card">
            <div>
                <div class="card-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="card-title">{{ $tenant->nama_tenant }}</div>
                <div class="card-subtitle"><i class="fas fa-tag" style="margin-right: 5px; font-size: 0.8rem;"></i> {{ $tenant->jenis_usaha }}</div>
                
                <div class="card-contact">
                    <i class="fas fa-phone-alt"></i> {{ $tenant->kontak }}
                </div>
            </div>

            <div class="card-actions">
                <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('Yakin ingin menghapus?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #94a3b8; font-size: 2rem;">
                <i class="fas fa-store-slash"></i>
            </div>
            <h3 style="color: #475569; margin-bottom: 10px;">Belum ada Data Tenant</h3>
            <p style="color: #94a3b8; margin-bottom: 20px;">Tambahkan tenant baru untuk mulai mengelola kemitraan.</p>
            <a href="{{ route('admin.tenants.create') }}" class="add-btn" style="display: inline-flex;">Tambah Tenant Sekarang</a>
        </div>
    @endforelse
</div>

@endsection
