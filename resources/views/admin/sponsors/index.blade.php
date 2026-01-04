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

    .card-header-icon {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: #f0fdf4;
        color: #16a34a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .sponsor-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-gold { background: #fefce8; color: #a16207; border: 1px solid #fef08a; }
    .badge-silver { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .badge-bronze { background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5; }
    .badge-default { background: #f1f5f9; color: #475569; }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .card-contact {
        background: #f8fafc;
        padding: 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }

    .btn-action {
        flex: 1;
        padding: 9px;
        border-radius: 8px;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-edit { background: white; border: 1px solid #e2e8f0; color: #475569; }
    .btn-edit:hover { background: #f8fafc; border-color: #cbd5e1; }

    .btn-delete { background: #fff1f2; color: #e11d48; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ffe4e6; }

    .add-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        transition: 0.3s;
    }
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }
</style>
@endsection

@section('content')

<!-- Navigasi Tab Local -->
<div class="nav-tabs">
    <a href="{{ route('admin.tenants.index') }}" class="nav-tab">Tenant</a>
    <a href="{{ route('admin.sponsors.index') }}" class="nav-tab active">Sponsor</a>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
    <div>
        <h3 style="color: #1e293b; font-size: 1.3rem;">Daftar Sponsor</h3>
        <p style="color: #64748b; font-size: 0.9rem;">Mitra pendanaan dan dukungan event</p>
    </div>
    <a href="{{ route('admin.sponsors.create') }}" class="add-btn">
        <i class="fas fa-plus"></i> Tambah Sponsor
    </a>
</div>

<div class="grid-container">
    @forelse($sponsors as $sponsor)
        @php
            $badgeClass = 'badge-default';
            $level = strtolower($sponsor->jenis_sponsor ?? '');
            if(str_contains($level, 'gold') || str_contains($level, 'emas')) $badgeClass = 'badge-gold';
            elseif(str_contains($level, 'silver') || str_contains($level, 'perak')) $badgeClass = 'badge-silver';
            elseif(str_contains($level, 'bronze') || str_contains($level, 'perunggu')) $badgeClass = 'badge-bronze';
        @endphp

        <div class="partner-card">
            <div>
                <div class="card-header-icon">
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <span class="sponsor-badge {{ $badgeClass }}">
                        {{ $sponsor->jenis_sponsor ?? 'Sponsor' }}
                    </span>
                </div>
                
                <div class="card-title">{{ $sponsor->nama_sponsor }}</div>
                
                <div class="card-contact">
                    <i class="fas fa-address-book"></i> {{ $sponsor->kontak }}
                </div>
            </div>

            <div class="card-actions">
                <a href="{{ route('admin.sponsors.edit', $sponsor->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <h3 style="color: #475569; margin-bottom: 10px;">Belum ada Data Sponsor</h3>
            <p style="color: #94a3b8; margin-bottom: 20px;">Tambahkan sponsor baru untuk mendukung event Anda.</p>
            <a href="{{ route('admin.sponsors.create') }}" class="add-btn" style="display: inline-flex;">Tambah Sponsor Sekarang</a>
        </div>
    @endforelse
</div>

@endsection
