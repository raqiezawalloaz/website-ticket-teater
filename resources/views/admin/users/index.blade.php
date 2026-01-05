@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Kelola Pengguna')
@section('header_subtitle', 'Kelola daftar pengguna sistem')

@section('styles')
<style>
    .btn-primary { 
        background: var(--primary-blue); color: white; border: none; 
        padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    .btn-primary:hover { background: #0052d4; }
    
    .btn-edit {
        background: #3b82f6; color: white; border: none;
        padding: 6px 12px; border-radius: 6px; cursor: pointer;
        font-size: 0.85rem; text-decoration: none;
        display: inline-block;
    }
    .btn-edit:hover { background: #2563eb; }

    .btn-delete {
        background: #ef4444; color: white; border: none;
        padding: 6px 12px; border-radius: 6px; cursor: pointer;
        font-size: 0.85rem;
    }
    .btn-delete:hover { background: #dc2626; }

    .table { width: 100%; border-collapse: collapse; }
    .table th {
        background: #f1f5f9; padding: 15px; text-align: left;
        font-weight: 600; color: #334155; border-bottom: 2px solid #e2e8f0;
    }
    .table td { padding: 15px; border-bottom: 1px solid #e2e8f0; }
    .table tr:hover { background: #f8fafc; }

    .badge {
        display: inline-block; padding: 4px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 600;
    }
    .badge-admin { background: #dbeafe; color: #1e40af; }
    .badge-user { background: #dcfce7; color: #166534; }
    
    .action-buttons { display: flex; gap: 10px; }
    .section-title { font-size: 1.1rem; font-weight: bold; margin-bottom: 25px; color: #1e293b; }
</style>
@endsection

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h3 class="section-title">Daftar Pengguna</h3>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    @if($users->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="color: #64748b; font-size: 0.85rem;">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #64748b;">
            <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
            <p>Belum ada pengguna</p>
        </div>
    @endif
</div>
@endsection
