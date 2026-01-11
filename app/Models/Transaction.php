<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'ticket_category_id', 'reference_number', 'ticket_code', 
        'customer_name', 'customer_email', 'event_name', 'ticket_category',
        'seat_number', 'quantity', 'total_price', 'status', 'snap_token', 'is_checked_in'
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

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}