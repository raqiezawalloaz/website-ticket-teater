<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Feedback</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #0061ff;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #0061ff;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .summary {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #0061ff;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #0061ff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: #0061ff;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            font-weight: bold;
            font-size: 13px;
        }
        td {
            font-size: 12px;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        tbody tr:hover {
            background: #eff6ff;
        }
        
        .rating {
            color: #fbbf24;
            font-weight: bold;
        }
        
        .comment {
            font-style: italic;
            color: #475569;
            max-width: 300px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN FEEDBACK & ULASAN</h1>
        <p>Campus Event - Sistem Manajemen Terintegrasi</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Data</h3>
        <p><strong>Total Ulasan:</strong> {{ $feedbacks->count() }}</p>
        <p><strong>Rating Rata-rata:</strong> {{ number_format($feedbacks->avg('rating'), 2) }} / 5.0</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Event</th>
                <th style="width: 15%;">User</th>
                <th style="width: 10%;">Rating</th>
                <th style="width: 35%;">Komentar</th>
                <th style="width: 15%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $index => $feedback)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $feedback->event->nama_event }}</strong></td>
                    <td>{{ $feedback->user->name }}</td>
                    <td class="rating">★ {{ $feedback->rating }}.0</td>
                    <td class="comment">{{ $feedback->comment ?: '-' }}</td>
                    <td>{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                        Belum ada data feedback
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem Campus Event</p>
        <p>© {{ now()->year }} Campus Event - All Rights Reserved</p>
    </div>
</body>
</html>
