<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-Tiket {{ $transaction->reference_number }} - CAMPUS-EVENT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 20px;
        }

        .ticket {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .ticket-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            text-align: center;
            border-bottom: 3px dashed rgba(102, 126, 234, 0.3);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .ticket-number {
            font-size: 12px;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .ticket-content {
            color: white;
            padding: 40px 30px;
        }

        .event-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }

        .buyer-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }

        .buyer-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .buyer-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .buyer-email {
            font-size: 12px;
            opacity: 0.9;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .qr-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #667eea;
            font-weight: bold;
            text-align: center;
        }

        .ticket-footer {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 30px;
            text-align: center;
            font-size: 11px;
            opacity: 0.9;
            border-top: 3px dashed rgba(255, 255, 255, 0.3);
        }

        .status-badge {
            display: inline-block;
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.5);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
            margin: 20px 0;
        }

        @media print {
            body {
                padding: 0;
            }

            .ticket {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <div class="logo">CAMPUS-EVENT</div>
            <div class="ticket-number">Nomor Tiket: {{ $transaction->reference_number }}</div>
        </div>

        <!-- Content -->
        <div class="ticket-content">
            <div class="status-badge">✓ Pembayaran Berhasil</div>

            <div class="event-name">{{ $transaction->event_name }}</div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kategori Tiket</div>
                    <div class="info-value">{{ strtoupper($transaction->ticket_category) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Harga</div>
                    <div class="info-value">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
                </div>
                @if($transaction->event)
                    <div class="info-item">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value">{{ $transaction->event->tanggal_event->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Waktu</div>
                        <div class="info-value">{{ $transaction->event->tanggal_event->format('H:i') }} WIB</div>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <div class="info-label">Lokasi</div>
                        <div class="info-value">{{ $transaction->event->lokasi }}</div>
                    </div>
                @endif
            </div>

            <div class="divider"></div>

            <!-- Buyer Info -->
            <div class="buyer-info">
                <div class="buyer-label">Pemegang Tiket</div>
                <div class="buyer-name">{{ $transaction->customer_name }}</div>
                <div class="buyer-email">{{ $transaction->customer_email }}</div>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-label">Kode Verifikasi</div>
                <div class="qr-code">
                    {{ $transaction->reference_number }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <p>Tunjukkan tiket ini saat check-in di venue. Tiket ini berlaku hanya untuk pemegang nama di atas.</p>
            <p style="margin-top: 10px;">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

</body>
</html>
