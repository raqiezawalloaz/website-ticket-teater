@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('styles')
<style>
    /* Menggunakan style gabungan yang sudah rapi */
    .welcome-banner {
        background: linear-gradient(90deg, #2563eb 0%, #a855f7 100%);
        color: white; padding: 40px; border-radius: 16px; margin-bottom: 30px;
    }
    .stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;
    }
    .stat-card {
        background: white; padding: 20px; border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 12px -2px rgba(0,0,0,0.1); }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; margin-bottom: 15px; color: white;
    }
    .number { font-size: 1.8rem; font-weight: bold; color: #1e293b; }
    
    .content-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; }
    .panel {
        background: white; padding: 25px; border-radius: 12px;
        border: 1px solid #f1f5f9;
    }
    .event-item {
        background: #f8fafc; padding: 15px; border-radius: 10px;
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 10px; transition: all 0.2s ease;
    }
    .badge {
        background: #dcfce7; color: #166534; padding: 4px 12px;
        border-radius: 20px; font-size: 0.75rem;
    }
    a { text-decoration: none; color: inherit; }
</style>
@endsection

@section('content')
<div class="welcome-banner">
    <h1 style="font-size: 1.8rem; margin-bottom: 10px;">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p style="opacity: 0.9;">Pantau penjualan tiket teater dan status pembayaran real-time.</p>
</div>

{{-- Memasukkan Data Dinamis dari branch Galih ke dalam UI yang rapi --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #3b82f6;"><i class="fas fa-ticket-alt"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Total Transaksi</h4>
        <div class="number">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #22c55e;"><i class="fas fa-check-double"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Lunas</h4>
        <div class="number">{{ $stats['success'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #a855f7;"><i class="fas fa-clock"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Pending</h4>
        <div class="number">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Gagal</h4>
        <div class="number">{{ $stats['failed'] }}</div>
    </div>
</div>

<div class="content-grid">
    <div class="panel">
        <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Event Mendatang</h3>
        <div class="event-item">
            <div>
                <div style="font-weight: bold; margin-bottom: 5px;">Teater Romeo & Juliet 2025</div>
                <div style="font-size: 0.85rem; color: #64748b;">
                    <i class="far fa-clock"></i> 20 Jan 2025 &nbsp; <i class="far fa-user"></i> 120 Peserta
                </div>
            </div>
            <span class="badge">Coming Soon</span>
        </div>
        <div class="event-item">
            <div>
                <div style="font-weight: bold; margin-bottom: 5px;">Workshop Akting Dasar</div>
                <div style="font-size: 0.85rem; color: #64748b;">
                    <i class="far fa-clock"></i> 25 Jan 2025 &nbsp; <i class="far fa-user"></i> 45 Peserta
                </div>
            </div>
            <span class="badge">Coming Soon</span>
        </div>
    </div>

    <div class="panel">
        <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Aksi Cepat Admin</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('admin.transactions.index') }}">
                <div class="event-item" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                    <div>
                        <div style="font-weight: bold; color: #1e40af;">Kelola Transaksi</div>
                        <div style="font-size: 0.8rem; color: #64748b;">Verifikasi pembayaran & cek API</div>
                    </div>
                    <i class="fas fa-chevron-right" style="color: #1e40af;"></i>
                </div>
            </a>

            <a href="{{ route('admin.transactions.exportPdf') }}">
                <div class="event-item" style="background: #fff1f2; border: 1px solid #fecdd3;">
                    <div>
                        <div style="font-weight: bold; color: #be123c;">Laporan Penjualan (PDF)</div>
                        <div style="font-size: 0.8rem; color: #64748b;">Download laporan lengkap</div>
                    </div>
                    <i class="fas fa-file-pdf" style="color: #be123c;"></i>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection