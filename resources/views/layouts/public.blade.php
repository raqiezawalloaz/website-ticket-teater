<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Event')</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0061ff;
            --secondary: #64748b;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { background-color: var(--bg-light); color: var(--text-dark); }

        /* Navbar */
        .navbar {
            background: white;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-brand { font-size: 1.5rem; font-weight: 700; color: var(--primary); text-decoration: none; }
        .nav-links { display: flex; gap: 20px; }
        .nav-link { color: var(--text-dark); text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover { color: var(--primary); }
        .nav-link.active { color: var(--primary); }

        .btn-login {
            background: var(--primary); color: white; padding: 0.5rem 1.5rem; 
            border-radius: 6px; text-decoration: none; font-weight: 600;
            transition: background 0.3s;
        }
        .btn-login:hover { background: #0052d4; }

        /* Container */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        /* Footer */
        footer {
            background: white;
            padding: 2rem 5%;
            text-align: center;
            margin-top: 3rem;
            color: var(--secondary);
            border-top: 1px solid #e2e8f0;
        }
        
        /* Utility */
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        .font-bold { font-weight: 700; }
        .text-muted { color: var(--secondary); }

        @yield('styles')
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ url('/') }}" class="nav-brand">CAMPUS-EVENT</a>
        <div class="nav-links">
            <a href="{{ route('events.index') }}" class="nav-link">Event</a>
            <a href="{{ route('public.tenants.index') }}" class="nav-link {{ request()->routeIs('public.tenants.*') ? 'active' : '' }}">Tenant</a>
            <a href="{{ route('public.sponsors.index') }}" class="nav-link {{ request()->routeIs('public.sponsors.*') ? 'active' : '' }}">Sponsor</a>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-login">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Campus Event. All rights reserved.</p>
    </footer>

</body>
</html>
