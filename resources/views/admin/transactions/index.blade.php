@extends('layouts.admin')

@section('title', 'Manajemen Transaksi & Tiket')
@section('header_title', 'Manajemen Transaksi & Tiket')
@section('header_subtitle')
<i class="fas fa-map-marker-alt"></i> Gedung Kesenian Jakarta
@endsection

@section('styles')
<style>
    /* LOCAL STYLES FROM ORIGINAL TRANSACTION PAGE */
    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-card h4 { color: #64748b; font-size: 0.85rem; margin-bottom: 5px; }
    .stat-card .number { font-size: 1.5rem; font-weight: bold; color: #1e293b; }

    /* Panel Tabel */
    .panel { background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 15px; background: #f8fafc; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
    td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }

    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef9c3; color: #854d0e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #e0f2fe; color: #0369a1; }
    .badge-secondary { background: #f1f5f9; color: #475569; }

    .btn { padding: 8px 14px; border-radius: 8px; font-size: 0.8rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: none; transition: 0.3s; }
    .btn-pdf { background: #ef4444; color: white; font-weight: bold; }
    .btn-pdf:hover { background: #dc2626; }
    .btn-api { background: #f1f5f9; color: #1e40af; border: 1px solid #e2e8f0; }
    .btn-api:hover { background: #e2e8f0; }
    
    .text-primary { color: var(--primary-blue); }
</style>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <h4>Total Transaksi</h4>
        <div class="number">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <h4 style="color: #22c55e;">Lunas (Success)</h4>
        <div class="number">{{ $stats['success'] }}</div>
    </div>
    <div class="stat-card">
        <h4 style="color: #f59e0b;">Menunggu (Pending)</h4>
        <div class="number">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <h4 style="color: #ef4444;">Gagal/Expired</h4>
        <div class="number">{{ $stats['failed'] }}</div>
    </div>
</div>

@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #bbf7d0; font-size: 0.9rem;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #fecaca; font-size: 0.9rem;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="panel">
    <div class="panel-header">
        <h3>Daftar Pesanan & Tiket Digital</h3>
        <a href="{{ route('admin.transactions.exportPdf') }}" class="btn btn-pdf"><i class="fas fa-file-pdf"></i> LAPORAN PDF</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ref / Kode Tiket</th>
                <th>Customer / User</th> <th>Event / Kategori</th> <th>Status Bayar</th>
                <th>Status Tiket</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td>
                    <strong>#{{ $trx->reference_number }}</strong><br>
                    <small class="text-primary">{{ $trx->ticket_code }}</small>
                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                </td>

                <td>
                    <div style="font-weight: bold;">
                        {{ $trx->user->name ?? $trx->customer_name }}
                    </div>
                    <small style="color: #64748b;">
                        {{ $trx->user->email ?? $trx->customer_email }}
                    </small><br>
                    <small style="color: #64748b;">{{ $trx->customer_phone }}</small>
                </td>

                <td>
                    <div style="font-weight: bold; color: var(--primary-blue); margin-bottom: 4px;">
                        {{ $trx->event->nama_event ?? $trx->event_name ?? 'Event Tidak Ditemukan' }}
                    </div>

                    <span class="badge" style="background: #334155; color: white;">{{ $trx->ticket_category }}</span>
                    <small style="margin-left: 5px;">Seat: {{ $trx->seat_number ?? 'Bebas' }}</small>
                </td>

                <td>
                    <span class="badge {{ $trx->status == 'success' ? 'badge-success' : ($trx->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                        {{ strtoupper($trx->status) }}
                    </span>
                </td>

                <td>
                    @if($trx->is_checked_in)
                        <span class="badge badge-info">SUDAH MASUK</span>
                    @else
                        <span class="badge badge-secondary">BELUM DIPAKAI</span>
                    @endif
                </td>

                <td>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        <a href="{{ route('admin.transactions.checkStatus', $trx->id) }}" class="btn btn-api" title="Cek API Status Midtrans">
                            <i class="fas fa-sync"></i> API
                        </a>

                        @if($trx->status == 'pending')
                        <a href="{{ route('admin.transactions.mockPay', $trx->id) }}" class="btn btn-api" style="color: #166534;" title="Bayar (Simulasi)">
                            <i class="fas fa-money-bill"></i>
                        </a>
                        @endif

                        <button onclick="viewTicket('{{ $trx->ticket_code }}', '{{ $trx->user->name ?? $trx->customer_name }}')" class="btn btn-api" title="Lihat E-Ticket">
                            <i class="fas fa-qrcode"></i>
                        </button>

                        @if($trx->status == 'success' && !$trx->is_checked_in)
                        <form action="{{ route('admin.transactions.checkIn', $trx->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-api" style="color: #22c55e;" title="Check-in Masuk Gedung">
                                <i class="fas fa-sign-in-alt"></i>
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-api" style="color: #ef4444;"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; color: #64748b; padding: 40px;">Belum ada data transaksi dan tiket.</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Modal Structure preserved exactly -->
<div id="ticketModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000; backdrop-filter: blur(4px);">
    <div style="background:white; width:350px; margin:80px auto; padding:30px; border-radius:20px; text-align:center; position:relative; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <span onclick="closeModal()" style="position:absolute; right:20px; top:15px; cursor:pointer; font-size: 1.5rem; color: #94a3b8;">&times;</span>
        <h4 style="margin-bottom:5px; color: #1e293b;">E-TICKET TEATER</h4>
        <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 20px;">CAMPUS-EVENT MANAJEMEN</p>
        
        <div id="qrcode" style="margin-bottom:20px; background: #f8fafc; padding: 15px; border-radius: 12px; display: inline-block;">
            <img src="" id="qrImg" width="160" height="160" alt="QR Code Ticket">
        </div>

        <h3 id="ticketName" style="margin:0; color: #1e293b; font-size: 1.2rem;">-</h3>
        <p id="ticketCode" style="color:var(--primary-blue); font-weight:bold; letter-spacing: 1px; margin-top: 5px;">-</p>
        
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px dashed #e2e8f0;">
            <p style="font-size:0.75rem; color:#64748b; line-height: 1.5;">Tunjukkan QR Code ini kepada petugas di pintu masuk Gedung Kesenian Jakarta.</p>
        </div>
    </div>
</div>

<script>
    function viewTicket(code, name) {
        document.getElementById('ticketModal').style.display = 'block';
        document.getElementById('ticketName').innerText = name;
        document.getElementById('ticketCode').innerText = code;
        // Generate QR Code dinamis berdasarkan kode tiket
        document.getElementById('qrImg').src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${code}`;
    }
    function closeModal() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    // Tutup modal jika user klik di luar kotak modal
    window.onclick = function(event) {
        let modal = document.getElementById('ticketModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endsection