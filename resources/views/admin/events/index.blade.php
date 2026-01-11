@extends('layouts.admin')

@section('title', 'Manajemen Event')
@section('header_title', 'Manajemen Event')
@section('header_subtitle', 'Kelola event, jadwal, dan status acara.')

@section('styles')
<style>
    .controls { display: flex; justify-content: space-between; margin-bottom: 20px; gap: 15px; }
    .search-box { flex: 1; position: relative; }
    .search-box input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; border: 1px solid #e2e8f0; }
    .search-box i { position: absolute; left: 15px; top: 12px; color: #94a3b8; }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { text-align: left; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; padding: 15px; border-bottom: 1px solid #f1f5f9; }
    td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; }

    .btn-add { background: var(--primary-blue); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; }
    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; }
    .status-success { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }

    .action-icons { display: flex; gap: 15px; color: #6366f1; }
    .action-icons i { cursor: pointer; }
    .delete-btn { background: none; border: none; color: #ef4444; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="controls">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari event...">
        </div>
        <select style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; color: #64748b;">
            <option>Semua Status</option>
        </select>
        <a href="{{ route('admin.events.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> Buat Event Baru
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Event</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Kapasitas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #1e293b;">{{ $event->nama_event }}</div>
                    </td>
                    <td style="color: #64748b;">{{ $event->tanggal_event->format('d M Y, H:i') }}</td>
                    <td style="color: #64748b;">{{ $event->lokasi }}</td>
                    <td style="color: #64748b;">
                        @foreach($event->ticketCategories as $cat)
                            <div style="font-size: 0.75rem; white-space: nowrap; margin-bottom: 2px;">
                                <i class="fas fa-ticket-alt" style="color: #6366f1; width: 14px;"></i> 
                                {{ $cat->name }}: 
                                <strong style="color: {{ $cat->remaining_stock < 10 ? '#ef4444' : '#1e293b' }};">
                                    {{ number_format($cat->remaining_stock, 0, ',', '.') }}
                                </strong> 
                                <span style="color: #94a3b8;">/ {{ number_format($cat->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        @if($event->ticketCategories->isEmpty())
                            <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Belum ada tiket</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ strtolower($event->status_event) == 'aktif' ? 'status-success' : 'status-pending' }}">
                            <i class="fas {{ strtolower($event->status_event) == 'aktif' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ ucfirst($event->status_event) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-icons">
                            <a href="{{ route('admin.events.show', $event) }}" title="Lihat Detail & Ulasan"><i class="fas fa-eye" style="color: #64748b;"></i></a>
                            <a href="{{ route('admin.events.edit', $event) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('admin.events.categories.index', $event) }}" title="Kategori"><i class="fas fa-list"></i></a>
                            <a href="{{ route('admin.events.export', $event) }}" title="Export"><i class="fas fa-download" style="color: #10b981;"></i></a>
                            
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event?')" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; color: #94a3b8;">Belum ada data event tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection