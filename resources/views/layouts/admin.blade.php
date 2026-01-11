<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - CAMPUS-EVENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0061ff;
            --bg-gray: #f8fafc;
            --text-dark: #334155;
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
            z-index: 100;
        }
        .brand-logo { font-weight: bold; font-size: 1.2rem; margin-bottom: 30px; padding-left: 10px; }
        .brand-logo span { display: block; font-size: 0.7rem; color: #64748b; font-weight: normal; }
        
        .nav-item {
            padding: 12px 15px; margin-bottom: 5px; border-radius: 8px;
            color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 12px;
            transition: 0.3s;
        }
        .nav-item:hover { background: #f1f5f9; color: var(--primary-blue); }
        .nav-item.active { background: var(--primary-blue); color: white; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px 40px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
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
        .profile-trigger:hover { background: #f1f5f9; }

        .avatar {
            width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-weight: bold;
        }

        .dropdown-menu {
            position: absolute; top: 100%; right: 0; background: white; border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15); min-width: 200px; margin-top: 8px;
            opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;
            z-index: 1000;
        }
        .dropdown-menu.active { opacity: 1; visibility: visible; transform: translateY(0); }

        .dropdown-menu a, .dropdown-menu form button {
            display: block; width: 100%; padding: 12px 16px; text-align: left;
            color: #475569; text-decoration: none; border: none; background: none;
            cursor: pointer; font-size: 0.9rem; transition: all 0.2s ease;
        }
        .dropdown-menu a:hover, .dropdown-menu form button:hover { background: #f1f5f9; color: var(--primary-blue); }
        .dropdown-divider { height: 1px; background: #e2e8f0; margin: 4px 0; }

        /* Common Components */
        .card { 
            background: white; border-radius: 12px; padding: 30px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%;
        }

        .alert-success {
            background: #dcfce7; border-left: 4px solid #22c55e; padding: 12px 15px;
            border-radius: 6px; margin-bottom: 20px; color: #166534;
        }

        /* Nav Tabs for Sections sharing same sidebar item */
        .nav-tabs { display: flex; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; }
        .nav-tab {
            padding: 10px 20px; text-decoration: none; color: #64748b; font-weight: 500;
            border-bottom: 2px solid transparent; transition: all 0.2s;
        }
        .nav-tab:hover { color: var(--primary-blue); }
        .nav-tab.active { color: var(--primary-blue); border-bottom-color: var(--primary-blue); }

        /* Submenu Styling */
        .nav-group { margin-bottom: 5px; }
        .nav-item-header {
            padding: 12px 15px; border-radius: 8px; color: #64748b;
            text-decoration: none; display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; transition: 0.3s;
        }
        .nav-item-header:hover { background: #f1f5f9; color: var(--primary-blue); }
        .nav-item-header.active { background: #eff6ff; color: var(--primary-blue); font-weight: 600; }
        
        .nav-item-content { display: flex; align-items: center; gap: 12px; }

        .submenu {
            max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;
            padding-left: 15px;
        }
        .submenu.open { max-height: 500px; transition: max-height 0.3s ease-in; }
        
        .submenu a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 15px 10px 32px; /* Indent child */
            font-size: 0.9rem; color: #64748b; text-decoration: none;
            border-radius: 8px; margin-top: 2px;
            position: relative;
        }
        .submenu a::before {
            content: ''; position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
            width: 4px; height: 4px; background: #cbd5e1; border-radius: 50%;
        }
        .submenu a:hover { color: var(--primary-blue); background: #f8fafc; }
        .submenu a.active { color: var(--primary-blue); background: white; font-weight: 500; }
        .submenu a.active::before { background: var(--primary-blue); }

        .arrow-icon { transition: transform 0.3s; font-size: 0.8rem; }
        .nav-group.active .arrow-icon { transform: rotate(180deg); }

    </style>
    @yield('styles')
</head>
<body>

    <div class="sidebar">
        <div class="brand-logo">CAMPUS-EVENT <span>Sistem Manajemen Terintegrasi</span></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.events.index') }}" class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Event & Kategori
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Transaksi & Tiket
                </a>
                
                <!-- MENU GABUNGAN (Tenant & Sponsor) -->
                <a href="{{ route('admin.tenants.index') }}" class="nav-item {{ request()->routeIs('admin.tenants.*') || request()->routeIs('admin.sponsors.*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Tenant & Sponsor
                </a>
                
                <!-- MENU BARU (Sertifikat) - Dipisah agar fokus -->
                <a href="{{ route('admin.certificates.index') }}" class="nav-item {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i> Sertifikat
                </a>

                <!-- MENU LAMA (Feedback) - Dipisah karena fungsinya berbeda -->
                <a href="{{ route('feedback.index') }}" class="nav-item {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                    <i class="fas fa-comment-dots"></i> Feedback
                </a>

            @else
                <a href="{{ route('events.index') }}" class="nav-item">
                     <i class="fas fa-calendar-alt"></i> Event & Kategori
                </a>
            @endif
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2 style="font-size: 1.5rem; color: #1e293b;">@yield('header_title')</h2>
                <p style="color: #64748b; font-size: 0.85rem;">@yield('header_subtitle')</p>
            </div>
            
            <div class="dropdown-container">
                <button class="profile-trigger" onclick="toggleDropdown()">
                    <div style="text-align: right;">
                        <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </button>
                <div class="dropdown-menu" id="profileDropdown">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}" onclick="closeDropdown()">
                            <i class="fas fa-users"></i> Kelola Pengguna
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="closeDropdown()">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        function toggleSubmenu(header) {
            const group = header.parentElement;
            const submenu = group.querySelector('.submenu');
            
            group.classList.toggle('active');
            submenu.classList.toggle('open');
        }

        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
        }
        function closeDropdown() {
            document.getElementById('profileDropdown').classList.remove('active');
        }
        document.addEventListener('click', function(event) {
            if (!document.querySelector('.dropdown-container').contains(event.target)) {
                closeDropdown();
            }
        });
    </script>
</body>
</html>