@extends('layouts.user')

@section('title', 'Daftar Event')
@section('header_title', 'Daftar Event')
@section('header_subtitle', 'Pilih dan ikuti event menarik di kampus')

@section('styles')
<style>
    .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
    .event-card {
        background: white; border-radius: 16px; overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        transition: 0.3s; display: flex; flex-direction: column; text-decoration: none; color: inherit;
    }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .event-card-header {
        height: 120px; padding: 25px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white; display: flex; flex-direction: column; justify-content: flex-end;
    }
    .event-card-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 5px; }
    .event-card-body { padding: 25px; flex: 1; }
    .event-meta { display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 0.85rem; margin-bottom: 10px; }
    .event-meta i { color: #6366f1; width: 14px; }
</style>
@endsection

@section('content')
<div class="events-grid">
    @forelse($events as $event)
        <a href="{{ route('events.show', $event->id) }}" class="event-card">
            <div class="event-card-header">
                <div class="event-card-title">{{ $event->nama_event }}</div>
                <div style="font-size: 0.8rem; opacity: 0.9;">{{ $event->tanggal_event->format('d M Y') }}</div>
            </div>
            <div class="event-card-body">
                <div class="event-meta">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $event->lokasi }}</span>
                </div>
                <div class="event-meta">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Mulai dari Rp {{ number_format($event->ticketCategories->min('price') ?? 0, 0, ',', '.') }}</span>
                </div>
                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.6; margin-top: 15px;">
                    {{ \Illuminate\Support\Str::limit($event->deskripsi, 100) }}
                </p>
            </div>
            <div style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #6366f1;">LIHAT DETAIL</span>
                <i class="fas fa-arrow-right" style="color: #6366f1; font-size: 0.8rem;"></i>
            </div>
        </a>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; color: #94a3b8;">
            <i class="fas fa-calendar-times" style="font-size: 4rem; margin-bottom: 20px; display: block;"></i>
            <h3>Belum Ada Event</h3>
            <p>Nantikan event-event menarik selanjutnya!</p>
        </div>
    @endforelse
</div>
@endsection