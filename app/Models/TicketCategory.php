<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $table = 'ticket_categories';

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity',
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}

