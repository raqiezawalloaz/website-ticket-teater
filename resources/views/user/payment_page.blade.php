@extends('layouts.user')

@section('title', 'Pembayaran Tiket')
@section('header_title', 'Selesaikan Pembayaran')
@section('header_subtitle', 'Satu langkah lagi untuk mendapatkan tiket Anda.')

@section('styles')
<style>
    .payment-container { max-width: 500px; margin: 0 auto; }
    .payment-card { 
        background: white; border-radius: 20px; padding: 35px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
        text-align: center;
    }
    .payment-icon {
        width: 70px; height: 70px; background: #f5f3ff; color: #6366f1;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px; font-size: 1.8rem;
    }
    .summary-box {
        background: #f8fafc; border-radius: 16px; padding: 25px; margin: 25px 0;
        text-align: left;
    }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; }
    .summary-row.total { border-top: 2px dashed #e2e8f0; margin-top: 15px; padding-top: 15px; font-weight: 800; font-size: 1.2rem; }
    
    .btn-pay {
        background: #6366f1; color: white; border: none; padding: 16px; border-radius: 12px;
        width: 100%; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: 0.3s;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }
    .btn-pay:hover { background: #4f46e5; transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="payment-container">
    <div class="payment-card">
        <div class="payment-icon">
            <i class="fas fa-wallet"></i>
        </div>
        
        <h2 style="font-weight: 800; color: #1e293b;">Konfirmasi Pesanan</h2>
        <p style="color: #64748b; font-size: 0.95rem;">Mohon periksa kembali detail pesanan Anda sebelum membayar.</p>

        <div class="summary-box">
            <div class="summary-row">
                <span style="color: #64748b;">Event</span>
                <span style="font-weight: 600;">{{ $transaction->event_name }}</span>
            </div>
            <div class="summary-row">
                <span style="color: #64748b;">Kategori</span>
                <span style="font-weight: 600;">{{ $transaction->ticket_category }}</span>
            </div>
            <div class="summary-row">
                <span style="color: #64748b;">Kuantitas</span>
                <span style="font-weight: 600;">{{ $transaction->quantity }} Tiket</span>
            </div>
            <div class="summary-row total">
                <span>Total Bayar</span>
                <span style="color: #6366f1;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <button id="pay-button" class="btn-pay">
            Pay Now with Midtrans
        </button>

        <p style="margin-top: 20px; font-size: 0.85rem; color: #94a3b8;">
            <i class="fas fa-shield-alt"></i> Transaksi terenkripsi dan aman.
        </p>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('payment.finish') }}"; 
            },
            onPending: function(result) {
                window.location.href = "{{ route('payment.finish') }}";
            },
            onError: function(result) {
                alert("Pembayaran Gagal!");
            }
        });
    });
</script>
@endsection