@extends('layouts.app') 

@section('title', $event->nama_event)
@section('header_title', 'Detail Event')
@section('header_subtitle', $event->nama_event)

@section('styles')
<style>
    .event-container { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
    
    /* Responsive Grid */
    @media (max-width: 900px) { .event-container { grid-template-columns: 1fr; } }

    .event-card {
        background: white; border-radius: 16px; overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        margin-bottom: 30px;
    }
    .event-banner {
        min-height: 250px; background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        padding: 40px; color: white; display: flex; flex-direction: column; justify-content: flex-end;
    }
    .event-body { padding: 30px; }
    .section-title { font-size: 1.25rem; font-weight: bold; margin-bottom: 20px; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
    .meta-item { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; color: #64748b; }
    .meta-item i { width: 20px; text-align: center; color: #6366f1; }

    .ticket-selector { background: white; padding: 25px; border-radius: 16px; border: 1px solid #f1f5f9; }
    .category-option {
        display: block;
        border: 2px solid #f1f5f9; padding: 18px; border-radius: 14px; margin-bottom: 15px;
        cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative; background: #fff;
    }
    .category-option:hover:not(.sold-out) { border-color: #6366f1; transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1); background: #fafaff; }
    
    .category-option input[type="radio"] { position: absolute; opacity: 0; }
    
    .category-option.selected { 
        border-color: #6366f1; background: #f5f3ff; 
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .category-option.selected::after {
        content: '\f058'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; top: 15px; right: 15px; color: #6366f1; font-size: 1.2rem;
    }

    .category-option.sold-out { 
        opacity: 0.6; cursor: not-allowed; background: #f8fafc; border-style: dashed;
        filter: grayscale(1);
    }
    
    .price-tag { font-size: 1.3rem; font-weight: 800; color: #1e293b; margin: 8px 0; display: block; }
    .category-option.selected .price-tag { color: #4f46e5; }
    .stock-badge { font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; font-weight: 600; display: inline-block; }
    .stock-available { background: #f0fdf4; color: #166534; }
    .stock-critical { background: #fff7ed; color: #9a3412; }
    .stock-none { background: #fef2f2; color: #991b1b; }

    .btn-checkout {
        display: block; width: 100%; padding: 15px; background: #6366f1; color: white;
        border: none; border-radius: 10px; font-weight: bold; font-size: 1rem; cursor: pointer;
        transition: 0.3s; margin-top: 20px;
    }
    .btn-checkout:hover { background: #4f46e5; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
    .btn-checkout:disabled { background: #cbd5e1; cursor: not-allowed; transform: none; box-shadow: none; }
</style>
@endsection

@section('content')
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #bbf7d0;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #fecaca;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="event-container">
    <div class="main-column">
        <div class="event-card">
            <div class="event-banner">
                <span style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; width: fit-content; margin-bottom: 15px;">
                    {{ $event->status_event }}
                </span>
                <h1 style="font-size: 2.2rem; font-weight: 800; line-height: 1.2;">{{ $event->nama_event }}</h1>
            </div>
            <div class="event-body">
                <div class="section-title">Informasi Acara</div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ \Carbon\Carbon::parse($event->tanggal_event)->translatedFormat('d F Y') }} • {{ \Carbon\Carbon::parse($event->tanggal_event)->format('H:i') }} WIB</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $event->lokasi ?? 'Lokasi Menyusul' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-users"></i>
                    <span>Kapasitas: {{ $event->total_capacity ?? 'Tidak Terbatas' }} Orang</span>
                </div>

                <div class="section-title" style="margin-top: 40px;">Deskripsi</div>
                <div style="line-height: 1.8; color: #475569; font-size: 1rem;">
                    {!! nl2br(e($event->deskripsi)) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="side-column">
        <div class="ticket-selector">
            <div class="section-title">Pesan Tiket</div>

            @auth
                @if($event->ticketCategories->count() > 0)
                    <!-- Form Checkout Pintar (Branch Arrival) -->
                    <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 15px;">Pilih kategori tiket:</p>

                        @foreach($event->ticketCategories as $category)
                            <label class="category-option {{ $category->remaining_stock <= 0 ? 'sold-out' : '' }}" 
                                   data-stock="{{ $category->remaining_stock }}"
                                   onclick="{{ $category->remaining_stock > 0 ? 'selectCategory(this)' : '' }}">
                                <input type="radio" name="ticket_category_id" value="{{ $category->id }}" 
                                       {{ $category->remaining_stock <= 0 ? 'disabled' : '' }} required>
                                
                                <div class="option-content">
                                    <div style="font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $category->name }}
                                    </div>
                                    <span class="price-tag">
                                        Rp {{ number_format($category->price, 0, ',', '.') }}
                                    </span>
                                    
                                    @if($category->remaining_stock <= 0)
                                        <span class="stock-badge stock-none">Terjual Habis</span>
                                    @elseif($category->remaining_stock <= 10)
                                        <span class="stock-badge stock-critical">Hampir Habis: {{ $category->remaining_stock }} tiket</span>
                                    @else
                                        <span class="stock-badge stock-available">Tersedia: {{ $category->remaining_stock }} tiket</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach

                        <div style="margin-top: 25px;">
                            <label style="font-size: 0.9rem; color: #64748b; display: block; margin-bottom: 8px;">Jumlah Tiket:</label>
                            <input type="number" name="quantity" value="1" min="1" max="10" 
                                   style="width: 100%; padding: 12px; border-radius: 8px; border: 2px solid #f1f5f9; font-weight: bold;" required>
                            <small id="qty-helper" style="color: #94a3b8; font-size: 0.8rem; display: block; margin-top: 5px;">Maksimal pembelian per transaksi: 10 tiket</small>
                        </div>

                        <!-- Auto-fill User Data -->
                        <input type="hidden" name="customer_name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="customer_email" value="{{ Auth::user()->email }}">

                        @if($event->ticketCategories->sum(fn($cat) => $cat->remaining_stock) > 0)
                            <button type="submit" class="btn-checkout">
                                <i class="fas fa-shopping-cart"></i> Beli Sekarang
                            </button>
                        @else
                            <button type="button" class="btn-checkout" disabled>
                                Tiket Habis
                            </button>
                        @endif
                    </form>
                @else
                    <div style="text-align: center; color: #94a3b8; padding: 20px;">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>Tiket belum tersedia untuk acara ini.</p>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 20px;">
                    <p style="color: #64748b; margin-bottom: 20px;">Silakan login terlebih dahulu untuk membeli tiket.</p>
                    <a href="{{ route('login') }}" class="btn-checkout" style="text-align: center; text-decoration: none;">Login untuk Membeli</a>
                </div>
            @endauth
        </div>

        <div class="panel" style="margin-top: 25px; background: white; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
            <div style="color: #64748b; font-size: 0.85rem; line-height: 1.6;">
                <i class="fas fa-shield-alt" style="color: #22c55e;"></i> Pembayaran aman menggunakan Midtrans. E-tiket akan dikirimkan ke email Anda setelah pembayaran berhasil.
            </div>
        </div>
    </div>
</div>

<script>
    function selectCategory(element) {
        // Hapus kelas selected dari semua opsi
        document.querySelectorAll('.category-option').forEach(el => el.classList.remove('selected'));
        // Tambahkan ke yang diklik
        element.classList.add('selected');
        
        // Pastikan radio button terpilih
        const radio = element.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        
        // Update validasi max quantity berdasarkan stok
        const stock = parseInt(element.getAttribute('data-stock'));
        const quantityInput = document.querySelector('input[name="quantity"]');
        const maxPurchase = Math.min(10, stock); // Max 10 atau sisa stok
        
        quantityInput.max = maxPurchase;
        document.getElementById('qty-helper').innerText = `Maksimal pembelian: ${maxPurchase} tiket`;

        // Reset value jika melebihi stok baru
        if (parseInt(quantityInput.value) > maxPurchase) {
            quantityInput.value = maxPurchase;
        }
    }
</script>
@endsection