<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'reference_number', 'ticket_code', 
        'total_price', 'status', 'is_checked_in'
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
}