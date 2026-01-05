# 🎫 Dokumentasi Fitur Checkout & Tiket

## Overview

Setelah user klik tombol **"Beli Tiket"**, sistem akan:

1. Membuat transaksi di database
2. Redirect ke Midtrans Snap untuk pembayaran
3. Setelah pembayaran, webhook akan update status
4. User bisa lihat tiket dan download e-tiket

---

## 1️⃣ Alur Checkout (Beli Tiket)

### User Flow:

```
Events Page → Click "Beli Tiket" → Checkout Form Submitted
→ Create Transaction (DB) → Redirect ke Midtrans Snap
→ Pembayaran → Webhook Update Status → Redirect ke "Tiket Saya"
```

### Files Terlibat:

-   **Controller**: `app/Http/Controllers/User/CheckoutController.php`
-   **Route**: `POST /checkout` (route name: `checkout.process`)
-   **View**: Inline form di `resources/views/user/events/show.blade.php`

### Data Transaksi yang Disimpan:

```php
[
    'user_id'              => Auth::id(),
    'event_id'             => $event->id,
    'ticket_category_id'   => $category->id,
    'reference_number'     => 'CAMPUS-' . time() . rand(10, 99),
    'customer_name'        => Auth::user()->name,
    'customer_email'       => Auth::user()->email,
    'event_name'           => $event->nama_event,
    'ticket_category'      => $category->name,
    'ticket_category_name' => $category->name,
    'total_price'          => $request->total_amount,
    'status'               => 'pending',
    'payment_url'          => Midtrans URL
]
```

---

## 2️⃣ Halaman "Tiket Saya" (Transaction Index)

### Route:

-   **GET** `/my-transactions` → `user.transactions.index`

### Features:

✅ Display semua tiket milik user  
✅ Filter berdasarkan status (pending, success, failed)  
✅ Pagination (9 item per halaman)  
✅ Status badge dengan warna berbeda  
✅ Quick action buttons

### Status Badge:

-   **Pending** (Kuning): Belum bayar, tombol "Bayar"
-   **Success** (Hijau): Sudah lunas, tombol "Download" dan "Detail"
-   **Failed** (Merah): Pembayaran gagal, tombol "Detail"

### View File:

`resources/views/user/transactions/index.blade.php`

---

## 3️⃣ Halaman Detail Tiket (Transaction Show)

### Route:

-   **GET** `/my-transactions/{id}` → `user.transactions.show`

### Features:

✅ Informasi lengkap transaksi  
✅ Status timeline  
✅ Action buttons sesuai status:

-   **Pending**: Tombol "Lanjutkan Pembayaran" & "Cek Status"
-   **Success**: Tombol "Download E-Tiket"
-   **Failed**: Tombol "Bayar Ulang"

### View File:

`resources/views/user/transactions/show.blade.php`

---

## 4️⃣ Download E-Tiket (PDF)

### Route:

-   **GET** `/my-transactions/{id}/download` → `user.transactions.download`

### Features:

✅ Hanya bisa download jika status = "success"  
✅ PDF dengan design profesional  
✅ Berisi: Nama event, kategori tiket, harga, tanggal, lokasi, nama pembeli, email  
✅ Verification code (reference_number) untuk check-in

### PDF File:

`resources/views/user/transactions/ticket-pdf.blade.php`

### Filename:

`E-TIKET-{event-name}-{reference-number}.pdf`

---

## 5️⃣ Payment Finish Page

### Route:

-   **GET** `/payment/finish` → `payment.finish`

### Features:

✅ Loading animation  
✅ Auto redirect ke "Tiket Saya" setelah 5 detik  
✅ Fallback button jika JS tidak berjalan

### View File:

`resources/views/user/payment_finish.blade.php`

---

## 6️⃣ Webhook Payment Status Update

### Route:

-   **POST** `/api/midtrans-callback` (CSRF exempt)

### Handler:

`app/Http/Controllers/Api/PaymentCallbackController@receive`

### Features:

✅ Verifikasi signature Midtrans  
✅ Update status transaksi based on payment state  
✅ Logging lengkap untuk debugging  
✅ Handle multiple payment statuses

### Status Mapping:

```
Midtrans → Database
settlement/capture → success
pending → pending
deny/cancel/expire → failed
```

---

## 🔧 Kustomisasi

### Ubah Warna Tiket (PDF):

Edit file: `resources/views/user/transactions/ticket-pdf.blade.php`

-   Background gradient: `linear-gradient(135deg, #667eea 0%, #764ba2 100%)`
-   Change di bagian `<style>`

### Ubah Harga/Info Field:

Di Controller `CheckoutController.php`, tambahkan field baru saat `Transaction::create()`

### Tambah New Status:

Di view, tambahkan kondisi `@elseif($transaction->status == 'new_status')`

---

## ⚠️ Important Notes

1. **reference_number** adalah unique identifier untuk setiap transaksi
2. **customer_name** dan **customer_email** disimpan saat pembelian (denormalisasi)
3. **payment_url** disimpan untuk retry pembayaran
4. User hanya bisa akses transactioni milik mereka sendiri (security check)
5. E-tiket hanya bisa didownload setelah pembayaran berhasil

---

## 📱 Testing Checklist

-   [ ] Click "Beli Tiket" → Redirect ke Midtrans
-   [ ] Bayar di Midtrans → Status berubah ke "success"
-   [ ] Lihat di "Tiket Saya" → Muncul dengan status "Lunas"
-   [ ] Click "Detail" → Lihat informasi lengkap
-   [ ] Click "Download" → PDF terdownload
-   [ ] Filter by status → Hanya tampil yang sesuai
-   [ ] Pagination → Jika > 9 tiket, ada halaman 2
-   [ ] Webhook log → Check di `storage/logs/laravel.log`

---

## 🚀 Next Steps (Optional Features)

1. **Email Notification** - Kirim email saat pembayaran berhasil
2. **QR Code** - Generate QR code di PDF tiket
3. **Check-in System** - Scan QR code saat event
4. **Refund** - Handle refund jika user request
5. **Ticket Transfer** - Allow user transfer tiket ke orang lain
6. **Reminder** - Send reminder email sebelum event
