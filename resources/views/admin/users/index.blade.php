<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - CAMPUS-EVENT</title>
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
            width: 100%;
        }

        .section-title { font-size: 1.1rem; font-weight: bold; margin-bottom: 25px; color: #1e293b; }

        .btn-primary { 
            background: var(--primary-blue); color: white; border: none; 
            padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none;
        }
        .btn-primary:hover { background: #0052d4; }

        .btn-edit {
            background: #3b82f6; color: white; border: none;
            padding: 6px 12px; border-radius: 6px; cursor: pointer;
            font-size: 0.85rem; text-decoration: none;
        }
        .btn-edit:hover { background: #2563eb; }

        .btn-delete {
            background: #ef4444; color: white; border: none;
            padding: 6px 12px; border-radius: 6px; cursor: pointer;
            font-size: 0.85rem;
        }
        .btn-delete:hover { background: #dc2626; }

        /* Table Styling */
        .table {
            width: 100%; border-collapse: collapse;
        }
        .table th {
            background: #f1f5f9; padding: 15px; text-align: left;
            font-weight: 600; color: #334155; border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            padding: 15px; border-bottom: 1px solid #e2e8f0;
        }
        .table tr:hover { background: #f8fafc; }

        .badge {
            display: inline-block; padding: 4px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600;
        }
        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-user { background: #dcfce7; color: #166534; }

        .alert-success {
            background: #dcfce7; border-left: 4px solid #22c55e; padding: 12px 15px;
            border-radius: 6px; margin-bottom: 20px; color: #166534;
        }

        .action-buttons { display: flex; gap: 10px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">CAMPUS-EVENT <span>Sistem Manajemen Event Terintegrasi</span></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="{{ route('admin.events.index') }}" class="nav-item"><i class="fas fa-calendar-alt"></i> Event & Kategori</a>
            <a href="{{ route('admin.users.index') }}" class="nav-item active"><i class="fas fa-users"></i> Kelola Pengguna</a>
            <a href="#" class="nav-item"><i class="fas fa-ticket-alt"></i> Transaksi & Tiket</a>
            <a href="#" class="nav-item"><i class="fas fa-store"></i> Tenant & Sponsor</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2 style="font-size: 1.5rem;">Kelola Pengguna</h2>
                <p style="color: #64748b; font-size: 0.85rem;">Kelola daftar pengguna sistem</p>
            </div>
            <div class="user-info">
                <div>
                    <div style="font-weight: bold;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                </div>
                <div style="width: 40px; height: 40px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">{{ substr(Auth::user()->name, 0, 1) }}</div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 class="section-title">Daftar Pengguna</h3>
                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </a>
            </div>

            @if($users->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 36px; height: 36px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td style="color: #64748b; font-size: 0.85rem;">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                    <p>Belum ada pengguna</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
