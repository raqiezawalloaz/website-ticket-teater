<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'event_id', 
        'ticket_category_id', 
        'reference_number', 
        'ticket_code',
        'customer_name', 
        'customer_email',
        'event_name', 
        'ticket_category', 
        'ticket_category_name',
        'seat_number', 
        'quantity', 
        'total_price', 
        'status', 
        'snap_token', 
        'is_checked_in', 
        'payment_url', 
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}