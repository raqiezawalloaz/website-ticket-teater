<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
     protected $fillable = [
    'reference_number', 
    'ticket_code', 
    'customer_name', 
    'customer_email', // Harus ada
    'event_name', 
    'ticket_category', 
    'seat_number', 
    'quantity', // Harus ada
    'total_price', 
    'status', 
    'is_checked_in'
];
}
