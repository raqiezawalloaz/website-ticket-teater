# 🔧 FIX: Missing Columns in Transactions Table

## Error yang Terjadi

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'user_id' in 'where clause'
```

## Root Cause

Migration file `2025_12_29_061606_create_transactions_table.php` tidak mendefinisikan kolom:

-   ❌ `user_id` (foreign key ke users)
-   ❌ `event_id` (foreign key ke events)
-   ❌ `reference_id` (unique identifier untuk Midtrans)
-   ❌ `payment_url` (URL pembayaran Midtrans)
-   ❌ `paid_at` (timestamp ketika pembayaran sukses)

Tapi model `Transaction.php` dan controller mengharapkan kolom-kolom tersebut.

## Solusi yang Diimplementasikan

### 1. Buat Migration Baru

File: `database/migrations/2026_01_05_000000_add_missing_columns_to_transactions.php`

```php
Schema::table('transactions', function (Blueprint $table) {
    // Foreign keys
    $table->unsignedBigInteger('user_id')->nullable();
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    $table->unsignedBigInteger('event_id')->nullable();
    $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

    // Tambahan kolom
    $table->string('reference_id')->unique()->nullable();
    $table->string('payment_url')->nullable();
    $table->timestamp('paid_at')->nullable();
});
```

### 2. Jalankan Migration

```bash
php artisan migrate
```

Output:

```
2026_01_05_000000_add_missing_columns_to_transactions ...... DONE
```

### 3. Perbaiki Controller

Update `Admin\TransactionController.php`:

-   Ubah `reference_number` → `reference_id`

```php
// BEFORE
'reference_number' => 'TRX-' . time(),

// AFTER
'reference_id' => 'TRX-' . time(),
```

## Struktur Tabel Setelah Fix

```
transactions table:
├── id (PK)
├── user_id (FK → users)          ✅ ADDED
├── event_id (FK → events)        ✅ ADDED
├── reference_number (string)
├── reference_id (unique)         ✅ ADDED
├── ticket_code
├── customer_name
├── customer_email
├── event_name
├── ticket_category
├── seat_number
├── quantity
├── total_price
├── payment_url                   ✅ ADDED
├── status (enum)
├── paid_at (timestamp)           ✅ ADDED
├── is_checked_in (boolean)
├── created_at
├── updated_at
```

## Verification

### Migration Status

```bash
php artisan migrate:status
```

✅ 2026_01_05_000000_add_missing_columns_to_transactions ... [2] Ran

### Routes

```bash
php artisan route:list | grep user.transactions
```

✅ GET /my-transactions → user.transactions.index
✅ GET /my-transactions/{id} → user.transactions.show
✅ GET /my-transactions/{id}/download → user.transactions.download

### Test Query

```php
// User bisa lihat transaksi mereka
$transactions = Transaction::where('user_id', Auth::id())->get();
// ✅ Sekarang column 'user_id' ada!
```

## Status Sekarang

✅ Kolom user_id ada di tabel
✅ Kolom event_id ada di tabel
✅ Kolom reference_id ada di tabel
✅ Foreign keys sudah setup
✅ User TransactionController bisa query

## Next Steps

Refresh browser → akses `/my-transactions` → seharusnya berhasil!
