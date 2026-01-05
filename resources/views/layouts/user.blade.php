<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Event')</title>
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

        /* Sidebar Styling (Matches Admin) */
        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; position: fixed; padding: 20px; border-right: 1px solid #e2e8f0; z-index: 100; }
        .brand-logo { font-weight: bold; font-size: 1.2rem; margin-bottom: 30px; padding-left: 10px; }
        .brand-logo span { display: block; font-size: 0.7rem; color: #64748b; font-weight: normal; }
        
        .nav-item {
            padding: 12px 15px; margin-bottom: 5px; border-radius: 8px;
            color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 12px;
            transition: 0.3s;
        }
        .nav-item:hover, .nav-item.active { background: var(--primary-blue); color: white; }
        .nav-item i { width: 20px; text-align: center; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Dropdown Profile */
        .dropdown-container { position: relative; display: inline-block; }
        .profile-trigger { display: flex; align-items: center; gap: 12px; cursor: pointer; background: none; border: none; padding: 5px; }
        .avatar { width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        .dropdown-menu {
            position: absolute; top: 100%; right: 0; background: white; border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); min-width: 200px; margin-top: 10px;
            opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s; z-index: 1000;
        }
        .dropdown-menu.active { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu a, .dropdown-menu button {
            display: block; width: 100%; padding: 12px 20px; text-align: left; color: #475569;
            border: none; background: none; cursor: pointer; text-decoration: none; font-size: 0.9rem;
        }
        .dropdown-menu a:hover, .dropdown-menu button:hover { background: #f8fafc; color: var(--primary-blue); }
        .dropdown-divider { border-top: 1px solid #e2e8f0; margin: 5px 0; }

        /* Additional Styles Yield */
        @yield('styles')
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand-logo">CAMPUS-EVENT <span>Sistem Manajemen Terintegrasi</span></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Daftar Event
            </a>
            
            <!-- User Transactions (My Tickets) - Assuming route exists or placeholder -->
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Tiket Saya & Sertifikat
            </a>
            
            <a href="{{ route('public.tenants.index') }}" class="nav-item {{ request()->routeIs('public.tenants.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Tenant & Sponsor
            </a>
            
            <!-- Removed redundant Certification link as it's part of Dashboard/Tiket Saya now -->
            <!-- If specific separate page desired, separate route needed, but Dashboard handles it nicely -->
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2 style="font-size: 1.5rem; color: #1e293b;">@yield('header_title', 'Campus Event')</h2>
                <p style="color: #64748b; font-size: 0.85rem;">@yield('header_subtitle', 'Platform Event Kampus Terintegrasi')</p>
            </div>
            
            <div class="dropdown-container">
                @auth
                    <button class="profile-trigger" onclick="toggleDropdown()">
                        <div style="text-align: right; display: none; @media(min-width: 768px){display:block;}">
                            <div style="font-weight: bold; color: #1e293b;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </button>
                    <div class="dropdown-menu" id="profileDropdown">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-user-circle"></i> Dashboard</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                        </form>
                    </div>
                @else
                    <div style="display: flex; gap: 15px;">
                        <a href="{{ route('login') }}" style="text-decoration: none; color: var(--primary-blue); font-weight: 600;">Masuk</a>
                        <a href="{{ route('register') }}" style="text-decoration: none; color: var(--primary-blue); font-weight: 600;">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>

        @yield('content')
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('active');
        }
        document.addEventListener('click', function(event) {
            if (!document.querySelector('.dropdown-container').contains(event.target)) {
                document.getElementById('profileDropdown').classList.remove('active');
            }
        });
    </script>
</body>
</html>
