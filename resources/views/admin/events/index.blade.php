<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Event - CAMPUS-EVENT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0061ff;
            --bg-gray: #f8fafc;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-gray); display: flex; }

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
        .nav-item.active { background: var(--primary-blue); color: white; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; padding: 30px 40px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        /* Table & Controls */
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .controls { display: flex; justify-content: space-between; margin-bottom: 20px; gap: 15px; }
        .search-box { flex: 1; position: relative; }
        .search-box input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .search-box i { position: absolute; left: 15px; top: 12px; color: #94a3b8; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; padding: 15px; border-bottom: 1px solid #f1f5f9; }
        td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.9rem; }

        .btn-add { background: var(--primary-blue); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; }
        .status-success { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }

        .action-icons { display: flex; gap: 15px; color: #6366f1; }
        .action-icons i { cursor: pointer; }
        .delete-btn { background: none; border: none; color: #ef4444; cursor: pointer; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">CAMPUS-EVENT <span>Sistem Manajemen Event Terintegrasi</span></div>
        <a href="#" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="#" class="nav-item active"><i class="fas fa-calendar-alt"></i> Event & Kategori</a>
        <a href="#" class="nav-item"><i class="fas fa-ticket-alt"></i> Transaksi & Tiket</a>
        <a href="#" class="nav-item"><i class="fas fa-store"></i> Tenant & Sponsor</a>
        <a href="#" class="nav-item"><i class="fas fa-certificate"></i> Sertifikat & Feedback</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Manajemen Event</h2>
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                </div>
                <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </div>

        @if(session('success')) 
            <div style="background:#d1fae5; color: #065f46; padding:15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div> 
        @endif

        <div class="card">
            <div class="controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari event...">
                </div>
                <select style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; color: #64748b;">
                    <option>Semua Status</option>
                </select>
                <a href="{{ route('admin.events.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Buat Event Baru
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <div style="font-weight: bold; color: #1e293b;">{{ $event->nama_event }}</div>
                            </td>
                            <td style="color: #64748b;">{{ $event->tanggal_event }}</td>
                            <td style="color: #64748b;">{{ $event->lokasi }}</td>
                            <td>
                                <span class="status-badge {{ $event->status_event == 'Aktif' ? 'status-success' : 'status-pending' }}">
                                    <i class="fas {{ $event->status_event == 'Aktif' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                    {{ $event->status_event }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('admin.events.edit', $event) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.events.categories.index', $event) }}" title="Kategori"><i class="fas fa-list"></i></a>
                                    <a href="{{ route('admin.events.export', $event) }}" title="Export"><i class="fas fa-download" style="color: #10b981;"></i></a>
                                    
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Belum ada data event tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>