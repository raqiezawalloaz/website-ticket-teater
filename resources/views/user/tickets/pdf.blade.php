<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket - {{ $transaction->ticket_code }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; }
        .ticket-container {
            width: 100%;
            border: 2px solid #4f46e5;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .row {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        .value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .footer {
            background-color: #f8fafc;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .ticket-code {
            font-family: monospace;
            font-size: 24px;
            color: #4f46e5;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <h1 style="margin:0;">E-TICKET</h1>
            <p style="margin:5px 0 0 0;">{{ $transaction->event_name }}</p>
        </div>
        
        <div class="content">
            <div class="row">
                <div class="label">Nama Pemesan</div>
                <div class="value">{{ $transaction->customer_name }}</div>
            </div>
            
            <div class="row">
                <div style="width: 50%; float: left;">
                    <div class="label">Kategori Tiket</div>
                    <div class="value">{{ $transaction->ticket_category }}</div>
                </div>
                <div style="width: 50%; float: right; text-align: right;">
                    <div class="label">Jumlah</div>
                    <div class="value">{{ $transaction->quantity }} Tiket</div>
                </div>
                <div style="clear: both;"></div>
            </div>
            
            <div class="row">
                <div style="width: 50%; float: left;">
                    <div class="label">Tanggal Pembelian</div>
                    <div class="value">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div style="width: 50%; float: right; text-align: right;">
                    <div class="label">Total Bayar</div>
                    <div class="value">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
                </div>
                <div style="clear: both;"></div>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <div class="label">Entry Code</div>
                <div class="ticket-code">{{ $transaction->ticket_code }}</div>
            </div>
        </div>
        
        <div class="footer">
            Harap tunjukkan e-ticket ini saat memasuki area teater.
            <br>
            Antigravity Ticket Systems &bull; website-ticket-teater.test
        </div>
    </div>
</body>
</html>
