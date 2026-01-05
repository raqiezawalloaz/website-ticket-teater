<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'ticket_category_id', 'reference_number', 'ticket_code',
        'customer_name', 'customer_email',
        'event_name', 'ticket_category', 'ticket_category_name',
        'total_price', 'status', 'is_checked_in', 'payment_url', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // RELASI 1: Transaksi dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI 2: Transaksi terhubung ke satu Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // RELASI 3: Transaksi terhubung ke kategori tiket
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}