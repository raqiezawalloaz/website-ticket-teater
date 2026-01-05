<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran - CAMPUS-EVENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">

    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Loading Animation -->
            <div class="mb-6">
                <div class="inline-block">
                    <div class="w-16 h-16 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Memproses Pembayaran</h1>
            <p class="text-gray-600 mb-4">Mohon tunggu sebentar...</p>

            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded text-left text-sm text-gray-700">
                <p class="mb-2"><strong>Catatan:</strong> Anda sedang dialihkan ke gateway pembayaran Midtrans.</p>
                <p>Jika halaman tidak berubah dalam 5 detik, silakan <a href="javascript:location.reload()" class="text-blue-600 font-semibold">klik di sini</a>.</p>
            </div>

            <!-- Fallback Button (jika JS gagal) -->
            <div class="mt-6">
                <a href="{{ route('user.transactions.index') }}" class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-home mr-2"></i>Kembali ke Tiket Saya
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh jika masih di halaman ini setelah 5 detik
        setTimeout(() => {
            location.href = "{{ route('user.transactions.index') }}";
        }, 5000);
    </script>

</body>
</html>
