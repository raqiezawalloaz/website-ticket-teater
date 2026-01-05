<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Partisipasi</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .container {
            width: 100%;
            height: 100vh;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            text-align: center;
            @if($event->certificate_background)
                background-image: url('{{ public_path('storage/' . $event->certificate_background) }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            @else
                background: #fff;
            @endif
        }
        
        @if(!$event->certificate_background)
        /* Decorative Borders - Only if no custom background */
        .border-pattern {
            position: absolute;
            top: 20px; left: 20px; right: 20px; bottom: 20px;
            border: 2px solid #b45309; /* Amber-700 */
            z-index: 1;
        }
        .border-inner {
            position: absolute;
            top: 25px; left: 25px; right: 25px; bottom: 25px;
            border: 1px solid #d97706; /* Amber-600 */
            z-index: 1;
        }
        @endif

        /* Content */
        .header {
            margin-top: 60px;
            font-size: 60px;
            font-weight: bold;
            text-transform: uppercase;
            color: #78350f; /* Amber-900 */
            letter-spacing: 5px;
            font-family: 'serif';
            position: relative;
            z-index: 10;
        }
        .sub-header {
            font-size: 24px;
            color: #b45309;
            margin-top: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            z-index: 10;
        }
        
        .presented-to {
            margin-top: 40px;
            font-size: 18px;
            color: #555;
            font-style: italic;
            position: relative;
            z-index: 10;
        }

        .name {
            font-size: 50px;
            font-weight: bold;
            color: #1e293b; /* Slate-800 */
            margin: 20px 0;
            font-family: 'serif';
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding-bottom: 10px;
            min-width: 400px;
            position: relative;
            z-index: 10;
        }

        .content {
            font-size: 20px;
            line-height: 1.6;
            color: #334155;
            margin-top: 20px;
            padding: 0 100px;
            position: relative;
            z-index: 10;
        }
        .event-name {
            font-weight: bold;
            color: #000;
            font-size: 24px;
        }

        .footer {
            margin-top: 80px;
            display: table;
            width: 80%;
            margin-left: 10%;
            position: relative;
            z-index: 10;
        }
        .signature-box {
            display: table-cell;
            text-align: center;
            vertical-align: top;
            width: 40%;
        }
        .signature-line {
            width: 80%;
            margin: 0 auto;
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        .signature-role {
            font-size: 14px;
            color: #777;
        }
        
        @if(!$event->certificate_background)
        /* Watermark - Only if no custom background */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 150px;
            color: rgba(0,0,0,0.03);
            font-weight: bold;
            z-index: 0;
            white-space: nowrap;
        }
        @endif
    </style>
</head>
<body>
    @if(!$event->certificate_background)
        <div class="watermark">CAMPUS EVENT</div>
    @endif

    <div class="container">
        @if(!$event->certificate_background)
            <!-- Borders - Only if no custom background -->
            <div class="border-pattern"></div>
            <div class="border-inner"></div>
        @endif

        <div class="header">Sertifikat</div>
        <div class="sub-header">PARTISIPASI</div>

        <div class="presented-to">Diberikan Kepada:</div>

        <div class="name">{{ $user->name }}</div>

        <div class="content">
            Sebagai tanda apresiasi atas partisipasinya dalam acara:<br>
            <span class="event-name">{{ $event->nama_event }}</span>
        </div>
        
        <div style="margin-top: 15px; font-size: 16px; color: #666; position: relative; z-index: 10;">
            Diselenggarakan pada tanggal {{ $event->tanggal_event->format('d F Y') }}
        </div>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">{{ now()->format('d F Y') }}</div>
                <div class="signature-role">Tanggal Terbit</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Ketua Panitia</div>
                <div class="signature-role">Penyelenggara Event</div>
            </div>
        </div>
        
        <div style="position: absolute; bottom: 40px; width: 100%; text-align: center; font-size: 12px; color: #aaa; z-index: 10;">
            ID Transaksi: {{ $transaction->reference_number }} | Verifikasi keaslian sertifikat ini melalui sistem kami.
        </div>
    </div>
</body>
</html>
