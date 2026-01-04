<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tiket - Teater</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); border: none; }
        .ticket-info { background-color: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <h4>Konfirmasi Pembayaran</h4>
                        <p class="text-muted">Selesaikan pembayaran untuk mendapatkan tiketmu.</p>
                    </div>

                    <div class="ticket-info">
                        <div class="d-flex justify-content-between">
                            <span>Nama:</span>
                            <strong>{{ $transaction->customer_name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Tiket:</span>
                            <strong>{{ $transaction->quantity }}x {{ $transaction->ticket_category }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5">
                            <span>Total:</span>
                            <strong class="text-primary">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <button id="pay-button" class="btn btn-primary btn-lg w-100">
                        Bayar Sekarang
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ url('/') }}" class="text-muted small">Batalkan Pesanan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        // Ambil elemen tombol
        const payButton = document.getElementById('pay-button');
        
        payButton.addEventListener('click', function () {
            // Memanggil Popup Midtrans dengan Snap Token dari Controller
            window.snap.pay('{{ $snapToken }}', {
                
                // Jika Berhasil (Success)
                onSuccess: function(result) {
                    console.log(result);
                    // Redirect ke halaman Finish (Buat route ini nanti)
                    window.location.href = "/payment/finish"; 
                },
                
                // Jika Menunggu (Pending - misal pilih transfer ATM tapi belum bayar)
                onPending: function(result) {
                    console.log(result);
                    window.location.href = "/payment/finish";
                },
                
                // Jika Gagal (Error)
                onError: function(result) {
                    console.log(result);
                    alert("Pembayaran Gagal atau Dibatalkan!");
                },
                
                // Jika popup ditutup tanpa menyelesaikan pembayaran
                onClose: function() {
                    alert('Kamu menutup popup sebelum menyelesaikan pembayaran');
                }
            });
        });
    </script>

</body>
</html>