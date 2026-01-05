# 🎫 CAMPUS-EVENT: Fitur Checkout & Tiket

## 📊 Alur Sistem Lengkap

```
┌─────────────────────────────────────────────────────────────┐
│                   HALAMAN DETAIL EVENT                       │
│  (127.0.0.1:8000/events/1)                                  │
│                                                               │
│  ┌──────────────────────────────────┐                        │
│  │  Malam Kebudayaan                │                        │
│  │  📅 10 Jan 2026 - 14:00         │                        │
│  │  📍 Cikoneng                     │                        │
│  │                                  │                        │
│  │  ┌─ KATEGORI TIKET ────────────┐ │                        │
│  │  │ VIP                          │ │                        │
│  │  │ Rp 20.000 | 15 tiket tersedia│ │                        │
│  │  │ [BELI TIKET]               │ │  ← Click here!        │
│  │  └────────────────────────────┘ │                        │
│  └──────────────────────────────────┘                        │
└─────────────────────────────────────────────────────────────┘
                        ↓
         User submit form checkout
         (event_id, ticket_category_id, total_amount)
         ↓
┌─────────────────────────────────────────────────────────────┐
│         CHECKOUT CONTROLLER (POST /checkout)                │
│                                                               │
│  1. Ambil Event & TicketCategory data                        │
│  2. Create Transaction record di database:                   │
│     - reference_number: 'CAMPUS-176760253615'               │
│     - customer_name: 'Galih Hirpana'                        │
│     - event_name: 'Malam Kebudayaan'                        │
│     - ticket_category: 'vip'                                │
│     - status: 'pending'                                      │
│  3. Generate Midtrans Snap token                            │
│  4. Update transaction dengan payment_url                   │
│  5. Redirect ke Midtrans Snap (payment_url)                 │
└─────────────────────────────────────────────────────────────┘
                        ↓
         ┌─────────────────────────────────┐
         │  MIDTRANS SNAP PAYMENT PAGE    │
         │  (gateway.midtrans.com)         │
         │                                  │
         │  Order ID: CAMPUS-176760253615  │
         │  Amount: Rp 20.000              │
         │  Customer: Galih Hirpana        │
         │  Email: galihhirpana@gmail.com  │
         │                                  │
         │  [QRIS] [Bank Transfer] [CC]   │
         └─────────────────────────────────┘
              ↓ (user complete payment) ↓
         ┌─────────────────────────────────┐
         │  WEBHOOK CALLBACK                │
         │  (POST /api/midtrans-callback)  │
         │                                  │
         │  1. Verify signature             │
         │  2. Find transaction by ID       │
         │  3. Update status to 'success'   │
         │  4. Set paid_at timestamp        │
         │  5. Log transaksi lengkap        │
         └─────────────────────────────────┘
              ↓
         Auto redirect ke /my-transactions
                        ↓
┌─────────────────────────────────────────────────────────────┐
│           TIKET SAYA (GET /my-transactions)                 │
│                                                               │
│  🎫 Tiket Saya                                              │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Malam Kebudayaan              ✓ LUNAS                 │ │
│  │ Kategori: VIP                 Rp 20.000              │ │
│  │ Ref: CAMPUS-176760253615                             │ │
│  │ [DOWNLOAD] [DETAIL]                                  │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Concert Besar                 ⏳ MENUNGGU PEMBAYARAN   │ │
│  │ Kategori: REGULAR             Rp 50.000              │ │
│  │ Ref: CAMPUS-176760401234                             │ │
│  │ [BAYAR] [DETAIL]                                     │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
         ↓ (click DOWNLOAD pada tiket LUNAS)
         ↓
┌─────────────────────────────────────────────────────────────┐
│           DETAIL TIKET (GET /my-transactions/{id})           │
│                                                               │
│  ← Kembali                     MALAM KEBUDAYAAN    [✓ LUNAS] │
│                                                               │
│  ┌─ INFORMASI EVENT ────────────┬─ INFORMASI PEMBELI ────┐  │
│  │ Nama Event: Malam Kebudayaan │ Nama: Galih Hirpana  │  │
│  │ Kategori: VIP               │ Email: galih@...     │  │
│  │ Tanggal: 10 Jan 2026        │ Harga: Rp 20.000     │  │
│  │ Lokasi: Cikoneng            │ Tgl Beli: 05 Jan...  │  │
│  └────────────────────────────┴──────────────────────────┘  │
│                                                               │
│  ┌─ AKSI ────────────────────────────────────────────────┐  │
│  │ [DOWNLOAD E-TIKET] [KEMBALI]                         │  │
│  └───────────────────────────────────────────────────────┘  │
│                                                               │
│  ┌─ TIMELINE STATUS ──────────────────────────────────────┐  │
│  │ ✓ Pesanan Dibuat      05 Jan 2026 - 08:32           │  │
│  │ ✓ Pembayaran Berhasil 05 Jan 2026 - 08:35           │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
         ↓ (click DOWNLOAD E-TIKET)
         ↓
┌─────────────────────────────────────────────────────────────┐
│              E-TIKET PDF (ticket-pdf.blade.php)             │
│                                                               │
│  ╔═══════════════════════════════════════════════════════╗  │
│  ║              CAMPUS-EVENT E-TIKET                     ║  │
│  ║          Nomor Tiket: CAMPUS-176760253615            ║  │
│  ║                                                        ║  │
│  ║  ✓ PEMBAYARAN BERHASIL                               ║  │
│  ║                                                        ║  │
│  ║           MALAM KEBUDAYAAN                           ║  │
│  ║                                                        ║  │
│  ║  ┌─ KATEGORI ────────┬─ HARGA ──────────┐            ║  │
│  ║  │ VIP               │ Rp 20.000        │            ║  │
│  ║  ├───────────────────┼──────────────────┤            ║  │
│  ║  │ TANGGAL           │ WAKTU            │            ║  │
│  ║  │ 10 Jan 2026       │ 14:00 WIB        │            ║  │
│  ║  └─────────────────────────────────────┘            ║  │
│  ║                                                        ║  │
│  ║  ┌────────────────────────────────────────┐           ║  │
│  ║  │ PEMEGANG TIKET                         │           ║  │
│  ║  │ Galih Hirpana                          │           ║  │
│  ║  │ galihhirpana@gmail.com                 │           ║  │
│  ║  └────────────────────────────────────────┘           ║  │
│  ║                                                        ║  │
│  ║     ┌─────────────────────────┐                       ║  │
│  ║     │  CAMPUS-176760253615    │                       ║  │
│  ║     │  (Verification Code)    │                       ║  │
│  ║     └─────────────────────────┘                       ║  │
│  ║                                                        ║  │
│  ║  Tunjukkan tiket ini saat check-in. Berlaku hanya   ║  │
│  ║  untuk pemegang nama di atas.                        ║  │
│  ║                                                        ║  │
│  ║  Dicetak: 05 Jan 2026 - 08:45                       ║  │
│  ╚═══════════════════════════════════════════════════════╝  │
│                                                               │
│  File download: E-TIKET-Malam-Kebudayaan-CAMPUS-...pdf    │
└─────────────────────────────────────────────────────────────┘
```

