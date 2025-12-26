<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori Tiket - {{ $event->nama_event }}</title>
    <style>body{font-family:Arial;padding:20px}table{width:100%;border-collapse:collapse}td,th{border:1px solid #ddd;padding:8px}</style>
</head>
<body>
    <h1>Kategori Tiket untuk: {{ $event->nama_event }}</h1>
    @if(session('success')) <div style="background:#d1fae5;padding:8px">{{ session('success') }}</div> @endif
    <p><a href="{{ route('admin.events.categories.create', $event) }}">Tambah Kategori</a> | <a href="{{ route('admin.events.index') }}">Kembali</a></p>
    <table>
        <thead><tr><th>Nama</th><th>Harga</th><th>Jumlah</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->price }}</td>
                    <td>{{ $cat->quantity }}</td>
                    <td>
                        <a href="{{ route('admin.events.categories.edit', [$event, $cat]) }}">Edit</a>
                        <form action="{{ route('admin.events.categories.destroy', [$event, $cat]) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
