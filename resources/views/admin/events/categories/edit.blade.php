@extends('layouts.admin')

@section('title', 'Edit Kategori Tiket - ' . $event->nama_event)
@section('header_title', 'Edit Kategori')
@section('header_subtitle', 'Ubah detail kategori tiket untuk ' . $event->nama_event)

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
    <form action="{{ route('admin.events.categories.update', [$event, $category]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" required>
        </div>

        <div class="form-group">
            <label for="price">Harga (Rupiah)</label>
            <input type="number" name="price" id="price" value="{{ $category->price }}" required>
        </div>

        <div class="form-group">
            <label for="quantity">Total Kuota Tiket</label>
            <input type="number" name="quantity" id="quantity" value="{{ $category->quantity }}" required>
            <small style="color: #94a3b8; display: block; margin-top: 5px;">
                Saat ini tersisa: <strong>{{ $category->remaining_stock }}</strong> tiket.
            </small>
        </div>

        <div style="display: flex; align-items: center; gap: 20px; margin-top: 30px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Perbarui Kategori
            </button>
            <a href="{{ route('admin.events.categories.index', $event) }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