---

## 🗂️ Database Schema

```
TRANSACTIONS TABLE:
├── id (PK)
├── user_id (FK → users)
├── event_id (FK → events)
├── ticket_category_id (FK → ticket_categories)
├── reference_number (UNIQUE) ← Identifier utama
├── ticket_code (UNIQUE, nullable)
├── customer_name
├── customer_email
├── event_name
├── ticket_category
├── ticket_category_name
├── total_price
├── status (pending/success/failed)
├── payment_url (nullable)
├── paid_at (nullable, timestamp)
├── is_checked_in (boolean)
├── created_at
└── updated_at
```

---

## 📡 API Endpoints

```
USER ENDPOINTS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
POST   /checkout                    Create transaction & redirect Midtrans
GET    /my-transactions             List user transactions
GET    /my-transactions/{id}        Show transaction detail
GET    /my-transactions/{id}/download Download PDF ticket

WEBHOOK ENDPOINTS:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
POST   /api/midtrans-callback       Midtrans payment notification
```

---

## 🎯 Status Flow

```
User Create Order
       ↓
  PENDING ← Waiting for payment
       ↓
  [Webhook receive notification]
       ↓
  SUCCESS ← Payment accepted ← Can download ticket
  or
  FAILED ← Payment rejected ← Need to retry
```

---

## 🔐 Security Features

✅ **User Authorization**

-   User hanya bisa lihat transaksi milik mereka sendiri
-   Filter by `Auth::id()` di semua query

✅ **Download Protection**

-   E-tiket hanya bisa didownload jika:
    -   User adalah pemilik transaksi
    -   Status transaksi = 'success'

✅ **Webhook Verification**

-   Verify Midtrans signature sebelum update
-   Prevent unauthorized status change

✅ **CSRF Protection**

-   Checkout form protected by CSRF token
-   Webhook endpoint di exception list

---

## 📊 Field Mapping

```
Checkout Form Input → Database Column
────────────────────────────────────────
event_id          → transactions.event_id
ticket_category_id → transactions.ticket_category_id
total_amount      → transactions.total_price
Auth user         → transactions.user_id, customer_name, customer_email
Event data        → transactions.event_name
Category data     → transactions.ticket_category, ticket_category_name

Midtrans Response → Database Column
────────────────────────────────────────
order_id          → reference_number (unique identifier)
transaction_status → status (pending/success/failed)
settlement_time   → paid_at (timestamp)
```

---

## ✨ Key Features

1. **Automatic Transaction Creation**

    - Data tersimpan saat user klik "Beli Tiket"
    - Reference number auto generated

2. **Seamless Payment Integration**

    - Direct redirect ke Midtrans Snap
    - No need manual gateway selection

3. **Real-time Status Update**

    - Webhook update transaction status
    - No polling needed

4. **Professional E-Ticket**

    - PDF dengan design menarik
    - Verification code untuk check-in
    - Denormalized data (historical accuracy)

5. **User-Friendly Dashboard**

    - Semua tiket di satu tempat
    - Filter by status
    - Pagination

6. **Comprehensive Logging**
    - Semua transaksi tercatat
    - Debug webhook issues easily

---

## 🚀 Ready to Go!

Sistem checkout dan tiket sudah **PRODUCTION READY**!

Sekarang user bisa:

1. ✅ Beli tiket untuk event
2. ✅ Bayar via Midtrans
3. ✅ Download e-tiket PDF
4. ✅ Manage semua tiket mereka

**Start testing now!** 🎉
