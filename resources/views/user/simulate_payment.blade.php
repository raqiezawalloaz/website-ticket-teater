@extends('layouts.user')

@section('title', 'Simulator Pembayaran')
@section('header_title', 'Virtual Payment Simulator')
@section('header_subtitle', 'Gunakan halaman ini untuk mensimulasikan pembayaran tanpa kartu kredit/debit asli.')

@section('styles')
<style>
    .simulator-container { max-width: 600px; margin: 0 auto; }
    .simulator-card {
        background: white; border-radius: 24px; overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;
    }
    .simulator-header {
        background: #f8fafc; padding: 25px; border-bottom: 1px solid #f1f5f9;
        text-align: center;
    }
    .simulator-body { padding: 40px; }
    
    .status-pill {
        display: inline-block; padding: 6px 16px; border-radius: 20px;
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
        background: #fef9c3; color: #854d0e; margin-bottom: 20px;
    }
    
    .detail-item {
        display: flex; justify-content: space-between; margin-bottom: 15px;
        font-size: 0.95rem; color: #64748b;
    }
    .detail-item strong { color: #1e293b; }
    
    .total-box {
        background: #f5f3ff; padding: 20px; border-radius: 16px; margin: 30px 0;
        border: 1px dashed #c7d2fe; text-align: center;
    }
    .total-amount { font-size: 1.8rem; font-weight: 800; color: #4f46e5; }
    
    .btn-simulate {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white; border: none; width: 100%; padding: 18px; border-radius: 12px;
        font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: 0.3s;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
    }
    .btn-simulate:hover { transform: translateY(-3px); box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4); }
    
    .warning-box {
        background: #fffbeb; border: 1px solid #fde68a; padding: 15px;
        border-radius: 12px; margin-top: 25px; display: flex; gap: 12px;
    }
    .warning-box i { color: #d97706; margin-top: 3px; }
    .warning-box p { font-size: 0.85rem; color: #92400e; margin: 0; }
</style>
@endsection

@section('content')
<div class="simulator-container">
    <div class="simulator-card">
        <div class="simulator-header">
            <h3 style="margin:0; color: #1e293b;">Konfirmasi Pembayaran</h3>
        </div>
        
        <div class="simulator-body">
            <div style="text-align: center;">
                <span class="status-pill"><i class="fas fa-clock"></i> MENUNGGU PEMBAYARAN</span>
                <h4 style="font-size: 1.4rem; color: #1e293b; margin-top: 0;">{{ $transaction->event_name }}</h4>
            </div>

            <div style="margin-top: 30px;">
                <div class="detail-item">
                    <span>Nomor Referensi</span>
                    <strong>#{{ $transaction->reference_number }}</strong>
                </div>
                <div class="detail-item">
                    <span>Kategori Tiket</span>
                    <strong>{{ $transaction->ticket_category }}</strong>
                </div>
                <div class="detail-item">
                    <span>Jumlah</span>
                    <strong>{{ $transaction->quantity }} Tiket</strong>
                </div>
            </div>

            <div class="total-box">
                <div style="font-size: 0.85rem; color: #6366f1; font-weight: 600; text-transform: uppercase;">Total Tagihan</div>
                <div class="total-amount">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
            </div>

            <form action="{{ route('payment.simulate.process') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                <button type="submit" class="btn-simulate">
                    <i class="fas fa-check-circle"></i> Bayar Secara Virtual
                </button>
            </form>

            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <p><strong>Mode Pengujian:</strong> Tombol di atas akan mensimulasikan pembayaran "Success" ke database tanpa memotong saldo asli Anda. Gunakan ini untuk bypass error Midtrans 401.</p>
            </div>
            
            <a href="{{ route('user.tickets.index') }}" style="display: block; text-align: center; margin-top: 20px; color: #94a3b8; text-decoration: none; font-size: 0.9rem;">
                Batalkan dan Kembali
            </a>
        </div>
    </div>
</div>
@endsection
