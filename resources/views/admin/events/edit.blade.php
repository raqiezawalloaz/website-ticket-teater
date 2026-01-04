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

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn-secondary">Batal</a>
        </div>
    </div>
</form>
@endsection