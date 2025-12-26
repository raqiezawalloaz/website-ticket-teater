<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $event->nama_event }}</title>
    <style>body{font-family:Arial;padding:20px}</style>
</head>
<body>
    <h1>{{ $event->nama_event }}</h1>
    <div>{{ $event->tanggal_event->format('Y-m-d H:i') }} — {{ $event->lokasi }}</div>
    <p>{{ $event->deskripsi }}</p>
    <h3>Kategori Tiket</h3>
    <ul>
        @foreach($event->ticketCategories as $cat)
            <li>{{ $cat->name }} - Rp {{ $cat->price }} ({{ $cat->quantity }} tersedia)</li>
        @endforeach
    </ul>
    <p><a href="{{ route('events.index') }}">Kembali</a></p>
</body>
</html>
