<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'tanggal_event',
        'lokasi',
        'total_capacity',
        'status_event',
        'certificate_background',
    ];

    protected $casts = [
        'tanggal_event' => 'datetime',
    ];

    public function ticketCategories()
    {
        return $this->hasMany(\App\Models\TicketCategory::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(\App\Models\Feedback::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
}
