@extends('layouts.admin')

@section('title', 'Detail Event')
@section('header_title', 'Detail Event')
@section('header_subtitle', 'Lihat detail event dan ulasan pengguna.')

@section('content')
<div class="mb-6 flex space-x-4">
    <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
        &larr; Kembali
    </a>
    <a href="{{ route('admin.events.edit', $event->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
        Edit Event
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Informasi Event</h3>
        <table class="w-full">
            <tr class="border-b">
                <td class="py-2 text-gray-600 w-1/3">Nama Event</td>
                <td class="py-2 font-semibold">{{ $event->nama_event }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 text-gray-600">Tanggal</td>
                <td class="py-2">{{ $event->tanggal_event->format('d F Y H:i') }}</td>
            </tr>
            <tr class="border-b">
                <td class="py-2 text-gray-600">Lokasi</td>
                <td class="py-2">{{ $event->lokasi }}</td>
            </tr>
            <tr>
                <td class="py-2 text-gray-600">Status</td>
                <td class="py-2">
                    <span class="px-2 py-1 text-xs rounded-full {{ $event->status_event == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($event->status_event) }}
                    </span>
                </td>
            </tr>
        </table>
        
        <div class="mt-4">
            <h4 class="font-semibold text-gray-700 mb-2">Deskripsi</h4>
            <div class="bg-gray-50 p-3 rounded text-sm text-gray-600">
                {{ $event->deskripsi ?: 'Tidak ada deskripsi' }}
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Ulasan Pengguna</h3>
            <div class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                Rata-rata: {{ number_format($event->feedbacks->avg('rating'), 1) }} / 5.0
            </div>
        </div>

        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
            @forelse($event->feedbacks as $feedback)
                <div class="border-b border-gray-100 pb-3 last:border-0">
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-semibold text-gray-800">{{ $feedback->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $feedback->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex items-center mb-1">
                        @for($i=1; $i<=5; $i++)
                            <span class="text-sm {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600 italic">"{{ $feedback->comment }}"</p>
                </div>
            @empty
                <div class="text-center text-gray-400 py-4">
                    Belum ada ulasan untuk event ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
