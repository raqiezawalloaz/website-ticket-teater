<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buat Event Baru</title>
    <style>body{font-family:Arial;padding:20px}label{display:block;margin-top:8px}input,textarea,select{width:100%;padding:8px}</style>
</head>
<body>
    <h1>Buat Event Baru</h1>
    @if($errors->any())<div style="background:#fee2e2;padding:8px">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf
        <label>Nama Event</label>
        <input name="nama_event" value="{{ old('nama_event') }}" required>
        <label>Deskripsi</label>
        <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
        <label>Tanggal</label>
        <input name="tanggal_event" type="datetime-local" value="{{ old('tanggal_event') }}" required>
        <label>Lokasi</label>
        <input name="lokasi" value="{{ old('lokasi') }}">
        <label>Status</label>
        <select name="status_event">
            <option value="aktif" {{ old('status_event') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ old('status_event') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <div style="margin-top:10px"><button type="submit">Simpan</button> <a href="{{ route('admin.events.index') }}">Batal</a></div>
    </form>
</body>
</html>
