@extends('layouts.user')

@section('title', 'Dashboard Saya')
@section('header_title', 'Dashboard Saya')
@section('header_subtitle', 'Kelola tiket dan pantau riwayat transaksi Anda.')

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(90deg, #6366f1 0%, #a855f7 100%);
        color: white; padding: 30px; border-radius: 16px; margin-bottom: 30px;
    }
    .stats-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;
    }
    .stat-card {
        background: white; padding: 20px; border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
    }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; margin-bottom: 15px; color: white;
    }
    .number { font-size: 1.8rem; font-weight: bold; color: #1e293b; }
    
    .panel-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; }
    .panel { background: white; padding: 25px; border-radius: 12px; border: 1px solid #f1f5f9; }
    
    .list-item {
        background: #f8fafc; padding: 15px; border-radius: 10px;
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px; border: 1px solid transparent; transition: 0.2s;
    }
    .list-item:hover { border-color: #e2e8f0; transform: translateX(5px); }
    
    .status-badge {
        padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
    }
    .status-success { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-failed { background: #fee2e2; color: #991b1b; }

    .btn-action {
        display: inline-block; padding: 8px 16px; background: #6366f1; color: white;
        border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="welcome-banner">
    <h1 style="font-size: 1.5rem; margin-bottom: 8px;">Halo, {{ Auth::user()->name }}!</h1>
    <p style="opacity: 0.9;">Senang melihat Anda kembali. Siap untuk menonton pertunjukan teater berikutnya?</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #22c55e;"><i class="fas fa-ticket-alt"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Tiket Aktif</h4>
        <div class="number">{{ $myStats['total_tickets'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f59e0b;"><i class="fas fa-hourglass-half"></i></div>
        <h4 style="color: #64748b; font-size: 0.85rem;">Menunggu Pembayaran</h4>
        <div class="number">{{ $myStats['pending_payments'] }}</div>
    </div>
</div>

<div class="panel-grid">
    <!-- Riwayat Transaksi -->
    <div class="panel">
        <h3 style="margin-bottom: 20px; font-size: 1.1rem; display: flex; justify-content: space-between; align-items: center;">
            Transaksi Terakhir
            <a href="{{ route('user.tickets.index') }}" style="font-size: 0.8rem; color: #6366f1; text-decoration: none;">Lihat Semua</a>
        </h3>
        
        @forelse($myTransactions as $trx)
            <div class="list-item">
                <div>
                    <div style="font-weight: bold; margin-bottom: 4px;">{{ $trx->event->nama_event ?? $trx->event_name }}</div>
                    <div style="font-size: 0.8rem; color: #64748b;">
                        {{ $trx->created_at->format('d M Y') }} • {{ $trx->ticket_category }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</div>
                    <span class="status-badge status-{{ $trx->status }}" style="display: block; width: fit-content; margin-left: auto;">
                        {{ ucfirst($trx->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 30px; color: #94a3b8;">
                <i class="fas fa-shopping-cart" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                Belum ada transaksi pembelian tiket.
            </div>
        @endforelse
    </div>

    <!-- Event Rekomendasi -->
    <div class="panel">
        <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Eksplorasi Event</h3>
        @foreach($availableEvents as $event)
            <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                <div style="font-weight: bold; margin-bottom: 5px;">{{ $event->nama_event }}</div>
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 10px;">
                    <i class="far fa-calendar-alt"></i> {{ $event->tanggal_event->format('d M Y') }}
                </div>
                <a href="{{ route('events.show', $event) }}" class="btn-action">Detail & Beli</a>
            </div>
        @endforeach
        <a href="{{ route('events.index') }}" style="display: block; text-align: center; color: #6366f1; text-decoration: none; font-size: 0.9rem; margin-top: 10px;">
            Lihat Semua Event <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
@endsection
