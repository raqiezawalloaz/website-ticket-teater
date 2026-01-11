@extends('layouts.admin')

@section('title', 'Edit Event')
@section('header_title', 'Edit Event')
@section('header_subtitle', 'Perbarui informasi detail event Anda')

@section('styles')
<style>
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; color: #334155; }
    
    input, textarea, select {
        width: 100%; padding: 12px; border-radius: 8px;
        border: 1px solid #e2e8f0; background: #fff; font-size: 0.9rem;
    }

    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .btn-primary { 
        background: var(--primary-blue); color: white; border: none; 
        padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: #f1f5f9; color: #475569; text-decoration: none;
        padding: 12px 25px; border-radius: 8px; font-weight: bold; margin-left: 10px;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<form action="{{ route('admin.events.update', $event->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <h3 class="section-title">Informasi Dasar</h3>

        <div class="form-group">
            <label>Nama Event</label>
            <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Event</label>
            <textarea name="deskripsi" rows="5">{{ old('deskripsi', $event->deskripsi) }}</textarea>
        </div>

        <div class="row">
            <div class="form-group">
                <label>Tanggal & Waktu</label>
                <input type="datetime-local" name="tanggal_event" 
                       value="{{ old('tanggal_event', date('Y-m-d\TH:i', strtotime($event->tanggal_event))) }}" required>
            </div>
            <div class="form-group">
                <label>Status Event</label>
                <select name="status_event">
                    <option value="aktif" {{ $event->status_event == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $event->status_event == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Lokasi / Link Meeting</label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}">
        </div>

        <div class="form-group">
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.8rem; color: #64748b; font-weight: bold; margin-bottom: 8px; text-transform: uppercase;">Rincian Kuota Terdaftar:</div>
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    @php $allocated = 0; @endphp
                    @forelse($event->ticketCategories as $cat)
                        <div style="font-size: 0.9rem; color: #1e293b; background: white; padding: 8px 12px; border-radius: 6px; border: 1px solid #f1f5f9;">
                            <i class="fas fa-ticket-alt" style="color: #6366f1;"></i> {{ $cat->name }}: 
                            <strong style="color: {{ $cat->remaining_stock < 10 ? '#ef4444' : '#1e293b' }};">{{ $cat->remaining_stock }}</strong>
                            <span style="color: #94a3b8; font-size: 0.8rem;">/ {{ $cat->quantity }} Tersedia</span>
                        </div>
                        @php $allocated += $cat->quantity; @endphp
                    @empty
                        <div style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">Belum ada kategori tiket dibuat.</div>
                    @endforelse
                </div>
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: 0.85rem; color: #64748b;">
                    Total Kapasitas Saat Ini: <strong>{{ $allocated }}</strong>
                    <a href="{{ route('admin.events.categories.index', $event->id) }}" style="float: right; color: #6366f1; text-decoration: none; font-weight: 600;">
                        Kelola Kategori <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn-secondary">Batal</a>
        </div>
    </div>
</form>
@endsection