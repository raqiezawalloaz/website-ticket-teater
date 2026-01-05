@extends('layouts.admin')

@section('title', 'Buat Event Baru')
@section('header_title', 'Buat Event Baru')
@section('header_subtitle', 'Tambahkan event baru ke dalam sistem')

@section('styles')
<style>
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; color: #334155; }
    
    input, textarea, select {
        width: 100%; padding: 12px; border-radius: 8px;
        border: 1px solid #e2e8f0; background: #fff; font-size: 0.9rem;
    }

    input:focus, textarea:focus, select:focus {
        outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(0, 97, 255, 0.1);
    }

    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .btn-primary { 
        background: var(--primary-blue); color: white; border: none; 
        padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    .btn-primary:hover { background: #0052d4; }

    .btn-secondary {
        background: #f1f5f9; color: #475569; text-decoration: none;
        padding: 12px 25px; border-radius: 8px; font-weight: bold; margin-left: 10px;
        display: inline-block;
    }
    .btn-secondary:hover { background: #e2e8f0; }

    .alert-error {
        background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 15px;
        border-radius: 6px; margin-bottom: 20px; color: #991b1b;
    }
    .alert-error li { margin-left: 20px; }
</style>
@endsection

@section('content')
@if($errors->any())
    <div class="alert-error">
        <strong><i class="fas fa-exclamation-circle"></i> Terjadi kesalahan:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card">
        <h3 class="section-title">Informasi Dasar</h3>

        <div class="form-group">
            <label>Nama Event</label>
            <input type="text" name="nama_event" value="{{ old('nama_event') }}" placeholder="Masukkan nama event" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Event</label>
            <textarea name="deskripsi" rows="5" placeholder="Masukkan deskripsi event">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="row">
            <div class="form-group">
                <label>Tanggal & Waktu</label>
                <input type="datetime-local" name="tanggal_event" value="{{ old('tanggal_event') }}" required>
            </div>
            <div class="form-group">
                <label>Status Event</label>
                <select name="status_event" required>
                    <option value="">Pilih Status...</option>
                    <option value="aktif" {{ old('status_event') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status_event') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Lokasi / Link Meeting</label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi atau link meeting">
        </div>

        <div class="form-group">
            <label>Background Sertifikat (Opsional)</label>
            <input type="file" name="certificate_background" accept="image/*">
            <small style="color: #64748b; font-size: 0.8rem;">Upload gambar (png/jpg/jpeg) min 2MB. Kosongkan untuk menggunakan template default.</small>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn-secondary">Batal</a>
        </div>
    </div>
</form>
@endsection
