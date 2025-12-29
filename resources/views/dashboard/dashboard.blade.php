<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CAMPUS-EVENT</title>
    <!-- Font Awesome untuk Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0061ff;
            --bg-gray: #f8fafc;
            --text-dark: #334155;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-gray);
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-right: 1px solid #e2e8f0;
            z-index: 100;
        }

        .brand-logo {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 30px;
            padding-left: 10px;
        }

        .brand-logo span {
            display: block;
            font-size: 0.7rem;
            color: #64748b;
            font-weight: normal;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s;
        }

        /* Style Active & Hover */
        .nav-item:hover, .nav-item.active {
            background: var(--primary-blue);
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 30px 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #6366f1;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Banner Welcome */
        .welcome-banner {
            background: linear-gradient(90deg, #2563eb 0%, #a855f7 100%);
            color: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
        }

        .stat-card h4 { color: #64748b; font-size: 0.85rem; margin-bottom: 5px; }
        .stat-card .number { font-size: 1.8rem; font-weight: bold; color: #1e293b; }

        /* Bottom Section Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 25px;
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .panel h3 { margin-bottom: 20px; font-size: 1.1rem; }

        .event-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            transition: 0.2s;
        }

        .event-item:hover {
            background: #f1f5f9;
        }

        .badge {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand-logo">
            CAMPUS-EVENT
            <span>Sistem Manajemen Event Terintegrasi</span>
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            @if(Auth::check() && Auth::user()->email === 'admin@gmail.com')
                <a href="{{ route('admin.events.index') }}" class="nav-item">
                    <i class="fas fa-calendar-alt"></i> Event & Kategori
                </a>
            @endif

            <!-- MODUL 5: LINK TRANSAKSI -->
            <a href="{{ route('admin.transactions.index') }}" 
               class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Transaksi & Tiket
            </a>

            <a href="#" class="nav-item"><i class="fas fa-store"></i> Tenant & Sponsor</a>
            <a href="#" class="nav-item"><i class="fas fa-certificate"></i> Sertifikat & Feedback</a>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <h2 style="color: #1e293b;">Dashboard</h2>
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                </div>
                <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </div>

        <div class="welcome-banner">
            <h1 style="font-size: 1.8rem; margin-bottom: 10px;">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p style="opacity: 0.9;">Pantau penjualan tiket teater dan status pembayaran real-time.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #3b82f6;"><i class="fas fa-ticket-alt"></i></div>
                <h4>Total Transaksi</h4>
                <div class="number">124</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #22c55e;"><i class="fas fa-check-double"></i></div>
                <h4>Lunas</h4>
                <div class="number">98</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #a855f7;"><i class="fas fa-clock"></i></div>
                <h4>Pending</h4>
                <div class="number">26</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
                <h4>Gagal</h4>
                <div class="number">5</div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Event Terbaru Panel -->
            <div class="panel">
                <h3>Event Mendatang</h3>
                <div class="event-item">
                    <div>
                        <div style="font-weight: bold; margin-bottom: 5px;">Teater Romeo & Juliet 2025</div>
                        <div style="font-size: 0.85rem; color: #64748b;">
                            <i class="far fa-clock"></i> 20 Jan 2025 &nbsp; <i class="far fa-user"></i> 120 Peserta
                        </div>
                    </div>
                    <span class="badge">Coming Soon</span>
                </div>
                <div class="event-item">
                    <div>
                        <div style="font-weight: bold; margin-bottom: 5px;">Workshop Akting Dasar</div>
                        <div style="font-size: 0.85rem; color: #64748b;">
                            <i class="far fa-clock"></i> 25 Jan 2025 &nbsp; <i class="far fa-user"></i> 45 Peserta
                        </div>
                    </div>
                    <span class="badge">Coming Soon</span>
                </div>
            </div>

            <!-- AKSI CEPAT ADMIN -->
            <div class="panel">
                <h3>Aksi Cepat Admin</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    
                    <!-- MENU KE DAFTAR TRANSAKSI -->
                    <a href="{{ route('admin.transactions.index') }}">
                        <div class="event-item" style="background: #eff6ff; border: 1px solid #bfdbfe; cursor: pointer;">
                            <div>
                                <div style="font-weight: bold; color: #1e40af;">Kelola Transaksi</div>
                                <div style="font-size: 0.8rem; color: #64748b;">Verifikasi pembayaran & cek API</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #1e40af;"></i>
                        </div>
                    </a>

                    <!-- MENU DOWNLOAD PDF -->
                    <a href="{{ route('admin.transactions.exportPdf') }}">
                        <div class="event-item" style="background: #fff1f2; border: 1px solid #fecdd3; cursor: pointer;">
                            <div>
                                <div style="font-weight: bold; color: #be123c;">Laporan Penjualan (PDF)</div>
                                <div style="font-size: 0.8rem; color: #64748b;">Download laporan lengkap format PDF</div>
                            </div>
                            <i class="fas fa-file-pdf" style="color: #be123c;"></i>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>

</body>
</html>