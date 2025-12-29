<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru - CAMPUS-EVENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Card Full Width */
        .card { 
            background: white; 
            border-radius: 12px; 
            padding: 30px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            width: 100%; /* Membuat card melebar penuh */
        }

        .section-title { font-size: 1.1rem; font-weight: bold; margin-bottom: 25px; color: #1e293b; }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; color: #334155; }
        
        input, textarea, select {
            width: 100%; padding: 12px; border-radius: 8px;
            border: 1px solid #e2e8f0; background: #fff; font-size: 0.9rem;
        }

        input:focus, textarea:focus, select:focus {
            outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(0, 97, 255, 0.1);
        }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .btn-primary { 
            background: var(--primary-blue); color: white; border: none; 
            padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { background: #0052d4; }

        .btn-secondary {
            background: #f1f5f9; color: #475569; text-decoration: none;
            padding: 12px 25px; border-radius: 8px; font-weight: bold; margin-left: 10px;
        }
        .btn-secondary:hover { background: #e2e8f0; }

        .alert-error {
            background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 15px;
            border-radius: 6px; margin-bottom: 20px; color: #991b1b;
        }
        .alert-error li { margin-left: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">CAMPUS-EVENT <span>Sistem Manajemen Event Terintegrasi</span></div>
        <nav>
            <a href="#" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="{{ route('admin.events.index') }}" class="nav-item active"><i class="fas fa-calendar-alt"></i> Event & Kategori</a>
            <a href="#" class="nav-item"><i class="fas fa-ticket-alt"></i> Transaksi & Tiket</a>
            <a href="#" class="nav-item"><i class="fas fa-store"></i> Tenant & Sponsor</a>
            <a href="#" class="nav-item"><i class="fas fa-certificate"></i> Sertifikat & Feedback</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2 style="font-size: 1.5rem;">Buat Event Baru</h2>
                <p style="color: #64748b; font-size: 0.85rem;">Tambahkan event baru ke dalam sistem</p>
            </div>
            <div class="user-info">
                <div>
                    <div style="font-weight: bold;">Admin Teater</div>
                    <div style="font-size: 0.75rem; color: #64748b;">admin@gmail.com</div>
                </div>
                <div style="width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">A</div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <strong><i class="fas fa-exclamation-circle"></i> Terjadi kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf
            
            <div class="card">
                <h3 class="section-title">Informasi Dasar</h3>

                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event') }}" placeholder="Masukkan nama event" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Event</label>
                    <textarea name="deskripsi" rows="5" placeholder="Masukkan deskripsi event">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal_event" value="{{ old('tanggal_event') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status Event</label>
                        <select name="status_event" required>
                            <option value="">Pilih Status...</option>
                            <option value="aktif" {{ old('status_event') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_event') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi / Link Meeting</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi atau link meeting">
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-plus"></i> Tambah Event
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
