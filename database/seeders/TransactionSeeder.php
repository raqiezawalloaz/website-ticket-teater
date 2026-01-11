<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::first();
        $event = \App\Models\Event::first();

        // Data 1: Tiket VIP yang sudah Lunas tapi Belum Masuk
        Transaction::create([
            'user_id'          => $user->id,
            'event_id'         => $event->id,
            'reference_number' => 'TKT-20251201',
            'ticket_code'      => 'TIX-VIP' . strtoupper(Str::random(5)),
            'customer_name'    => 'Budi Santoso',
            'customer_email'   => 'budi@gmail.com',
            'event_name'       => $event->nama_event,
            'ticket_category'  => 'VIP',
            'seat_number'      => 'A-01',
            'total_price'      => 500000,
            'status'           => 'success',
            'is_checked_in'    => false,
        ]);

        // Data 2: Tiket Reguler yang sudah Lunas dan SUDAH Masuk (Check-in)
        Transaction::create([
            'user_id'          => $user->id,
            'event_id'         => $event->id,
            'reference_number' => 'TKT-20251202',
            'ticket_code'      => 'TIX-REG' . strtoupper(Str::random(5)),
            'customer_name'    => 'Siti Aminah',
            'customer_email'   => 'siti@gmail.com',
            'event_name'       => $event->nama_event,
            'ticket_category'  => 'Reguler',
            'seat_number'      => 'C-15',
            'quantity'         => 3,
            'total_price'      => 150000,
            'status'           => 'success',
            'is_checked_in'    => true,
        ]);

        // Data 3: Tiket yang masih Menunggu Pembayaran (Pending)
        Transaction::create([
            'user_id'          => $user->id,
            'event_id'         => $event->id,
            'reference_number' => 'TKT-20251203',
            'ticket_code'      => 'TIX-REG' . strtoupper(Str::random(5)),
            'customer_name'    => 'Andi Wijaya',
            'customer_email'   => 'andi@gmail.com',
            'event_name'       => $event->nama_event,
            'ticket_category'  => 'Reguler',
            'seat_number'      => 'D-05',
            'quantity'         => 1, 
            'total_price'      => 150000,
            'status'           => 'pending',
            'is_checked_in'    => false,
        ]);

        // Data 4: Tiket Gagal Pembayaran
        Transaction::create([
            'user_id'          => $user->id,
            'event_id'         => $event->id,
            'reference_number' => 'TKT-20251204',
            'ticket_code'      => 'TIX-VIP' . strtoupper(Str::random(5)),
            'customer_name'    => 'Rina Pratama',
            'customer_email'   => 'rina@gmail.com',
            'event_name'       => $event->nama_event,
            'ticket_category'  => 'VIP',
            'seat_number'      => 'B-02',
            'quantity'         => 3,
            'total_price'      => 500000,
            'status'           => 'failed',
            'is_checked_in'    => false,
        ]);
    }
}