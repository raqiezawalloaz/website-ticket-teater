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

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    public function getRemainingStockAttribute()
    {
        $sold = $this->transactions()
            ->whereIn('status', ['success', 'pending'])
            ->sum('quantity');
            
        return max(0, $this->quantity - $sold);
    }
}

