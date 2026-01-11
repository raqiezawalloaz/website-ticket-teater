@extends('layouts.admin')

@section('title', 'Tambah Kategori Tiket - ' . $event->nama_event)
@section('header_title', 'Tambah Kategori')
@section('header_subtitle', 'Buat kategori tiket baru untuk ' . $event->nama_event)

@section('styles')
<style>
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; color: #64748b; font-size: 0.9rem; font-weight: 500; }
    input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 1rem; }
    input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .btn-submit { background: var(--primary-blue); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-cancel { color: #64748b; text-decoration: none; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.events.categories.store', $event) }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Nama Kategori (Contoh: VIP, Early Bird, Reguler)</label>
            <input type="text" name="name" id="name" required placeholder="Masukkan nama kategori...">
        </div>

        <div class="form-group">
            <label for="price">Harga (Rupiah)</label>
            <input type="number" name="price" id="price" required placeholder="Contoh: 50000">
        </div>

        <div class="form-group">
            <label for="quantity">Kuota Tiket (Tersedia)</label>
            <input type="number" name="quantity" id="quantity" required placeholder="Contoh: 100">
        </div>

        <div style="display: flex; align-items: center; gap: 20px; margin-top: 30px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Kategori
            </button>
            <a href="{{ route('admin.events.categories.index', $event) }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
