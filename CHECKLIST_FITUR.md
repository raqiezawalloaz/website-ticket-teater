# 📋 Checklist Fitur Checkout & Tiket

## Status Implementasi ✅

### 1. Checkout Flow

-   ✅ User bisa klik "Beli Tiket" di halaman event
-   ✅ Form checkout dengan event_id, ticket_category_id, total_amount
-   ✅ Data transaksi disimpan ke database
-   ✅ Redirect ke Midtrans Snap payment gateway
-   ✅ Payment URL disimpan untuk retry

### 2. Tiket Saya (Index)

-   ✅ Display semua tiket milik user
-   ✅ Pagination 9 item per halaman
-   ✅ Filter by status (pending, success, failed)
-   ✅ Status badge dengan icon dan warna
-   ✅ Quick action buttons:
    -   Pending: "Bayar" button
    -   Success: "Download" + "Detail" buttons
    -   Failed: "Detail" button

### 3. Detail Tiket (Show)

-   ✅ Informasi event lengkap
-   ✅ Informasi pembeli
-   ✅ Status timeline
-   ✅ Action buttons sesuai status
-   ✅ Link kembali ke index

### 4. Download E-Tiket (PDF)

-   ✅ PDF template profesional
-   ✅ Berisi info event, pembeli, harga
-   ✅ Verification code (reference_number)
-   ✅ Hanya bisa download jika status = success
-   ✅ Filename: E-TIKET-{event}-{reference}.pdf

### 5. Payment Webhook

-   ✅ Webhook handler di /api/midtrans-callback
-   ✅ Signature verification
-   ✅ Status update (pending → success/failed)
-   ✅ Logging untuk debugging
-   ✅ Transaction found by reference_number

### 6. Databases & Models

-   ✅ Transaction model dengan semua relationships
-   ✅ $fillable berisi semua required fields
-   ✅ Migrations untuk semua kolom
    -   user_id, event_id, ticket_category_id
    -   reference_number, ticket_code
    -   customer_name, customer_email
    -   event_name, ticket_category, ticket_category_name
    -   total_price, status, payment_url, paid_at
    -   is_checked_in, created_at, updated_at

### 7. Routes

-   ✅ POST /checkout → checkout.process
-   ✅ GET /my-transactions → user.transactions.index
-   ✅ GET /my-transactions/{id} → user.transactions.show
-   ✅ GET /my-transactions/{id}/download → user.transactions.download
-   ✅ POST /api/midtrans-callback → Api\PaymentCallbackController@receive

### 8. Controllers

-   ✅ CheckoutController::process()
-   ✅ TransactionController::index() with filter
-   ✅ TransactionController::show()
-   ✅ TransactionController::downloadTicket()
-   ✅ PaymentCallbackController::receive()

### 9. Views

-   ✅ resources/views/user/transactions/index.blade.php
-   ✅ resources/views/user/transactions/show.blade.php
-   ✅ resources/views/user/transactions/ticket-pdf.blade.php
-   ✅ resources/views/user/payment_finish.blade.php

---

## Cara Test Fitur

### Step 1: Beli Tiket

```
1. Login sebagai user
2. Ke halaman Events
3. Pilih event → Click "Beli Tiket"
4. Pilih kategori tiket
5. Klik "Bayar Sekarang"
6. Redirect ke Midtrans Snap
```

### Step 2: Simulasi Pembayaran

```
Di Midtrans Sandbox:
1. Click "Bayar" di halaman Snap
2. Pilih metode pembayaran (QRIS, Transfer Bank, dll)
3. Follow instruksi pembayaran
4. Setelah berhasil → auto redirect ke "Tiket Saya"
```

### Step 3: Lihat Tiket

```
1. Di "Tiket Saya", lihat transaksi dengan status "Lunas"
2. Click "Detail" untuk lihat informasi lengkap
3. Click "Download" untuk download PDF e-tiket
```

### Step 4: Verifikasi Database

