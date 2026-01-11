@extends('layouts.admin')

@section('title', 'Kategori Tiket - ' . $event->nama_event)
@section('header_title', 'Kategori Tiket')
@section('header_subtitle', 'Kelola kategori dan harga tiket untuk ' . $event->nama_event)

@section('styles')
<style>
    .controls { display: flex; justify-content: space-between; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { text-align: left; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; padding: 15px; border-bottom: 1px solid #f1f5f9; }
    td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; }
    .btn-add { background: var(--primary-blue); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; }
    .action-icons { display: flex; gap: 15px; color: #6366f1; }
    .delete-btn { background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; }
    
    .capacity-summary {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px;
    }
    .summary-card {
        background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #f1f5f9;
    }
    .summary-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
    .summary-value { font-size: 1.5rem; font-weight: 800; color: #1e293b; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="controls">
        <a href="{{ route('admin.events.index') }}" style="color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Event
        </a>
        <a href="{{ route('admin.events.categories.create', $event) }}" class="btn-add">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th>Harga</th>
                <th>Total Kuota</th>
                <th>Sisa Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #1e293b;">{{ $cat->name }}</div>
                    </td>
                    <td style="color: #64748b;">Rp {{ number_format($cat->price, 0, ',', '.') }}</td>
                    <td style="color: #64748b;">{{ $cat->quantity }}</td>
                    <td>
                        <span style="font-weight: 600; color: {{ $cat->remaining_stock > 0 ? '#10b981' : '#ef4444' }};">
                            {{ $cat->remaining_stock }}
                        </span>
                    </td>
                    <td>
                        <div class="action-icons">
                            <a href="{{ route('admin.events.categories.edit', [$event, $cat]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.events.categories.destroy', [$event, $cat]) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Belum ada kategori tiket untuk event ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
