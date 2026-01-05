@extends('layouts.admin')

@section('title', 'Manajemen Sertifikat')
@section('header_title', 'Manajemen Sertifikat')
@section('header_subtitle', 'Kelola dan monitor sertifikat peserta event')

@section('styles')
<style>
    .event-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border-color: var(--primary-blue);
    }
    .event-info h3 {
        margin: 0 0 8px 0;
        color: #1e293b;
        font-size: 1.2rem;
    }
    .event-meta {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 5px;
    }
    .stats {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .stat-box {
        text-align: center;
        padding: 10px 20px;
        background: #f8fafc;
        border-radius: 8px;
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--primary-blue);
    }
    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
    }
    .btn-manage {
        background: var(--primary-blue);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-manage:hover {
        background: #0052d4;
    }
</style>
@endsection

@section('content')

<!-- Navigation Tabs -->
<div class="nav-tabs">
    <a href="{{ route('admin.certificates.index') }}" class="nav-tab active">Kelola Sertifikat</a>
    <a href="{{ route('admin.feedbacks.index') }}" class="nav-tab">Data Feedback</a>
</div>

<div class="card">
    <h3 style="margin-bottom: 20px; color: #1e293b;">Event dengan Sertifikat</h3>

    @forelse($events as $event)
        <div class="event-card">
            <div class="event-info">
                <h3>{{ $event->nama_event }}</h3>
                <div class="event-meta">
                    <i class="far fa-calendar"></i> {{ $event->tanggal_event->format('d F Y') }}
                </div>
                <div class="event-meta">
                    <i class="fas fa-map-marker-alt"></i> {{ $event->lokasi ?? 'Online' }}
                </div>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <div class="stat-number">{{ $event->transactions_count ?? 0 }}</div>
                    <div class="stat-label">Peserta</div>
                </div>

                <a href="{{ route('admin.certificates.show', $event->id) }}" class="btn-manage">
                    <i class="fas fa-certificate"></i> Kelola Sertifikat
                </a>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="far fa-file-alt" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <p>Belum ada event dengan peserta.</p>
        </div>
    @endforelse
</div>
@endsection
