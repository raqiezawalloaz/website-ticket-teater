<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Tiket Teater</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ... (Style tetap sama seperti yang Anda buat, sudah sangat bagus) ... */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-card {
            background: white; border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%; max-width: 420px; padding: 40px;
            position: relative; overflow: hidden;
        }
        .login-card::before {
            content: ""; position: absolute; top: 0; left: 0;
            width: 100%; height: 6px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
        }
        .login-header { text-align: center; margin-bottom: 35px; }
        .login-header h1 { color: #1e293b; font-size: 26px; font-weight: 800; margin-bottom: 8px; }
        .login-header p { color: #64748b; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #334155; font-weight: 600; font-size: 14px; }
        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .form-group input { width: 100%; padding: 12px 12px 12px 45px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px; transition: all 0.3s ease; }
        .form-group input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
        .error-text { color: #ef4444; font-size: 12px; margin-top: 5px; display: block; }
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        .alert-error { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .remember-me { display: flex; align-items: center; gap: 8px; margin-bottom: 25px; font-size: 14px; color: #64748b; }
        .login-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
        .footer-links { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9; font-size: 14px; color: #64748b; }
        .footer-links a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .copyright { text-align: center; margin-top: 10px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div style="font-size: 3rem; margin-bottom: 10px;">🎭</div>
            <h1>Campus Event</h1>
            <p>Silakan masuk untuk mengelola tiket</p>
        </div>

        {{-- Menampilkan Error Global --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div><strong>Oops!</strong> Email atau password salah.</div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- HASIL MERGE: Menggunakan UI Galih & Route Laravel yang benar --}}
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="nama@email.com" 
                        required 
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        required
                    >
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="margin:0; font-weight:normal; cursor:pointer;">Ingat Saya</label>
            </div>

            <button type="submit" class="login-btn">
                Masuk Sekarang
            </button>
        </form>

        <div class="footer-links">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>

        <div class="copyright">
            &copy; {{ date('Y') }} Campus Event System. All rights reserved.
        </div>
    </div>

</body>
</html>