@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('header_title', 'Edit Pengguna')
@section('header_subtitle', 'Edit data pengguna sistem')

@section('styles')
<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #334155; }
    .form-control {
        width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;
        font-size: 1rem;
    }
    .btn-primary { 
        background: var(--primary-blue); color: white; border: none; 
        padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none;
    }
    .btn-primary:hover { background: #0052d4; }
</style>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Update Pengguna
        </button>
    </form>
</div>
@endsection