```
Di MySQL/PHPMyAdmin:
- Cek tabel `transactions`
- Lihat record baru dengan status 'success'
- Verify semua field terisi dengan benar:
  - user_id, event_id, ticket_category_id
  - reference_number unik
  - customer_name, customer_email
  - event_name, ticket_category, ticket_category_name
  - total_price, status = 'success', payment_url, paid_at
```

### Step 5: Verifikasi Webhook

```
1. Check file: storage/logs/laravel.log
2. Cari log dari PaymentCallbackController
3. Verify signature & status update tercatat
```

---

## Troubleshooting

### Error: "Tiket belum tersedia"

-   Cek status transaksi: apakah sudah 'success'?
-   Cek di database → transactions table → status column
-   Jika masih 'pending', webhook belum jalan (cek logs)

### Error: "Transaksi tidak ditemukan"

-   Pastikan user login dengan akun yang benar
-   Cek user_id di database cocok dengan login user
-   Verifikasi transaction ID ada di URL

### PDF tidak download

-   Cek apakah Barryvdh DomPDF terinstall: `composer show | grep dompt`
-   Cek permission folder storage/logs/
-   Cek status transaksi = 'success'

### Webhook tidak jalan

-   Verifikasi CSRF exception di VerifyCsrfToken.php sudah ada
-   Cek di .env: MIDTRANS_SERVER_KEY dan CLIENT_KEY sudah benar
-   Test webhook dari Midtrans Dashboard → Settings → Webhook Test

### Payment stuck di Pending

-   Cek di Midtrans Dashboard → Transactions
-   Verify signature di PaymentCallbackController
-   Cek log file untuk error detail

---

## File Structure Summary

```
PROJECT/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── User/
│   │   │   │   ├── CheckoutController.php ✅
│   │   │   │   └── TransactionController.php ✅
│   │   │   ├── Api/
│   │   │   │   └── PaymentCallbackController.php ✅
│   │   │   └── ...
│   │   └── ...
│   ├── Models/
│   │   ├── Transaction.php ✅
│   │   ├── Event.php
│   │   ├── TicketCategory.php
│   │   └── ...
│   └── ...
├── database/
│   └── migrations/
│       ├── ..._create_transactions_table.php
│       ├── ..._add_missing_columns_to_transactions.php
│       ├── ..._add_ticket_category_id_to_transactions_table.php
│       ├── ..._add_default_to_ticket_code_column_on_transactions_table.php
│       └── ..._add_ticket_category_name_to_transactions_table.php
├── resources/
│   └── views/
│       ├── user/
│       │   ├── transactions/
│       │   │   ├── index.blade.php ✅
│       │   │   ├── show.blade.php ✅
│       │   │   └── ticket-pdf.blade.php ✅
│       │   ├── events/
│       │   │   └── show.blade.php (dengan form checkout)
│       │   └── payment_finish.blade.php ✅
│       └── ...
├── routes/
│   └── web.php (dengan semua routes ✅)
└── ...
```

---

## Performance Optimization (Optional)

### 1. Cache Event Data

```php
// Di CheckoutController
$event = Cache::remember('event_' . $request->event_id, 60*60, function() {
    return Event::find($request->event_id);
});
```

### 2. Eager Load Relationships

```php
// Di TransactionController::index()
$transactions = Transaction::with(['event', 'ticketCategory'])
    ->where('user_id', Auth::id())
    ->latest()
    ->paginate(9);
```

### 3. Index Database Columns

```php
// Di migration
$table->index('user_id');
$table->index('status');
$table->index('reference_number');
```

---

## Security Checklist

-   ✅ User hanya bisa akses transaksi milik mereka
-   ✅ Download PDF hanya jika auth + milik mereka + status success
-   ✅ Webhook verify signature sebelum update
-   ✅ CSRF protection kecuali untuk webhook endpoint
-   ✅ Reference number adalah unique identifier
-   ✅ No SQL injection (menggunakan Eloquent)

---

## Done! 🎉

Fitur checkout dan tiket sudah lengkap dan siap production!

### Next?

-   Test dengan real payment jika mau go live
-   Setup email notification
-   Create check-in system untuk event day
-   Monitor webhook logs regularly
