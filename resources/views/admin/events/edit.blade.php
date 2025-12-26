<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - CAMPUS-EVENT</title>
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

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .btn-primary { 
            background: var(--primary-blue); color: white; border: none; 
            padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-secondary {
            background: #f1f5f9; color: #475569; text-decoration: none;
            padding: 12px 25px; border-radius: 8px; font-weight: bold; margin-left: 10px;
        }
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
                <h2 style="font-size: 1.5rem;">Edit Event</h2>
                <p style="color: #64748b; font-size: 0.85rem;">Perbarui informasi detail event Anda</p>
            </div>
            <div class="user-info">
                <div>
                    <div style="font-weight: bold;">Admin Teater</div>
                    <div style="font-size: 0.75rem; color: #64748b;">admin@gmail.com</div>
                </div>
                <div style="width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">A</div>
            </div>
        </div>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card">
                <h3 class="section-title">Informasi Dasar</h3>

                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Event</label>
                    <textarea name="deskripsi" rows="5">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal_event" 
                               value="{{ old('tanggal_event', date('Y-m-d\TH:i', strtotime($event->tanggal_event))) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status Event</label>
                        <select name="status_event">
                            <option value="Aktif" {{ $event->status_event == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ $event->status_event == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Lokasi / Link Meeting</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}">
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>