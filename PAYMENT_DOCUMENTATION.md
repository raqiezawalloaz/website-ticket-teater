# 📋 DOKUMENTASI SISTEM PEMBAYARAN MIDTRANS

## ✅ Status Implementasi

### 1. PaymentCallbackController

-   **Location**: `app/Http/Controllers/Api/PaymentCallbackController.php`
-   **Status**: ✓ Complete dengan Logging
-   **Features**:
    -   ✓ Logging semua callback yang masuk
    -   ✓ Verifikasi signature Midtrans (keamanan)
    -   ✓ Mapping status capture → success
    -   ✓ Mapping status settlement → success
    -   ✓ Mapping status pending → pending
    -   ✓ Mapping status deny/expire/cancel → failed/expired

### 2. User Transaction View

-   **Location**: `resources/views/user/transactions/index.blade.php`
-   **Status**: ✓ Complete dengan Kondisional Buttons
-   **Kondisional Tombol**:
    -   Pending → Tombol "Bayar Sekarang" (redirect ke payment_url)
    -   Success → Tombol "Download E-Tiket" + "Detail"
    -   Expired/Failed → Tombol "Coba Lagi"

### 3. User Transaction Controller

-   **Location**: `app/Http/Controllers/User/TransactionController.php`
-   **Status**: ✓ Complete
-   **Methods**:
    -   `index()` - Tampilkan daftar transaksi user
    -   `show()` - Lihat detail transaksi
    -   `downloadTicket()` - Download E-Tiket PDF

### 4. Routes

-   **Location**: `routes/web.php`
-   **Status**: ✓ Complete
-   **Routes**:
    -   `GET /my-transactions` → user.transactions.index
    -   `GET /my-transactions/{id}` → user.transactions.show
    -   `GET /my-transactions/{id}/download` → user.transactions.download

---

## 🔄 Flow Pembayaran Lengkap

### Step 1: User Checkout

```
User klik "Bayar" → POST /checkout → CheckoutController::process()
```

### Step 2: Simpan ke Database

```
Transaction::create([
  'user_id' => Auth::id(),
  'reference_id' => 'TRX-' . time(),
  'status' => 'pending',
])
```

### Step 3: Redirect ke Midtrans

```
Snap::createTransaction($params) → redirect($paymentUrl)
```

### Step 4: User Bayar di Midtrans

-   Kartu Kredit → status: "capture"
-   GoPay/ShopeePay/Transfer → status: "settlement"

### Step 5: Webhook Callback

```
POST /api/midtrans-callback ← Midtrans
```

### Step 6: Update Status di Database

```
✓ capture (fraud=accept) → status = 'success'
✓ settlement → status = 'success'
⏳ pending → status = 'pending'
✗ deny/expire/cancel → status = 'failed'/'expired'
```

### Step 7: Log Activity

```
Log::info('Midtrans Callback Received: ', $request->all());
Log::info('✓ Transaction {$id} marked as SUCCESS');
```

### Step 8: View Auto-Update

```
Blade @if($trx->status == 'success')
  ← Tombol "Download E-Tiket" muncul otomatis
```

### Step 9: User Download E-Tiket

```
GET /my-transactions/{id}/download
→ GeneratePDF → E-TIKET-{reference_id}.pdf
```

---

## 📊 Status Mapping

| Midtrans Status | Fraud Status | Aplikasi Status | Arti                                            |
| --------------- | ------------ | --------------- | ----------------------------------------------- |
| `capture`       | `accept`     | ✓ success       | Pembayaran OK via Kartu Kredit                  |
| `settlement`    | -            | ✓ success       | Dana masuk ke bank (GoPay, ShopeePay, Transfer) |
| `pending`       | -            | ⏳ pending      | Menunggu pembayaran                             |
| `deny`          | -            | ✗ failed        | Pembayaran ditolak                              |
| `expire`        | -            | ⏰ expired      | Waktu pembayaran habis                          |
| `cancel`        | -            | ✗ failed        | Pembayaran dibatalkan                           |

---

## 🔐 Keamanan

### ✓ Signature Verification

```php
hash('sha512', order_id + status_code + gross_amount + server_key)
Dibandingkan dengan: $notification['signature_key']
```

### ✓ User Authorization

```php
Transaction::where('id', $id)
           ->where('user_id', Auth::id())  // User hanya lihat data mereka
           ->firstOrFail();
```

### ✓ CSRF Protection

```php
// Di bootstrap/app.php
$middleware->validateCsrfTokens(except: [
    'midtrans-callback',
    'api/midtrans-callback',
]);
```

---

## 📝 Logging Locations

### Main Log

```
storage/logs/laravel.log
```

### Sample Log Entry

```
[2025-01-05 10:30:45] local.INFO: ===== MIDTRANS CALLBACK RECEIVED =====
[2025-01-05 10:30:45] local.INFO: Callback Data: { "order_id": "TRX-1735980645", ... }
[2025-01-05 10:30:45] local.INFO: Processing Transaction TRX-1735980645: Status=settlement
[2025-01-05 10:30:45] local.INFO: ✓ Transaction TRX-1735980645 marked as SUCCESS
[2025-01-05 10:30:45] local.INFO: ===== CALLBACK PROCESSING COMPLETE =====
```

---

## 🎯 Troubleshooting

### "Saya sudah bayar tapi status masih pending"

1. Cek `storage/logs/laravel.log` → apakah webhook diterima?
2. Cek `SELECT * FROM transactions WHERE reference_id = 'TRX-...'`
3. Cek Midtrans Dashboard → apakah transaksi tercatat?
4. Verifikasi signature → apakah valid?

### "Tombol Download tidak muncul"

1. Cek status di database → harus `success`
2. Refresh halaman browser
3. Cek console browser → ada error?

### "E-Tiket PDF tidak bisa didownload"

1. Pastikan DomPDF sudah terinstall: `composer require barryvdh/laravel-dompdf`
2. Pastikan view `resources/views/user/transactions/ticket-pdf.blade.php` ada
3. Cek permission folder `storage/logs/`

---

## 📦 Dependencies

```bash
composer require midtrans/midtrans-php
composer require barryvdh/laravel-dompdf
```

---

## 🚀 Testing Webhook

### Via Postman

```
POST http://127.0.0.1:8000/api/midtrans-callback

Body (raw JSON):
{
  "order_id": "TRX-1735980645",
  "transaction_id": "1234567890",
  "transaction_status": "settlement",
  "payment_type": "gopay",
  "gross_amount": "50000",
  "status_code": "200",
  "fraud_status": "accept",
  "signature_key": "..." // Harus dicompute dengan benar
}
```

---

## ✨ Summary

✅ Sistem pembayaran Midtrans fully integrated
✅ Auto-update status dari webhook
✅ Kondisional UI berdasarkan status pembayaran
✅ E-Tiket PDF auto-download setelah bayar
✅ Logging lengkap untuk troubleshooting
✅ Security: Signature verification + User authorization

Selesai! 🎉
