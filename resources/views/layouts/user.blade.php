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
            --primary: #6366f1;
            --primary-light: #818cf8;
            --secondary: #a855f7;
            --bg-body: #f1f5f9;
            --sidebar-bg: rgba(255, 255, 255, 0.9);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-width: 280px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); display: flex; min-height: 100vh; color: var(--text-main); }

        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-width); 
            background: var(--sidebar-bg); 
            backdrop-filter: blur(10px);
            height: 100vh; 
            position: fixed; 
            padding: 30px 20px; 
            border-right: 1px solid rgba(226, 232, 240, 0.8); 
            z-index: 100;
        }
        .brand-logo { 
            font-weight: 800; font-size: 1.4rem; margin-bottom: 40px; padding-left: 10px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }
        .brand-logo span { display: block; font-size: 0.7rem; color: var(--text-muted); -webkit-text-fill-color: var(--text-muted); font-weight: 500; margin-top: 4px; }
        
        .nav-item {
            padding: 14px 18px; margin-bottom: 8px; border-radius: 12px;
            color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500; font-size: 0.95rem;
        }
        .nav-item:hover { background: #f8fafc; color: var(--primary); transform: translateX(5px); }
        .nav-item.active { 
            background: var(--primary); color: white; 
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }
        .nav-item i { width: 22px; text-align: center; font-size: 1.1rem; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 40px 60px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        
        /* Dropdown Profile */
        .dropdown-container { position: relative; display: inline-block; }
        .profile-trigger { display: flex; align-items: center; gap: 14px; cursor: pointer; background: white; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 100px; transition: 0.2s; }
        .profile-trigger:hover { border-color: var(--primary); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .avatar { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; }
        
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
            
            <!-- User Transactions (My Tickets) -->
            <a href="{{ route('user.tickets.index') }}" class="nav-item {{ request()->routeIs('user.tickets.index') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Tiket Saya
            </a>
            
            <a href="{{ route('public.tenants.index') }}" class="nav-item {{ request()->routeIs('public.tenants.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Daftar Tenant
            </a>

            <a href="{{ route('public.sponsors.index') }}" class="nav-item {{ request()->routeIs('public.sponsors.*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i> Daftar Sponsor
            </a>
            
            <a href="{{ route('feedback.index') }}" class="nav-item {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                <i class="fas fa-comment-dots"></i> Feedback
            </a>
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
    @yield('scripts')
</body>
</html>
