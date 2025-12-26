<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Event - Teater Ticket</title>
    <style>body{font-family:Arial;padding:20px}article{background:#fff;padding:16px;border:1px solid #eee;margin-bottom:12px;border-radius:8px}</style>
</head>
<body>
    <nav style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div style="font-weight:bold">Teater Ticket</div>
        <div>
            @guest
                <a href="{{ route('register') }}">Daftar</a> |
                <a href="{{ route('user.login') }}">Login</a>
            @else
                <form method="POST" action="{{ route('user.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:#2563eb;cursor:pointer;padding:0">Logout</button>
                </form>
            @endguest
        </div>
    </nav>

    <h1>Daftar Event</h1>

    @forelse($events as $e)
        <article>
            <h2>{{ $e->nama_event }}</h2>
            <div style="color:#6b7280">{{ $e->tanggal_event->format('Y-m-d H:i') }} — {{ $e->lokasi }}</div>
            <p>{{ \Illuminate\Support\Str::limit($e->deskripsi, 250) }}</p>
            <div style="margin-top:8px">Status: {{ ucfirst($e->status_event) }}</div>
        </article>
    @empty
        <p>Belum ada event.</p>
    @endforelse

</body>
</html>
