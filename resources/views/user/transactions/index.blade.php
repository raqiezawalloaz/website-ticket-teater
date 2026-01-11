<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - CAMPUS-EVENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .back-btn:hover {
            background: #f3f4f6;
        }

        .header {
            background: white;
            margin: 20px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid #667eea;
        }

        .header h2 {
            color: #1e293b;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .panel {
            background: white;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        th {
            padding: 15px;
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            text-decoration: none;
            color: white;
            transition: 0.2s;
            cursor: pointer;
            border: none;
            margin-right: 5px;
        }

        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .empty-state a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .empty-state a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="header">
            <h2>🎫 Tiket Saya</h2>
            <p>Kelola tiket Anda, pantau pembayaran, dan download e-tiket di sini.</p>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>ID ORDER</th>
                        <th>EVENT</th>
                        <th>TANGGAL</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td style="font-weight: bold;">{{ $trx->reference_number }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $trx->event_name ?? 'N/A' }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $trx->ticket_category ?? 'Regular' }}</div>
                        </td>
                        <td style="color: #475569;">{{ $trx->created_at->format('d M Y') }}</td>
                        <td style="font-weight: 600;">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($trx->status == 'pending')
                                <span class="badge" style="background: #fef3c7; color: #92400e;">⏳ Menunggu Bayar</span>
                            @elseif($trx->status == 'success')
                                <span class="badge" style="background: #dcfce7; color: #166534;">✓ Lunas</span>
                            @elseif($trx->status == 'expired')
                                <span class="badge" style="background: #fecaca; color: #7c2d12;">⏰ Expired</span>
                            @else
                                <span class="badge" style="background: #fee2e2; color: #991b1b;">✗ Gagal</span>
                            @endif
                        </td>
                        <td>
                            @if($trx->status == 'pending')
                                @if($trx->payment_url)
                                    <a href="{{ $trx->payment_url }}" class="btn-action" style="background: #3b82f6;">
                                        <i class="fas fa-credit-card"></i> Bayar
                                    </a>
                                @endif
                            @elseif($trx->status == 'success')
                                <a href="{{ route('user.transactions.download', $trx->id) }}" class="btn-action" style="background: #22c55e;">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <a href="{{ route('user.transactions.show', $trx->id) }}" class="btn-action" style="background: #6366f1;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            @else
                                <a href="{{ route('user.transactions.show', $trx->id) }}" class="btn-action" style="background: #f97316;">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-ticket-alt"></i>
                                <p><strong>Belum ada tiket</strong></p>
                                <p style="font-size: 0.9rem; margin-bottom: 20px;">Mulai beli tiket untuk event favorit Anda sekarang.</p>
                                <a href="{{ route('events.index') }}"><i class="fas fa-plus-circle"></i> Cari Event</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>