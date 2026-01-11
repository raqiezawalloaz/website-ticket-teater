@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Haloo, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600">Selamat datang di dashboard tiket teater Anda.</p>
        </div>
    </div>

    <!-- Riwayat Event -->
    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Event & Tiket Saya</h2>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($transactions as $trx)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $trx->status == 'success' ? 'bg-green-100 text-green-800' : 
                                   ($trx->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ strtoupper($trx->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-400">
                            #{{ $trx->reference_number }}
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $trx->event->nama_event }}</h3>
                    <div class="text-sm text-gray-500 mb-4">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $trx->event->tanggal_event->format('d M Y, H:i') }}
                    </div>

                    <div class="border-t border-gray-100 pt-4 flex flex-col gap-2">
                        @if($trx->status == 'success')
                            <!-- Tombol Download Sertifikat -->
                            <a href="{{ route('certificates.download', $trx->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh Sertifikat
                            </a>

                            <!-- Tombol Feedback -->
                            @php
                                $existingFeedback = $trx->event->feedbacks->first();
                            @endphp

                            @if($existingFeedback)
                                <div class="text-center text-sm text-gray-500 mt-2 bg-gray-50 p-2 rounded">
                                    <span class="font-semibold text-yellow-500">
                                        ★ {{ $existingFeedback->rating }}
                                    </span> 
                                    - "{{ Str::limit($existingFeedback->comment, 20) }}"
                                </div>
                            @else
                                <button onclick="openFeedbackModal({{ $trx->event->id }}, '{{ $trx->event->nama_event }}')" 
                                    class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Beri Ulasan
                                </button>
                            @endif
                        @else
                            <button disabled class="w-full px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                Menunggu Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10 text-gray-500">
                Belum ada riwayat event. Ayo beli tiket sekarang!
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Feedback -->
<div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4">Beri Ulasan: <span id="modalEventName"></span></h3>
        
        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" id="modalEventId">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <div class="flex gap-4">
                    @for($i=1; $i<=5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                            <span class="text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition star-label">★</span>
                        </label>
                    @endfor
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ceritakan pengalamanmu..."></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeFeedbackModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFeedbackModal(eventId, eventName) {
        document.getElementById('modalEventId').value = eventId;
        document.getElementById('modalEventName').innerText = eventName;
        document.getElementById('feedbackModal').style.display = 'flex';
    }

    function closeFeedbackModal() {
        document.getElementById('feedbackModal').style.display = 'none';
        
        // Reset inputs if needed
        document.getElementById('modalEventId').value = '';
    }
    
    // Close modal on outside click
    window.onclick = function(event) {
        var modal = document.getElementById('feedbackModal');
        if (event.target == modal) {
            closeFeedbackModal();
        }
    }

    // Star rating visual interaction
    const stars = document.querySelectorAll('input[name="rating"]');
    stars.forEach(star => {
        star.addEventListener('change', function() {
            // Reset all to gray
            document.querySelectorAll('.star-label').forEach(s => {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            });
            // Color only checked and previous
            // Note: This needs more complex JS for hover/peer-checked logic if we rely only on class manipulation,
            // but Tailwind peer-checked works for the specific element.
            // For simple "fill up to N", we can use peer-checked + CSS reversing or JS.
            // Current CSS `peer-checked:text-yellow-400` only highlights the selected one.
            // Let's improve with JS for better UX:
            
            let val = this.value;
            // Find all stars with value <= val
            stars.forEach(s => {
                let label = s.nextElementSibling;
                if (s.value <= val) {
                    label.classList.remove('text-gray-300');
                    label.classList.add('text-yellow-400');
                } else {
                    label.classList.add('text-gray-300');
                    label.classList.remove('text-yellow-400');
                }
            });
        });
    });
</script>
@endsection
