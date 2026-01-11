<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
   
    protected $table = 'feedback'; 

  
    protected $fillable = [
        'user_id', 
        'event_id', 
        'type', 
        'rating', 
        'message', 
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ambil relasi Event dari branch Main agar fitur Review bisa jalan
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}