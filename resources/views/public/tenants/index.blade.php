@extends('layouts.public')

@section('title', 'Daftar Tenant')

@section('styles')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    .card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    .card-body {
        padding: 2rem;
        text-align: center;
    }
    .icon-wrapper {
        width: 80px; height: 80px;
        background: #eff6ff;
        color: #0061ff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem auto;
        font-size: 2rem;
    }
    .card-title {
        font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;
    }
    .card-subtitle {
        color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;
    }
    .card-footer {
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
        color: #64748b;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="text-center mb-5">
    <h1 class="font-bold mb-4" style="font-size: 2.5rem;">Tenant Kami</h1>
    <p class="text-muted" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
        Jelajahi berbagai tenant menarik yang berpartisipasi dalam event kami. 
        Temukan produk dan layanan terbaik dari mitra kami.
    </p>
</div>

<div class="grid-container">
    @forelse($tenants as $tenant)
    <div class="card">
        <div class="card-body">
            <div class="icon-wrapper">
                <i class="fas fa-store"></i>
            </div>
            <h3 class="card-title">{{ $tenant->nama_tenant }}</h3>
            <p class="card-subtitle">{{ $tenant->jenis_usaha }}</p>
            <div class="card-footer">
                <i class="fas fa-phone-alt mr-2"></i> {{ $tenant->kontak }}
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
        <i class="fas fa-store-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
        <p class="text-muted">Belum ada data tenant.</p>
    </div>
    @endforelse
</div>
@endsection
