<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event - CAMPUS-EVENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --gradient-start: #0061ff;
            --gradient-end: #6366f1;
            --bg-gray: #f8fafc;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-gray); display: flex; min-height: 100vh; }

        /* Sidebar Sesuai Gambar 1 & 2 */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-right: 1px solid #e2e8f0;
            z-index: 100;
        }
        .brand { font-weight: bold; font-size: 1.2rem; margin-bottom: 30px; color: #1e293b; }
        .brand span { display: block; font-size: 0.7rem; color: #64748b; font-weight: normal; }
        
        .nav-item {
            padding: 12px 15px; margin-bottom: 5px; border-radius: 8px;
            color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 12px;
            transition: 0.3s;
        }
        .nav-item.active { background: var(--primary-blue); color: white; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }

        /* Main Content Area */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px 40px; }
        
        /* Header Profile Sesuai Gambar 1 */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .avatar { width: 40px; height: 40px; background: var(--gradient-end); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        /* Grid Event */
        .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }

        /* Modern Event Card */
        .event-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

        /* Banner Card Sesuai Gambar 1 */
        .event-card-header {
            padding: 25px;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white;
            position: relative;
        }
        .event-card-title { font-size: 1.2rem; font-weight: bold; margin-bottom: 8px; }
        .event-card-date { font-size: 0.85rem; opacity: 0.9; display: flex; align-items: center; gap: 6px; }

        .event-card-body { padding: 20px; flex: 1; }
        .info-row { display: flex; align-items: center; gap: 10px; color: #64748b; font-size: 0.9rem; margin-bottom: 12px; }
        .info-row i { width: 16px; color: #94a3b8; }

        .event-card-footer {
            padding: 15px 20px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Status Badge Sesuai Gambar 2 */
        .badge-success { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .badge-pending { background: #fef9c3; color: #854d0e; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        .btn-buy {
            background: var(--primary-blue);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-buy:hover { background: #1d4ed8; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">CAMPUS-EVENT <span>Sistem Manajemen Event Terintegrasi</span></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="{{ route('events.index') }}" class="nav-item active"><i class="fas fa-calendar-alt"></i> Event & Kategori</a>
            <a href="#" class="nav-item"><i class="fas fa-ticket-alt"></i> Transaksi & Tiket</a>
            <a href="#" class="nav-item"><i class="fas fa-store"></i> Tenant & Sponsor</a>
            <a href="#" class="nav-item"><i class="fas fa-certificate"></i> Sertifikat & Feedback</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2 style="color: #1e293b; font-size: 1.5rem;">Daftar Event</h2>
                <p style="color: #64748b; font-size: 0.85rem;">Pilih dan ikuti event menarik di kampus</p>
            </div>
            
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-weight: bold; color: #1e293b;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                </div>
                <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
                <div class="event-card">
                    <div class="event-card-header">
                        <div class="event-card-title">{{ $event->nama_event }}</div>
                        <div class="event-card-date">
                            <i class="far fa-calendar-alt"></i> {{ $event->tanggal_event->format('d F Y') }}
                        </div>
                    </div>

                    <div class="event-card-body">
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $event->lokasi }}</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-users"></i>
                            <span>Tersisa 45 Kursi</span> </div>
                        <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-top: 10px;">
                            {{ \Illuminate\Support\Str::limit($event->deskripsi, 90) }}
                        </p>
                    </div>

                    <div class="event-card-footer">
                        <span class="badge-success">
                            <i class="fas fa-check-circle"></i> Tersedia
                        </span>
                        <a href="{{ route('events.show', $event->id) }}" class="btn-buy">
                            Beli Tiket <i class="fas fa-arrow-right" style="margin-left: 5px; font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #94a3b8;">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Belum ada event yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>