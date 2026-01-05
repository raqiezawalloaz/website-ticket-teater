<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tiket - CAMPUS-EVENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('user.transactions.index') }}" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Tiket</h1>
            <div class="w-20"></div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $transaction->event_name }}</h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($transaction->status === 'success')
                        bg-green-100 text-green-800
                    @elseif($transaction->status === 'pending')
                        bg-yellow-100 text-yellow-800
                    @elseif($transaction->status === 'failed')
                        bg-red-100 text-red-800
                    @else
                        bg-gray-100 text-gray-800
                    @endif
                ">
                    @if($transaction->status === 'success')
                        <i class="fas fa-check-circle mr-2"></i>Lunas
                    @elseif($transaction->status === 'pending')
                        <i class="fas fa-hourglass-half mr-2"></i>Menunggu Pembayaran
                    @elseif($transaction->status === 'failed')
                        <i class="fas fa-times-circle mr-2"></i>Pembayaran Gagal
                    @else
                        <i class="fas fa-question-circle mr-2"></i>{{ ucfirst($transaction->status) }}
                    @endif
                </span>
            </div>
            <p class="text-gray-600">Nomor Referensi: <span class="font-mono font-semibold">{{ $transaction->reference_number }}</span></p>
        </div>

        <!-- Ticket Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Informasi Event -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Informasi Event
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-500 text-sm">Nama Event</p>
                        <p class="text-gray-800 font-semibold">{{ $transaction->event_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Kategori Tiket</p>
                        <p class="text-gray-800 font-semibold uppercase">{{ $transaction->ticket_category }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tanggal Event</p>
                        <p class="text-gray-800 font-semibold">
                            @if($transaction->event)
                                {{ $transaction->event->tanggal_event->format('d M Y - H:i') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Lokasi</p>
                        <p class="text-gray-800 font-semibold">
                            @if($transaction->event)
                                {{ $transaction->event->lokasi }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembeli -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user text-blue-600 mr-2"></i>Informasi Pembeli
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-500 text-sm">Nama Lengkap</p>
                        <p class="text-gray-800 font-semibold">{{ $transaction->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Email</p>
                        <p class="text-gray-800 font-semibold">{{ $transaction->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Harga Tiket</p>
                        <p class="text-gray-800 font-semibold text-lg">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tanggal Pembelian</p>
                        <p class="text-gray-800 font-semibold">{{ $transaction->created_at->format('d M Y - H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
            <div class="flex flex-wrap gap-3">
                @if($transaction->status === 'success')
                    <!-- Download Tiket -->
                    <a href="{{ route('user.transactions.download', $transaction->id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-download mr-2"></i>Download E-Tiket
                    </a>
                @elseif($transaction->status === 'pending')
                    <!-- Lanjutkan Pembayaran -->
                    @if($transaction->payment_url)
                        <a href="{{ $transaction->payment_url }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-credit-card mr-2"></i>Lanjutkan Pembayaran
                        </a>
                    @endif
                    <!-- Cek Status -->
                    <form action="{{ route('admin.transactions.checkStatus', $transaction->id) }}" method="GET" class="inline">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-sync mr-2"></i>Cek Status
                        </button>
                    </form>
                @elseif($transaction->status === 'failed')
                    <!-- Bayar Ulang -->
                    @if($transaction->payment_url)
                        <a href="{{ $transaction->payment_url }}" 
                           class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                            <i class="fas fa-redo mr-2"></i>Bayar Ulang
                        </a>
                    @endif
                @endif
                
                <!-- Kembali ke Daftar -->
                <a href="{{ route('user.transactions.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Timeline Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-stream text-blue-600 mr-2"></i>Status Pembayaran
            </h3>
            <div class="space-y-4">
                <!-- Created -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-800 font-semibold">Pesanan Dibuat</p>
                        <p class="text-gray-500 text-sm">{{ $transaction->created_at->format('d M Y - H:i') }}</p>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full 
                            @if($transaction->status === 'success')
                                bg-green-600
                            @elseif($transaction->status === 'pending')
                                bg-yellow-600
                            @elseif($transaction->status === 'failed')
                                bg-red-600
                            @else
                                bg-gray-600
                            @endif
                        ">
                            @if($transaction->status === 'success')
                                <i class="fas fa-check text-white"></i>
                            @elseif($transaction->status === 'pending')
                                <i class="fas fa-hourglass-half text-white"></i>
                            @elseif($transaction->status === 'failed')
                                <i class="fas fa-times text-white"></i>
                            @else
                                <i class="fas fa-question text-white"></i>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-800 font-semibold">
                            @if($transaction->status === 'success')
                                Pembayaran Berhasil
                            @elseif($transaction->status === 'pending')
                                Menunggu Pembayaran
                            @elseif($transaction->status === 'failed')
                                Pembayaran Gagal
                            @else
                                {{ ucfirst($transaction->status) }}
                            @endif
                        </p>
                        @if($transaction->paid_at)
                            <p class="text-gray-500 text-sm">{{ $transaction->paid_at->format('d M Y - H:i') }}</p>
                        @else
                            <p class="text-gray-500 text-sm">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
