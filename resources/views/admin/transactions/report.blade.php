<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #333; color: white; }
        .header { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN TIKET TEATER</h2>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No. Ref</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Event</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->reference_number }}</td>
                <td>{{ $trx->customer_name }}</td>
                <td>{{ $trx->customer_email }}</td>
                <td>{{ $trx->event_name }}</td>
                <td>Rp {{ number_format($trx->total_price) }}</td>
                <td>{{ strtoupper($trx->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>