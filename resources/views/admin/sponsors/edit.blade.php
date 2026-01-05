@extends('layouts.admin')

@section('title', 'Edit Sponsor')
@section('header_title', 'Edit Sponsor')
@section('header_subtitle', 'Perbarui informasi sponsor')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">Formulir Edit Sponsor</h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi detail mengenai sponsor.</p>
        </div>
        
        <form action="{{ route('admin.sponsors.update', $sponsor->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid gap-6">
                <!-- Nama Sponsor -->
                <div>
                    <label for="nama_sponsor" class="block text-sm font-medium text-gray-700 mb-2">Nama Sponsor</label>
                    <input type="text" 
                           id="nama_sponsor" 
                           name="nama_sponsor" 
                           value="{{ $sponsor->nama_sponsor }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all duration-200"
                           placeholder="Masukkan nama sponsor"
                           required>
                </div>

                <!-- Jenis Sponsor -->
                <div>
                    <label for="jenis_sponsor" class="block text-sm font-medium text-gray-700 mb-2">Jenis Sponsor</label>
                    <select name="jenis_sponsor" 
                            id="jenis_sponsor"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all duration-200"
                            required>
                        <option value="">Pilih Jenis Sponsor</option>
                        <option value="Gold" {{ $sponsor->jenis_sponsor == 'Gold' ? 'selected' : '' }}>Gold</option>
                        <option value="Silver" {{ $sponsor->jenis_sponsor == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Bronze" {{ $sponsor->jenis_sponsor == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                    </select>
                </div>

                <!-- Kontak -->
                <div>
                    <label for="kontak" class="block text-sm font-medium text-gray-700 mb-2">Kontak</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="kontak" 
                               name="kontak" 
                               value="{{ $sponsor->kontak }}"
                               class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors duration-200"
                               placeholder="Email atau Nomor Kontak"
                               required>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                <a href="{{ route('admin.sponsors.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
