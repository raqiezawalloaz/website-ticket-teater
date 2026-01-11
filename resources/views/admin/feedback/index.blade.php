@extends('layouts.admin')

@section('title', 'Kelola Feedback')
@section('header_title', 'Kelola Feedback')
@section('header_subtitle', 'Daftar masukan dari pengguna')

@section('content')
<div class="card">
    <div style="overflow-x: auto;">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="p-4 font-semibold text-slate-700">User</th>
                    <th class="p-4 font-semibold text-slate-700">Tipe</th>
                    <th class="p-4 font-semibold text-slate-700">Pesan</th>
                    <th class="p-4 font-semibold text-slate-700">Tanggal</th>
                    <th class="p-4 font-semibold text-slate-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $item)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <div class="font-medium text-slate-900">{{ $item->user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $item->user->email }}</div>
                    </td>
                    <td class="p-4">
                        @php
                            $color = match($item->type) {
                                'Event' => 'bg-purple-100 text-purple-700',
                                'Tenant' => 'bg-emerald-100 text-emerald-700',
                                'Sponsor' => 'bg-blue-100 text-blue-700',
                                'Sistem' => 'bg-slate-100 text-slate-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                            {{ $item->type }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-600 max-w-xs truncate" title="{{ $item->message }}">
                            {{ $item->message }}
                        </div>
                    </td>
                    <td class="p-4 text-sm text-slate-500">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="p-4 text-center">
                        <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus feedback ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-slate-500">
                        <i class="fas fa-comment-slash text-4xl mb-3 block"></i>
                        Belum ada feedback masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection
