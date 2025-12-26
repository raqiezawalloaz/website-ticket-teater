<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Kategori - {{ $event->nama_event }}</title>
    <style>body{font-family:Arial;padding:20px}label{display:block;margin-top:8px}</style>
</head>
<body>
    <h1>Tambah Kategori untuk: {{ $event->nama_event }}</h1>
    <form method="POST" action="{{ route('admin.events.categories.store', $event) }}">
        @csrf
        <label>Nama<input type="text" name="name" required></label>
        <label>Harga<input type="number" name="price" step="0.01" required></label>
        <label>Jumlah<input type="number" name="quantity" required></label>
        <button type="submit">Simpan</button>
    </form>
    <p><a href="{{ route('admin.events.categories.index', $event) }}">Kembali</a></p>
</body>
</html>
