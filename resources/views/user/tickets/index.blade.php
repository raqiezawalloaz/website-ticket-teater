@extends('layouts.user')

@section('title', 'Tiket Saya')
@section('header_title', 'Tiket Saya')
@section('header_subtitle', 'Daftar semua tiket yang telah Anda beli.')

@section('styles')
<style>
    .tickets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 10px;
    }
    .ticket-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    .ticket-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        padding: 20px;
        position: relative;
    }
    .ticket-status {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-success { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-failed { background: #fee2e2; color: #991b1b; }

    .ticket-body {
        padding: 20px;
        flex: 1;
    }
    .ticket-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }
    .ticket-footer {
        padding: 15px 20px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ticket-code {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        color: #4f46e5;
        font-size: 1.1rem;
    }
    .btn-download {
        color: #64748b;
        text-decoration: none;
        font-size: 1.2rem;
        transition: 0.2s;
    }
    .btn-download:hover { color: #4f46e5; }
    
    .pagination-container { margin-top: 40px; }
    
    .btn-pay-now {
        background: #6366f1; color: white; padding: 6px 14px; border-radius: 8px;
        font-size: 0.8rem; font-weight: 700; text-decoration: none; transition: 0.3s;
        border: none; cursor: pointer;
    }
    .btn-pay-now:hover { background: #4f46e5; transform: scale(1.05); }
</style>
@endsection

@section('content')
@if($transactions->count() > 0)
    <div class="tickets-grid">
        @foreach($transactions as $trx)
            <div class="ticket-card">
                <div class="ticket-header">
                    <span class="ticket-status status-{{ $trx->status }}">
                        {{ $trx->status }}
                    </span>
                    <div style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 5px;">Event</div>
                    <div style="font-size: 1.2rem; font-weight: bold;">{{ $trx->event->nama_event ?? $trx->event_name }}</div>
                </div>
                
                <div class="ticket-body">
                    <div class="ticket-info">
                        <div>
                            <div class="info-label">Kategori</div>
                            <div class="info-value">{{ $trx->ticket_category }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div class="info-label">Jumlah</div>
                            <div class="info-value">{{ $trx->quantity }} Tiket</div>
                        </div>
                    </div>
                    <div class="ticket-info" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                        <div>
                            <div class="info-label">Tanggal Beli</div>
                            <div class="info-value">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div class="info-label">Total Bayar</div>
                            <div class="info-value">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="ticket-footer">
                    <div>
                        <div class="info-label">Kode Tiket</div>
                        <div class="ticket-code">{{ $trx->ticket_code }}</div>
                    </div>
                    @if($trx->status === 'success')
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <a href="{{ route('user.tickets.download', $trx->id) }}" class="btn-pay-now" style="background: #10b981; padding: 6px 12px; font-size: 0.75rem;">
                                <i class="fas fa-download mr-1"></i> Download PDF
                            </a>
                            
                        </div>
                    @elseif($trx->status === 'pending')
                        <div style="display: flex; gap: 8px;">
                            @if($trx->snap_token)
                                <button class="btn-pay-now" onclick="payNow('{{ $trx->snap_token }}')">
                                    Bayar Sekarang
                                </button>
                            @endif
                            <form action="{{ route('user.tickets.cancel', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-pay-now" style="background: #ef4444;">
                                    Batalkan
                                </button>
                            </form>
                            <a href="{{ route('payment.simulate', $trx->id) }}" class="btn-pay-now" style="background: #8b5cf6;">
                                Simulator
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-container">
        {{ $transactions->links() }}
    </div>
@else
    <div style="text-align: center; padding: 100px 20px; background: white; border-radius: 16px; border: 1px solid #f1f5f9;">
        <i class="fas fa-ticket-alt" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 20px; display: block;"></i>
        <h3 style="color: #475569; margin-bottom: 10px;">Belum Ada Tiket</h3>
        <p style="color: #94a3b8; margin-bottom: 30px;">Anda belum melakukan transaksi pembelian tiket apapun.</p>
        <a href="{{ route('events.index') }}" style="background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            Cari Event Sekarang
        </a>
    </div>
@endif
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payNow(snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.reload();
            },
            onPending: function(result) {
                window.location.reload();
            },
            onError: function(result) {
                alert("Pembayaran Gagal!");
            }
        });
    }
</script>
@endsection
