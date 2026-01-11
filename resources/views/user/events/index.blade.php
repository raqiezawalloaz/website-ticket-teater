@extends('layouts.app')

@section('title', 'Daftar Event')
@section('header_title', 'Daftar Event')
@section('header_subtitle', 'Pilih dan ikuti event menarik di kampus')

@section('styles')
<style>
    /* Menggunakan Grid agar responsif */
    .events-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
        gap: 30px; 
    }

    .event-card {
        background: white; 
        border-radius: 16px; 
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        border: 1px solid #f1f5f9;
        transition: 0.3s; 
        display: flex; 
        flex-direction: column; 
        text-decoration: none; 
        color: inherit;
        position: relative;
    }

    .event-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); 
    }

    .event-card-header {
        height: 120px; 
        padding: 25px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); /* Gradient Ungu Khas Kampus */
        color: white; 
        display: flex; 
        flex-direction: column; 
        justify-content: flex-end;
    }

    .event-card-title { 
        font-size: 1.25rem; 
        font-weight: 800; 
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .event-card-body { 
        padding: 25px; 
        flex: 1; 
        display: flex;
        flex-direction: column;
    }

    .event-meta { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        color: #64748b; 
        font-size: 0.9rem; 
        margin-bottom: 10px; 
    }
    
    .event-meta i { 
        color: #6366f1; 
        width: 16px; 
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="events-grid">
    @forelse($events as $event)
        <a href="{{ route('events.show', $event->id) }}" class="event-card">
            <!-- Header Kartu -->
            <div class="event-card-header">
                <div class="event-card-title">{{ $event->nama_event }}</div>
                <div style="font-size: 0.85rem; opacity: 0.9;">
                    <i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($event->tanggal_event)->format('d M Y, H:i') }} WIB
                </div>
            </div>

            <!-- Body Kartu -->
            <div class="event-card-body">
                <div class="event-meta">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $event->lokasi ?? 'Online / Lokasi Menyusul' }}</span>
                </div>
                
                <div class="event-meta">
                    <i class="fas fa-ticket-alt"></i>
                    @if($event->ticketCategories->count() > 0)
                        <span>Mulai Rp {{ number_format($event->ticketCategories->min('price'), 0, ',', '.') }}</span>
                    @else
                        <span>Gratis / Info Menyusul</span>
                    @endif
                </div>

                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.6; margin-top: 15px; flex-grow: 1;">
                    {{ \Illuminate\Support\Str::limit($event->deskripsi, 90) }}
                </p>
            </div>

            <!-- Footer Kartu -->
            <div style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #6366f1;">LIHAT DETAIL</span>
                <i class="fas fa-arrow-right" style="color: #6366f1; font-size: 0.8rem;"></i>
            </div>
        </a>
    @empty
        <!-- State Kosong (Empty State) -->
        <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px; color: #94a3b8; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 20px; color: #cbd5e1; display: block;"></i>
            <h3 style="color: #334155; margin-bottom: 5px;">Belum Ada Event Aktif</h3>
            <p>Nantikan event-event seru selanjutnya!</p>
        </div>
    @endforelse
</div>
@endsection