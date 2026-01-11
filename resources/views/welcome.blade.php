<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Ticket Teater - Pengalaman Seni yang Luar Biasa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --dark: #0f172a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { background: var(--dark); color: white; overflow-x: hidden; }

        /* Navbar */
        nav {
            position: fixed; top: 0; width: 100%; padding: 25px 80px;
            display: flex; justify-content: space-between; align-items: center;
            z-index: 1000; transition: 0.3s;
        }
        nav.scrolled { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); padding: 15px 80px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .logo { font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; background: linear-gradient(90deg, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links { display: flex; gap: 40px; }
        .nav-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
        .nav-links a:hover { color: white; }
        .btn-auth { background: white; color: var(--dark); padding: 10px 25px; border-radius: 100px; text-decoration: none; font-weight: 700; transition: 0.3s; }
        .btn-auth:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255,255,255,0.1); }

        /* Hero Section */
        .hero {
            height: 100vh; position: relative;
            background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.9)), url('/assets/images/hero_theater.png');
            background-size: cover; background-position: center;
            display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;
            padding: 0 20px;
        }
        .hero h1 { font-family: 'Playfair Display', serif; font-size: 5rem; margin-bottom: 20px; line-height: 1.1; }
        .hero p { font-size: 1.25rem; color: rgba(255,255,255,0.8); max-width: 600px; margin-bottom: 40px; }
        .btn-primary { 
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white; padding: 18px 45px; border-radius: 100px; text-decoration: none;
            font-weight: 800; font-size: 1.1rem; transition: 0.3s;
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover { transform: scale(1.05); }

        /* Stats Section */
        .stats { display: flex; gap: 80px; margin-top: 80px; }
        .stat-item h3 { font-size: 2.5rem; font-weight: 800; }
        .stat-item p { font-size: 0.85rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; }

        /* Responsive */
        @media (max-width: 768px) {
            nav { padding: 20px; }
            .nav-links { display: none; }
            .hero h1 { font-size: 3rem; }
            .stats { gap: 40px; flex-wrap: wrap; justify-content: center; }
        }
    </style>
</head>
<body>

    <nav id="navbar">
        <div class="logo">TICKET TEATER.</div>
        <div class="nav-links">
            <a href="#about">Tentang Kami</a>
            <a href="#events">Jadwal Teater</a>
            <a href="#tenant">Tenant & Sponsor</a>
        </div>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-auth">Dashboard</a>
        @else
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="{{ route('login') }}" style="color: white; text-decoration: none; font-weight: 600;">Masuk</a>
                <a href="{{ route('register') }}" class="btn-auth">Daftar Sekarang</a>
            </div>
        @endauth
    </nav>

    <div class="hero">
        <h1 class="animate-up">Saksikan Keajaiban<br>Panggung Teater</h1>
        <p class="animate-up" style="animation-delay: 0.1s;">Pesan tiket untuk pertunjukan teater terbaik di kampus dan rasakan setiap momen dramatis secara langsung.</p>
        <a href="{{ route('events.index') }}" class="btn-primary animate-up" style="animation-delay: 0.2s;">Jelajahi Pertunjukan</a>

        <div class="stats animate-up" style="animation-delay: 0.3s;">
            <div class="stat-item">
                <h3>{{ $stats['events'] ?? 0 }}+</h3>
                <p>Pertunjukan</p>
            </div>
            <div class="stat-item">
                <h3>{{ number_format($stats['tickets'] ?? 0, 0, ',', '.') }}</h3>
                <p>Penonton</p>
            </div>
            <div class="stat-item">
                <h3>{{ $stats['tenants'] ?? 0 }}+</h3>
                <p>Tenant Seni</p>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.getElementById('navbar').classList.add('scrolled');
            } else {
                document.getElementById('navbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
