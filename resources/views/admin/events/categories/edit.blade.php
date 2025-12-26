<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Kategori - {{ $event->nama_event }}</title>
    <style>body{font-family:Arial;padding:20px}label{display:block;margin-top:8px}</style>
</head>
<body>
    <h1>Edit Kategori untuk: {{ $event->nama_event }}</h1>
    <form method="POST" action="{{ route('admin.events.categories.update', [$event, $category]) }}">
        @csrf
        @method('PUT')
        <label>Nama<input type="text" name="name" value="{{ $category->name }}" required></label>
        <label>Harga<input type="number" name="price" step="0.01" value="{{ $category->price }}" required></label>
        <label>Jumlah<input type="number" name="quantity" value="{{ $category->quantity }}" required></label>
        <button type="submit">Simpan</button>
    </form>
    <p><a href="{{ route('admin.events.categories.index', $event) }}">Kembali</a></p>
</body>
</html>
