<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->nama_event }} - CAMPUS-EVENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0061ff;
            --bg-gray: #f8fafc;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-gray); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-right: 1px solid #e2e8f0;
        }
        .brand { font-weight: bold; font-size: 1.2rem; margin-bottom: 30px; }
        .brand span { display: block; font-size: 0.7rem; color: #64748b; font-weight: normal; }
        
        .nav-item {
            padding: 12px 15px; margin-bottom: 5px; border-radius: 8px;
            color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 12px;
        }
        .nav-item.active { background: var(--primary-blue); color: white; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px 40px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .user-info { display: flex; align-items: center; gap: 12px; text-align: right; }

        .card { 
            background: white; 
            border-radius: 12px; 
            padding: 30px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            width: 100%;
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #e2e8f0;
        }

        .event-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .event-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .event-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .meta-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .meta-item i {
            margin-top: 2px;
            opacity: 0.9;
        }

        .meta-content {
            flex: 1;
        }

        .meta-label {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 1rem;
            font-weight: 600;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .description {
            line-height: 1.8;
            color: #475569;
            font-size: 1rem;
            white-space: pre-wrap;
        }

        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .badge-aktif {
            background: #dcfce7;
            color: #166534;
        }

        .badge-nonaktif {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-buy {
            background: var(--primary-blue);
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-buy:hover {
            background: #0052d4;
            transform: translateY(-2px);
        }

        .btn-buy:active {
            transform: translateY(0);
        }

        /* Dropdown Profile */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .profile-trigger:hover {
            background: #f1f5f9;
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
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            min-width: 200px;
            margin-top: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu form button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            text-align: left;
            color: #475569;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .dropdown-menu form button:hover {
            background: #f1f5f9;
            color: var(--primary-blue);
        }

        .dropdown-menu form button i {
            margin-right: 8px;
        }
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
            <a href="{{ route('events.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div class="dropdown-container">
                @auth
                    <button class="profile-trigger" onclick="toggleDropdown()">
                        <div style="text-align: right;">
                            <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </button>
                    <div class="dropdown-menu" id="profileDropdown">
                        <form method="POST" action="{{ route('logout') }}" style="display: block;">
                            @csrf
                            <button type="submit" onclick="closeDropdown()">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('login') }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">Login</a>
                        <span style="color: #e2e8f0;">|</span>
                        <a href="{{ route('register') }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="event-header">
            <div class="event-title">{{ $event->nama_event }}</div>
            <span class="badge {{ $event->status_event == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                {{ ucfirst($event->status_event) }}
            </span>
            <div class="event-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <div class="meta-content">
                        <div class="meta-label">Tanggal & Waktu</div>
                        <div class="meta-value">{{ $event->tanggal_event->format('d M Y - H:i') }}</div>
                    </div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="meta-content">
                        <div class="meta-label">Lokasi</div>
                        <div class="meta-value">{{ $event->lokasi }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="section-title">Deskripsi Event</h2>
            <div class="description">{{ $event->deskripsi ?? 'Tidak ada deskripsi' }}</div>

            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #e2e8f0;">
                <h2 class="section-title">Kategori Tiket</h2>
                @if($event->ticketCategories->count() > 0)
                    <div style="display: grid; gap: 15px;">
                        @foreach($event->ticketCategories as $category)
                            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary-blue);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: bold; margin-bottom: 4px;">{{ $category->name }}</div>
                                        <div style="font-size: 0.9rem; color: #64748b;">
                                            Tersedia: <strong>{{ $category->quantity }}</strong> tiket
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 1.2rem; font-weight: bold; color: var(--primary-blue);">
                                            Rp {{ number_format($category->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="btn-buy" onclick="alert('Fitur pembelian tiket akan segera hadir')">
                        <i class="fas fa-ticket-alt"></i> Beli Tiket
                    </button>
                @else
                    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; text-align: center; color: #64748b;">
                        <i class="fas fa-info-circle"></i> Kategori tiket tidak tersedia untuk event ini
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        }

        function closeDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.remove('active');
            }
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const container = document.querySelector('.dropdown-container');
            if (dropdown && container && !container.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>

</body>
</html>
